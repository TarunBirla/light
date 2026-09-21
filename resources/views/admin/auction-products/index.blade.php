@extends('layouts.admin')

@section('page-title', 'Auction Products')
@section('breadcrumb', 'Admin / Auction Products')

@section('content')

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .page-header h3 {
            font-size: 22px;
            font-weight: 700;
            color: #111;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FFC700;
            color: #111;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background .2s, transform .15s;
            font-family: 'Akshar', sans-serif;
        }

        .btn-add:hover {
            background: #E6B200;
            color: #111;
            transform: translateY(-1px);
        }

        .table-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #E8E6DF;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-card table {
            width: 100%;
            min-width: 950px;
            border-collapse: collapse;
        }

        .table-card thead tr {
            background: #FAFAF8;
            border-bottom: 1px solid #F0EEE8;
        }

        .table-card thead th {
            padding: 13px 14px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #888;
            text-align: left;
            white-space: nowrap;
        }

        .table-card tbody tr {
            border-bottom: 1px solid #F7F6F1;
            transition: background .15s;
        }

        .table-card tbody tr:hover {
            background: #FAFAF8;
        }

        .table-card tbody td {
            padding: 13px 14px;
            font-size: 14px;
            color: #111;
            vertical-align: middle;
        }

        .item-img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #E8E6DF;
        }

        .item-title {
            font-weight: 600;
            color: #111;
        }

        .qty-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: #EAF3FF;
            color: #1a5fb4;
        }

        .price-tag {
            font-weight: 700;
            color: #166534;
            font-size: 15px;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-active {
            background: #EDFAF0;
            color: #1a7a3a;
        }

        .badge-inactive {
            background: #F5F5F5;
            color: #888;
        }

        .action-group {
            display: flex;
            gap: 8px;
        }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            background: #FFF3B0;
            color: #B38A00;
            border: none;
            cursor: pointer;
            font-family: 'Akshar', sans-serif;
            transition: background .2s;
        }

        .btn-edit:hover {
            background: #FFC700;
            color: #111;
        }

        .btn-del {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            background: #FEF0F0;
            color: #c0392b;
            border: none;
            cursor: pointer;
            font-family: 'Akshar', sans-serif;
            transition: background .2s;
        }

        .btn-del:hover {
            background: #c0392b;
            color: #fff;
        }

        .id-badge {
            display: inline-block;
            background: #F7F6F1;
            color: #888;
            border-radius: 6px;
            padding: 3px 9px;
            font-size: 12px;
            font-weight: 600;
        }

        .cat-tag {
            display: inline-block;
            background: #FFF3B0;
            color: #B38A00;
            border-radius: 6px;
            padding: 3px 10px;
            font-size: 12px;
            font-weight: 600;
        }

        .empty-row td {
            text-align: center;
            padding: 60px 0;
            color: #bbb;
            font-size: 14px;
        }

        .pagination-wrap {
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .search-input {
            width: 320px;
            height: 44px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 0 15px;
            font-size: 14px;
            outline: none;
            transition: .2s;
        }

        .search-input:focus {
            border-color: #FFC700;
            box-shadow: 0 0 0 3px rgba(255, 199, 0, .15);
        }

        .btn-clear {
            height: 44px;
            padding: 0 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            text-decoration: none;
            color: #666;
            font-weight: 600;
            font-family: 'Akshar', sans-serif;
        }
    </style>

    <div class="page-header">
        <h3><i class="fa-solid fa-gavel me-2" style="color:#FFC700"></i>Auction Products</h3>
        <a href="{{ route('auction-products.create') }}" class="btn-add">
            <i class="fa-solid fa-plus"></i> Add Auction Product
        </a>
    </div>

    <div style="display:flex;align-items:center;gap:10px; margin-bottom:20px;">
        <form method="GET" action="{{ route('auction-products.index') }}" style="display:flex;gap:10px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search auction product or category..." class="search-input">
            <button type="submit" class="btn-add">
                <i class="fa-solid fa-search"></i> Search
            </button>
        </form>

        @if(request('search'))
            <a href="{{ route('auction-products.index') }}" class="btn-clear">
                <i class="fa-solid fa-xmark"></i> Clear
            </a>
        @endif
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Min Price</th>
                    <th>Shipping</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auctionProducts as $product)
                    <tr>
                        <td><span class="id-badge">{{ $product->id }}</span></td>
                        <td>
                            @php
                                $images = $product->image ?? [];
                                if (!is_array($images)) {
                                    $images = [$images];
                                }
                                $firstImage = $images[0] ?? null;
                            @endphp

                            <div style="position:relative">
                                @if($firstImage)
                                    <img src="{{ asset('uploads/auction_products/' . $firstImage) }}" class="item-img">
                                @else
                                    <div class="item-img d-flex align-items-center justify-content-center bg-light text-muted">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif

                                @if(count($images) > 1)
                                    <span style="position:absolute; top:-5px; right:-5px; background:#FFC700; color:#111; padding:3px 8px; border-radius:20px; font-size:11px; font-weight:700;">
                                        +{{ count($images) - 1 }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="item-title">{{ $product->title }}</span>
                        </td>
                        <td>
                            <span class="cat-tag">{{ $product->category->name ?? '—' }}</span>
                        </td>
                        <td>
                            <span class="qty-pill">{{ $product->qty }}</span>
                        </td>
                        <td>
                            <span class="price-tag">£{{ number_format($product->minprice, 2) }}</span>
                        </td>
                        <td>
                            @if($product->shipping_type == 'included')
                                <span class="badge bg-success text-white px-2 py-1">Included</span>
                            @elseif($product->shipping_type == 'both')
                                <span class="badge bg-info text-white px-2 py-1">Both</span>
                            @else
                                <span class="badge bg-secondary text-white px-2 py-1">Excluded</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $product->sort_order }}</span>
                        </td>
                        <td>
                            @if($product->status == 'active')
                                <span class="badge-status badge-active">
                                    <i class="fa-solid fa-circle" style="font-size:7px"></i> Active
                                </span>
                            @else
                                <span class="badge-status badge-inactive">
                                    <i class="fa-regular fa-circle" style="font-size:7px"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="{{ route('auction-products.edit', $product->id) }}" class="btn-edit">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                                <form action="{{ route('auction-products.destroy', $product->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-del" onclick="return confirm('Delete this auction product?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="10">
                            <i class="fa-solid fa-gavel" style="font-size:36px;display:block;margin-bottom:10px"></i>
                            No auction products found. <a href="{{ route('auction-products.create') }}" style="color:#FFC700;font-weight:600">Add one?</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="custom-pagination">
        {{ $auctionProducts->appends(request()->query())->onEachSide(1)->links() }}
    </div>
@endsection
