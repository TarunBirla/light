@extends('front.layouts.app')

@section('content')

<style>
    .auction-hero {
        background: linear-gradient(135deg, #111111 0%, #1e1e1e 100%);
        color: #ffffff;
        padding: 60px 0 40px;
        text-align: center;
        border-bottom: 3px solid var(--brand);
    }
    .auction-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }
    .auction-hero h1 span {
        color: var(--brand);
    }
    .auction-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #E8E6DF;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow-x: auto;
    }
    .auction-table {
        width: 100%;
        min-width: 800px;
        border-collapse: collapse;
    }
    .auction-table thead tr {
        background: #111111;
        color: #ffffff;
    }
    .auction-table thead th {
        padding: 16px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .auction-table tbody tr {
        border-bottom: 1px solid #E8E6DF;
        transition: background .2s;
    }
    .auction-table tbody tr:hover {
        background: #FFFDF0;
    }
    .auction-table tbody td {
        padding: 16px;
        vertical-align: middle;
    }
    .product-thumb {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #ddd;
    }
    .btn-auction {
        background: var(--brand);
        color: #111111;
        font-weight: 700;
        padding: 8px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all .2s;
        border: none;
    }
    .btn-auction:hover {
        background: #E6B200;
        color: #111111;
        transform: translateY(-2px);
    }
    .price-badge {
        background: #EDFAF0;
        color: #1a7a3a;
        font-weight: 800;
        font-size: 1.1rem;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .custom-pagination {
        display: flex;
        justify-content: center;
        margin: 25px 0;
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
        background: var(--brand);
        border-color: var(--brand);
        color: #111;
    }
    .custom-pagination .page-item.disabled .page-link {
        background: #f5f5f5;
        color: #aaa;
        cursor: not-allowed;
    }
</style>

<div class="auction-hero">
    <div class="container">
        <h1>Live <span>Auction Products</span></h1>
        <p class="text-white-50">Submit your bid for equipment & products. Guest Mode — No registration required!</p>
    </div>
</div>

<div class="container py-5">
    
    <!-- Tabs Navigation -->
    <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3">
        <li class="nav-item">
            <a class="nav-link fw-bold px-4 py-2 {{ ($tab ?? 'active') === 'active' ? 'active bg-warning text-dark' : 'bg-light text-dark border' }}" 
               style="border-radius:10px;"
               href="{{ route('front.auctions.index', array_merge(request()->query(), ['tab' => 'active'])) }}">
                <i class="bi bi-gavel me-1"></i> Active Auction Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-bold px-4 py-2 {{ ($tab ?? 'active') === 'old' ? 'active bg-dark text-white' : 'bg-light text-dark border' }}" 
               style="border-radius:10px;"
               href="{{ route('front.auctions.index', array_merge(request()->query(), ['tab' => 'old'])) }}">
                <i class="bi bi-clock-history me-1"></i> Old / Past Auction Products
            </a>
        </li>
    </ul>
    
    <!-- Filter Bar -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8 mb-3 mb-md-0">
            <form method="GET" action="{{ route('front.auctions.index') }}" class="d-flex gap-2 flex-wrap">
                <input type="hidden" name="tab" value="{{ $tab ?? 'active' }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search auction products..." class="form-control" style="max-width:280px;">
                <select name="category_id" class="form-select" style="max-width:200px;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i> Search</button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('front.auctions.index') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Auction Table View -->
    <div class="auction-card">
        <table class="auction-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Min Price</th>
                    <!-- <th>Highest Offered Bid</th> -->
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auctionProducts as $product)
                    @php
                        $imgs = $product->image ?? [];
                        if (!is_array($imgs)) { $imgs = [$imgs]; }
                        $firstImg = $imgs[0] ?? null;
                        $highest = $product->requests ? $product->requests->max('bid_price') : null;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($firstImg)
                                    <img src="{{ asset('uploads/auction_products/' . $firstImg) }}" class="product-thumb">
                                @else
                                    <div class="product-thumb d-flex align-items-center justify-content-center bg-light text-muted">
                                        <i class="bi bi-card-image fs-3"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-1 fw-bold text-dark">{{ $product->title }}</h5>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-2 fs-6">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td>
                            @if($product->available_qty > 0)
                                <span class="fw-semibold">{{ $product->available_qty }} Available</span>
                            @else
                                <span class="badge bg-danger text-white px-2 py-1">0 Available</span>
                            @endif
                        </td>
                        <td>
                            <span class="price-badge">£{{ number_format($product->minprice, 2) }}</span>
                        </td>
                        <!-- <td>
                            @if($highest && $highest > 0)
                                <span class="badge bg-success text-white px-3 py-2 fs-6 fw-bold" style="border-radius:20px;">
                                    £{{ number_format($highest, 2) }}
                                </span>
                            @else
                                <span class="text-muted small">No Bids Yet</span>
                            @endif
                        </td> -->
                        <td class="text-center">
                            @if($product->available_qty > 0)
                                <a href="{{ route('front.auctions.show', $product->id) }}" class="btn-auction">
                                    <i class="bi bi-gavel"></i> Auction
                                </a>
                            @else
                                <span class="badge bg-danger text-white px-3 py-2 fs-6 text-uppercase fw-bold">
                                    <i class="bi bi-x-circle me-1"></i> SOLD OUT
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            <h5>No auction products available right now.</h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="custom-pagination">
        {{ $auctionProducts->appends(request()->query())->onEachSide(1)->links() }}
    </div>

</div>

@endsection
