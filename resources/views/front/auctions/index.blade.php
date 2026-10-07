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



<div class="container py-3">
    
    <!-- Filter Bar & Tabs Row -->
    <div class="row mb-2 align-items-center g-3">
        <div class="col-lg-7 col-md-6">
            <form method="GET" action="{{ route('front.auctions.index') }}" class="d-flex gap-2 flex-wrap align-items-center">
                <input type="hidden" name="tab" value="{{ $tab ?? 'active' }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search auction products..." class="form-control" style="max-width:240px;">
                <select name="category_id" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-dark"><i class="bi bi-search"></i> Search</button>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('front.auctions.index', ['tab' => $tab ?? 'active']) }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </form>
        </div>
        <div class="col-lg-5 col-md-6 text-md-end">
            <ul class="nav nav-pills d-inline-flex gap-1 bg-light p-1 rounded-3 border">
                <li class="nav-item">
                    <a class="nav-link btn-sm fw-bold px-3 py-1-5 {{ ($tab ?? 'active') === 'active' ? 'active bg-warning text-dark shadow-sm' : 'text-dark' }}" 
                       style="border-radius:6px; font-size:0.82rem;"
                       href="{{ route('front.auctions.index', array_merge(request()->query(), ['tab' => 'active'])) }}">
                        <i class="bi bi-gavel me-1"></i> Active Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-sm fw-bold px-3 py-1-5 {{ ($tab ?? 'active') === 'old' ? 'active bg-dark text-white shadow-sm' : 'text-dark' }}" 
                       style="border-radius:6px; font-size:0.82rem;"
                       href="{{ route('front.auctions.index', array_merge(request()->query(), ['tab' => 'old'])) }}">
                        <i class="bi bi-clock-history me-1"></i> Old / Past Products
                    </a>
                </li>
            </ul>
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

    <!-- ================= SECTION 1: OVERVIEW & IMAGE ================= -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 mt-4">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-12 text-center mb-2">
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase rounded-pill mb-2">
                        <i class="bi bi-info-circle me-1"></i> Overview
                    </span>
                    <h2 class="fw-bold text-dark mb-3">How the Auction Works</h2>
                    <p class="text-muted fs-5 max-w-700 mx-auto">
                        The auction runs for a limited time, but you’ll have plenty of opportunity to view all lots before bidding.
                    </p>
                </div>
                <div class="col-12">
                    <div class="position-relative rounded-4 overflow-hidden shadow">
                        <img src="{{ asset('imgauction.jpg') }}" alt="Auction Banner" class="img-fluid w-100" style="max-height: 520px; object-fit: cover; object-position: center;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= SECTION 2: STEPS TO PARTICIPATE ================= -->
    <div class="my-5">
        <div class="text-center mb-4">
            <span class="text-warning fw-bold text-uppercase tracking-wider small">GETTING STARTED</span>
            <h2 class="fw-bold text-dark mt-1 mb-2">Steps to Participate</h2>
            <p class="text-muted">Follow these simple steps to join our auctions, place bids, and collect your items.</p>
        </div>

        @php
            $defaultSteps = [
                ['step_number' => 1, 'title' => 'Register your interest', 'description' => 'Create an account or sign in to get started with auction bidding.', 'icon' => 'bi-person-plus-fill'],
                ['step_number' => 2, 'title' => 'Receive confirmation', 'description' => 'Receive account confirmation and bidding eligibility verification.', 'icon' => 'bi-envelope-check-fill'],
                ['step_number' => 3, 'title' => 'View auction lots', 'description' => 'Browse all active lots, inspect item specs and photos online.', 'icon' => 'bi-grid-3x3-gap-fill'],
                ['step_number' => 4, 'title' => 'Place your bids', 'description' => 'Submit your offer or competitive bids on desired equipment.', 'icon' => 'bi-gavel'],
                ['step_number' => 5, 'title' => 'Viewing day (optional)', 'description' => 'Schedule an in-person viewing of equipment on designated viewing days.', 'icon' => 'bi-calendar-event-fill'],
                ['step_number' => 6, 'title' => 'Winning an item', 'description' => 'Receive instant notification if your offer or bid is accepted by admin.', 'icon' => 'bi-trophy-fill'],
                ['step_number' => 7, 'title' => 'Payment', 'description' => 'Complete your payment securely via bank transfer or online invoice.', 'icon' => 'bi-credit-card-fill'],
                ['step_number' => 8, 'title' => 'Collection', 'description' => 'Arrange collection or pickup of your wining auction items.', 'icon' => 'bi-truck'],
            ];
            $displaySteps = count($auctionSteps) > 0 ? $auctionSteps : $defaultSteps;
        @endphp

        <div class="row g-3">
            @foreach($displaySteps as $idx => $st)
                @php
                    $stepNo = is_array($st) ? $st['step_number'] : ($st->step_number ?? ($idx + 1));
                    $title = is_array($st) ? $st['title'] : $st->title;
                    $desc = is_array($st) ? $st['description'] : $st->description;
                    $icon = is_array($st) ? ($st['icon'] ?? 'bi-check-circle-fill') : ($st->icon ?? 'bi-check-circle-fill');
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 hover-lift p-3 bg-white" style="transition: transform .2s, box-shadow .2s;">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="bg-warning text-dark fw-bold rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; font-size: 1.1rem;">
                                {{ $stepNo }}
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">{{ $title }}</h6>
                        </div>
                        @if($desc)
                            <p class="text-muted small mb-0 ms-1">{{ $desc }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ================= SECTION 3: FREQUENTLY ASKED QUESTIONS ================= -->
    <div class="my-5">
        <div class="text-center mb-4">
            <span class="text-warning fw-bold text-uppercase tracking-wider small">SUPPORT</span>
            <h2 class="fw-bold text-dark mt-1 mb-2">Frequently Asked Questions</h2>
            <p class="text-muted">Find quick answers to the most common questions about registration, bidding, payment and collection.</p>
        </div>

        @php
            $defaultFaqs = [
                ['question' => 'How do I register for an auction?', 'answer' => 'You can register by creating an account on our website. Once logged in, you can place bids directly on any active auction product.'],
                ['question' => 'What happens after I place a bid?', 'answer' => 'Your bid request is submitted to our team for review. You can track your submitted and winning bids inside your account profile.'],
                ['question' => 'Can I inspect items before bidding?', 'answer' => 'Yes, designated viewing days are available upon request. Please contact our support team to arrange an in-person viewing.'],
                ['question' => 'How do I pay for won items?', 'answer' => 'Once your bid is approved or accepted, you will receive an invoice with payment details including bank transfer instructions.'],
                ['question' => 'How does item collection work?', 'answer' => 'After payment confirmation, collection details and pickup slots will be provided so you or your courier can collect the items.'],
            ];
            $displayFaqs = count($auctionFaqs) > 0 ? $auctionFaqs : $defaultFaqs;
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion custom-accordion" id="auctionFaqAccordion">
                    @foreach($displayFaqs as $fIndex => $faq)
                        @php
                            $q = is_array($faq) ? $faq['question'] : $faq->question;
                            $a = is_array($faq) ? $faq['answer'] : $faq->answer;
                        @endphp
                        <div class="accordion-item border-0 shadow-sm rounded-3 mb-3 overflow-hidden">
                            <h2 class="accordion-header" id="headingFaq{{ $fIndex }}">
                                <button class="accordion-button {{ $fIndex !== 0 ? 'collapsed' : '' }} fw-bold text-dark py-3 px-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFaq{{ $fIndex }}" aria-expanded="{{ $fIndex === 0 ? 'true' : 'false' }}">
                                    <i class="bi bi-question-circle-fill text-warning me-2"></i> {{ $q }}
                                </button>
                            </h2>
                            <div id="collapseFaq{{ $fIndex }}" class="accordion-collapse collapse {{ $fIndex === 0 ? 'show' : '' }}" aria-labelledby="headingFaq{{ $fIndex }}" data-bs-parent="#auctionFaqAccordion">
                                <div class="accordion-body bg-light text-muted px-4 py-3 border-top">
                                    {!! nl2br(e($a)) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
