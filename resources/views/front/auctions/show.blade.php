@extends('front.layouts.app')

@section('content')

<style>
    .auction-detail-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #E8E6DF;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        padding: 30px;
    }
    .main-img {
        width: 100%;
        height: 420px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #E8E6DF;
    }
    .thumb-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all .2s;
    }
    .thumb-img.active, .thumb-img:hover {
        border-color: var(--brand);
    }
    .price-pill {
        display: inline-block;
        background: #EDFAF0;
        color: #1a7a3a;
        font-size: 1.5rem;
        font-weight: 800;
        padding: 6px 20px;
        border-radius: 30px;
        border: 1px solid #b3e6c2;
    }
    .btn-place-bid {
        background: var(--brand);
        color: #111111;
        font-weight: 800;
        font-size: 1.1rem;
        padding: 14px 32px;
        border-radius: 10px;
        border: none;
        width: 100%;
        transition: all .2s;
    }
    .btn-place-bid:hover {
        background: #E6B200;
        transform: translateY(-2px);
    }
</style>

<div class="container py-5">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="auction-detail-card">
        <div class="row g-4">
            
            <!-- Images Gallery -->
            <div class="col-lg-6">
                @php
                    $images = $auctionProduct->image ?? [];
                    if (!is_array($images)) { $images = [$images]; }
                    $firstImage = $images[0] ?? null;
                @endphp

                @if($firstImage)
                    <img id="mainProductImage" src="{{ asset('uploads/auction_products/' . $firstImage) }}" class="main-img mb-3">
                @else
                    <div class="main-img d-flex align-items-center justify-content-center bg-light text-muted mb-3">
                        <i class="bi bi-card-image fs-1"></i>
                    </div>
                @endif

                @if(count($images) > 1)
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach($images as $idx => $img)
                            <img src="{{ asset('uploads/auction_products/' . $img) }}" class="thumb-img {{ $idx == 0 ? 'active' : '' }}" onclick="changeMainImage(this, '{{ asset('uploads/auction_products/' . $img) }}')">
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info & Bid CTA -->
            <div class="col-lg-6 d-flex flex-column justify-content-between">
                <div>
                    <div class="mb-2 d-flex flex-wrap gap-2 align-items-center">
                        <span class="badge bg-warning text-dark fw-bold px-3 py-2">Auction Product</span>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            Category: {{ $auctionProduct->category->name ?? 'Uncategorized' }}
                        </span>
                        @if(($auctionProduct->shipping_type ?? 'excluded') == 'included')
                            <span class="badge bg-success text-white px-3 py-2">
                                <i class="bi bi-truck me-1"></i> Included Shipping cost
                            </span>
                        @elseif(($auctionProduct->shipping_type ?? 'excluded') == 'both')
                            <span class="badge bg-info text-dark px-3 py-2">
                                <i class="bi bi-truck me-1"></i> Included & Excluded Shipping cost
                            </span>
                        @else
                            <span class="badge bg-secondary text-white px-3 py-2">
                                <i class="bi bi-truck me-1"></i> Excluded Shipping cost
                            </span>
                        @endif
                    </div>

                    <h2 class="fw-bold text-dark mt-2 mb-3">{{ $auctionProduct->title }}</h2>

                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-4">
                            <small class="text-muted d-block uppercase fw-bold mb-1">Minimum Price</small>
                            <span class="price-pill" style="font-size:1.2rem; padding:4px 14px;">£{{ number_format($auctionProduct->minprice, 2) }}</span>
                        </div>
                        <!-- <div class="col-6 col-md-4">
                            <small class="text-muted d-block uppercase fw-bold mb-1">Highest Bid Offered</small>
                            <span class="price-pill" style="font-size:1.2rem; padding:4px 14px; background:#D4EDDA; color:#155724; border-color:#c3e6cb;">
                                £{{ number_format($highestBid, 2) }}
                            </span>
                        </div> -->
                        <div class="col-12 col-md-4">
                            <small class="text-muted d-block uppercase fw-bold mb-1">Total Bids Placed</small>
                            <span class="badge bg-dark text-white fs-6 px-3 py-2" style="border-radius:20px;">
                                <i class="bi bi-gavel me-1 text-warning"></i> {{ $auctionProduct->requests->count() }} Bids
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        @if($auctionProduct->available_qty > 0)
                            <span class="fw-semibold text-secondary">
                                <i class="bi bi-boxes me-1"></i> Quantity Available: <strong>{{ $auctionProduct->available_qty }}</strong>
                            </span>
                        @else
                            <span class="badge bg-danger text-white fs-6 px-3 py-2 fw-bold">
                                <i class="bi bi-x-circle me-1"></i> SOLD OUT (0 Available)
                            </span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-2">Description</h5>
                        <div class="text-muted" style="line-height:1.6;">
                            {!! $auctionProduct->description ?? 'No description available for this product.' !!}
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top">
                    @if($auctionProduct->available_qty > 0)
                        <button type="button" class="btn-place-bid" data-bs-toggle="modal" data-bs-target="#auctionBidModal">
                            <i class="bi bi-gavel me-2"></i> Bid Now / Request Auction
                        </button>
                        <small class="text-muted d-block text-center mt-2">
                            <i class="bi bi-shield-check me-1"></i> Guest Mode — Instant Request Submission
                        </small>
                    @else
                        <button class="btn btn-danger btn-lg w-100 fw-bold disabled" disabled style="border-radius:10px; padding:14px;">
                            <i class="bi bi-x-circle me-2"></i> PRODUCT SOLD OUT
                        </button>
                        <small class="text-danger d-block text-center mt-2 fw-semibold">
                            This auction product has been fully approved/completed and is no longer accepting bids.
                        </small>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Public Bids History Table -->
    <div class="auction-detail-card mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0 text-dark">
                <i class="bi bi-gavel text-warning me-2"></i> Live Bids History
                <span class="badge bg-secondary fs-6 rounded-pill align-middle ms-2">{{ $auctionProduct->requests->count() }}</span>
            </h4>
            <span class="text-muted small"><i class="bi bi-arrow-repeat me-1"></i> Publicly Visible Bids</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Bidder Name</th>
                        <!-- <th>Offered Price</th> -->
                        <th>Quantity</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auctionProduct->requests as $index => $bid)
                        <tr class="{{ $bid->bid_price == $highestBid && $highestBid > 0 ? 'table-warning' : '' }}">
                            <td><span class="fw-bold text-muted">#{{ $index + 1 }}</span></td>
                            <td>
                                <div class="fw-bold text-dark">
                                    {{ $bid->name }}
                                    @if($bid->bid_price == $highestBid && $highestBid > 0)
                                        <span class="badge bg-warning text-dark ms-1" style="font-size:10px;">Highest Bid</span>
                                    @endif
                                </div>
                            </td>
                            <!-- <td>
                                <span class="fw-bold text-success fs-6">
                                    £{{ number_format($bid->bid_price ?? $auctionProduct->minprice, 2) }}
                                </span>
                            </td> -->
                            <td>
                                <span class="badge bg-light text-dark border">{{ $bid->qty }}</span>
                            </td>
                            <td class="text-muted small">
                                {{ $bid->created_at ? $bid->created_at->format('d M Y, h:i A') : '—' }}
                            </td>
                            <td>
                                @if($bid->status == 'completed')
                                    <span class="badge bg-primary px-3 py-1">Completed</span>
                                @elseif($bid->status == 'approved')
                                    <span class="badge bg-success px-3 py-1">Approved</span>
                                @elseif($bid->status == 'rejected')
                                    <span class="badge bg-danger px-3 py-1">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-1">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-info-circle fs-3 d-block mb-2 text-secondary"></i>
                                No bids submitted yet for this product. Be the first to place a bid!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Box for Auction Request -->
