@extends('layouts.admin')

@section('page-title', 'Auction Requests')
@section('breadcrumb', 'Admin / Auction Requests')

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

        .table-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #E8E6DF;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-card table {
            width: 100%;
            min-width: 1000px;
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

        .id-badge {
            display: inline-block;
            background: #F7F6F1;
            color: #888;
            border-radius: 6px;
            padding: 3px 9px;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-del {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
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

        .search-input {
            width: 280px;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 0 15px;
            font-size: 14px;
            outline: none;
        }

        .search-input:focus {
            border-color: #FFC700;
        }

        .status-select {
            padding: 4px 8px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
        }

        .status-pending { background: #FFF3B0; color: #856404; }
        .status-approved { background: #D4EDDA; color: #155724; }
        .status-rejected { background: #F8D7DA; color: #721C24; }
        .status-completed { background: #CCE5FF; color: #004085; }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FFC700;
            color: #111;
            font-size: 14px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 10px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .custom-pagination {
            display: flex;
            justify-content: center;
            margin: 25px 0;
        }

        .custom-pagination nav {
            display: flex;
        }

        .custom-pagination .pagination {
            display: flex;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .custom-pagination .page-item .page-link {
            min-width: 42px;
            height: 42px;
            border-radius: 10px;
            border: 1px solid #E8E6DF;
            background: #fff;
            color: #111;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .2s ease;
        }

        .custom-pagination .page-item .page-link:hover,
        .custom-pagination .page-item.active .page-link {
            background: #FFC700;
            border-color: #FFC700;
            color: #111;
        }

        .custom-pagination .page-item.disabled .page-link {
            background: #f5f5f5;
            color: #aaa;
            cursor: not-allowed;
        }

        .custom-pagination svg {
            width: 16px;
            height: 16px;
        }
    </style>

    <div class="page-header">
        <h3><i class="fa-solid fa-list-check me-2" style="color:#FFC700"></i>Auction Requests</h3>
    </div>

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
        <form method="GET" action="{{ route('auction-requests.index') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer, email, phone, product..." class="search-input">
            
            <select name="status" class="form-select" style="width:160px; height:40px; border-radius:10px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>

            <button type="submit" class="btn-add">
                <i class="fa-solid fa-search"></i> Filter
            </button>

            @if(request('search') || request('status'))
                <a href="{{ route('auction-requests.index') }}" class="btn btn-outline-secondary" style="height:40px; border-radius:10px; display:inline-flex; align-items:center;">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Min Price</th>
                    <th>Offered Bid Price</th>
                    <th>Customer Name</th>
                    <th>Contact Info</th>
                    <th>Qty</th>
                    <!-- <th>Message</th> -->
                    <!-- <th>Date</th> -->
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr>
                        <td><span class="id-badge">{{ $req->id }}</span></td>
                        <td>
                            <strong style="color:#111;">{{ $req->auctionProduct->title ?? 'Deleted Product' }}</strong>
                            @if($req->auctionProduct && $req->auctionProduct->category)
                                <br><small class="text-muted">{{ $req->auctionProduct->category->name }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-secondary">
                                £{{ number_format($req->auctionProduct->minprice ?? 0, 2) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success text-white fs-6 px-3 py-2 fw-bold" style="border-radius:20px;">
                                £{{ number_format($req->bid_price ?? $req->auctionProduct->minprice ?? 0, 2) }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $req->name }}</strong>
                        </td>
                        <td>
                            <div><i class="fa-regular fa-envelope me-1 text-muted"></i>{{ $req->email }}</div>
                            <div><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $req->phone }}</div>
                            @if($req->address)
                                <div class="small text-secondary mt-1" style="max-width:220px; word-break:break-word;"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $req->address }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $req->qty }}</span>
                        </td>
                        <!-- <td>
                            <small class="text-secondary" style="max-width:200px; display:inline-block; word-break:break-word;">
                                {{ $req->message ?? '—' }}
                            </small>
                        </td> -->
                        <!-- <td>
                            <small class="text-muted">{{ $req->created_at ? $req->created_at->format('d M Y, h:i A') : '—' }}</small>
                        </td> -->
                        <td>
                            <form action="{{ route('auction-requests.status', $req->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="status-select status-{{ $req->status }}" onchange="this.form.submit()">
                                    <option value="pending" {{ $req->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $req->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $req->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="completed" {{ $req->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('auction-requests.destroy', $req->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-del" onclick="return confirm('Delete this request?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-clipboard-list mb-2" style="font-size:32px;"></i>
                            <div>No auction requests found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="custom-pagination">
        {{ $requests->appends(request()->query())->onEachSide(1)->links() }}
    </div>

@endsection