<div class="modal fade" id="auctionBidModal" tabindex="-1" aria-labelledby="auctionBidModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="auctionBidModalLabel">
                    <i class="bi bi-gavel text-warning me-2"></i> Submit Auction Request
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('front.auctions.store-request') }}" method="POST" id="auctionRequestForm" onsubmit="return handleAuctionSubmit(event)">
                @csrf
                <input type="hidden" name="auction_product_id" value="{{ $auctionProduct->id }}">

                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 py-2 px-3 small mb-4">
                        <i class="bi bi-info-circle me-1"></i> Bidding on: <strong>{{ $auctionProduct->title }}</strong> (Min Price: £{{ number_format($auctionProduct->minprice, 2) }} | Available Qty: <strong>{{ $auctionProduct->available_qty }}</strong>)
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Your Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" placeholder="+44 1234 567890" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Your Bid Offer Price (£) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="bid_price" id="requestBidPriceInput" class="form-control" value="{{ old('bid_price', $auctionProduct->minprice) }}" min="{{ $auctionProduct->minprice }}" oninput="checkMinBidPrice(this, {{ $auctionProduct->minprice }})" required>
                            <small class="text-muted">Min required price: <strong>£{{ number_format($auctionProduct->minprice, 2) }}</strong></small>
                            <div id="priceErrorMsg" class="text-danger small mt-1" style="display:none;"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Product Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="qty" id="requestQtyInput" class="form-control" value="1" min="1" max="{{ $auctionProduct->available_qty }}" oninput="checkMaxQty(this, {{ $auctionProduct->available_qty }})" required>
                            <small class="text-muted">Min: 1 | Max available: <strong>{{ $auctionProduct->available_qty }}</strong></small>
                            <div id="qtyErrorMsg" class="text-danger small mt-1" style="display:none;"></div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Delivery / Contact Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Enter your full street address, city, postcode..." required></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Message / Bid Offer Details</label>
                            <textarea name="message" class="form-control" rows="2" placeholder="Enter your message or custom offer notes..."></textarea>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="form-check bg-light p-3 rounded-3 border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="terms" id="termsCheck" value="1" required style="cursor:pointer; width:18px; height:18px;">
                                <label class="form-check-label fw-medium text-dark small" for="termsCheck" style="cursor:pointer; line-height: 1.5;">
                                    I agree to the <a href="#" onclick="event.preventDefault(); alert('By submitting an auction request, you agree to abide by the bidding rules and terms of Light As Air.');" style="color:var(--brand-dark, #B38A00); font-weight:700; text-decoration:underline;">Terms and Conditions</a> of Light As Air auction bidding. <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitBidBtn" class="btn btn-warning px-4 text-dark fw-bold" style="background:var(--brand); border:none;">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function changeMainImage(element, src) {
        document.getElementById('mainProductImage').src = src;
        document.querySelectorAll('.thumb-img').forEach(el => el.classList.remove('active'));
        element.classList.add('active');
    }

    function checkMinBidPrice(input, minPrice) {
        let val = parseFloat(input.value);
        let errorDiv = document.getElementById('priceErrorMsg');

        if (isNaN(val) || val < minPrice) {
            errorDiv.style.display = 'block';
            errorDiv.innerText = 'Bid offer price must be at least £' + minPrice.toFixed(2);
        } else {
            errorDiv.style.display = 'none';
        }
    }

    function checkMaxQty(input, maxQty) {
        let val = parseInt(input.value);
        let errorDiv = document.getElementById('qtyErrorMsg');

        if (isNaN(val) || val < 1) {
            errorDiv.style.display = 'block';
            errorDiv.innerText = 'Quantity must be at least 1.';
        } else if (val > maxQty) {
            input.value = maxQty;
            errorDiv.style.display = 'block';
            errorDiv.innerText = 'Quantity cannot exceed available stock (' + maxQty + '). Set to ' + maxQty + '.';
        } else {
            errorDiv.style.display = 'none';
        }
    }

    async function handleAuctionSubmit(event) {
        event.preventDefault();

        let qtyInput = document.getElementById('requestQtyInput');
        let priceInput = document.getElementById('requestBidPriceInput');
        let maxQty = {{ (int)$auctionProduct->available_qty }};
        let minPrice = {{ (float)$auctionProduct->minprice }};

        let qtyVal = parseInt(qtyInput.value);
        let priceVal = parseFloat(priceInput.value);

        if (isNaN(priceVal) || priceVal < minPrice) {
            alert('Your bid offer price must be at least £' + minPrice.toFixed(2));
            return false;
        }
        if (isNaN(qtyVal) || qtyVal < 1) {
            alert('Quantity must be at least 1.');
            return false;
        }
        if (qtyVal > maxQty) {
            alert('Quantity cannot be more than available stock (' + maxQty + ').');
            qtyInput.value = maxQty;
            return false;
        }

        const btn = document.getElementById('submitBidBtn');
        btn.disabled = true;
        btn.innerText = 'Submitting...';

        const form = document.getElementById('auctionRequestForm');
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (!response.ok || !data.status) {
                btn.disabled = false;
                btn.innerText = 'Submit Request';
                alert(data.message || 'Submission failed. Please check your inputs.');
                return false;
            }

            let msg = `🔥 NEW LIGHT AS AIR AUCTION REQUEST

Item: ${data.item || '{{ addslashes($auctionProduct->title) }}'}
Offered Bid Price: £${data.bid_price}
Quantity: ${data.qty}

Name: ${data.name}
Email: ${data.email}
Phone: ${data.phone}
Address: ${data.address || 'N/A'}${data.user_msg ? '\nMessage: ' + data.user_msg : ''}`;

            window.open(
                `https://wa.me/447879175585?text=${encodeURIComponent(msg)}`,
                '_blank'
            );

            const modalEl = document.getElementById('auctionBidModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }

            alert('✅ Request submitted successfully! Opening WhatsApp...');
            window.location.reload();
        } catch (error) {
            console.error(error);
            btn.disabled = false;
            btn.innerText = 'Submit Request';
            alert('An error occurred. Please try again.');
        }
        return false;
    }
</script>

@endsection
