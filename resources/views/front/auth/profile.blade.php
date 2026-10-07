@extends('front.layouts.app')

@section('content')

<style>
.profile-header {
    background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
    padding: 40px 0;
}
.profile-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,.06);
    overflow: hidden;
}
.profile-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: #ffc700;
    color: #111;
    font-size: 38px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
}
.nav-tabs .nav-link {
    font-weight: 600;
    color: #4b5563;
    padding: 12px 24px;
    border: none;
    border-bottom: 3px solid transparent;
}
.nav-tabs .nav-link.active {
    color: #111827;
    background: transparent;
    border-bottom: 3px solid #ffc700;
}
.section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1f2937;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 8px;
    margin-bottom: 20px;
}
.form-label {
    font-weight: 600;
    font-size: 0.9rem;
    color: #374151;
}
</style>



<div class="container my-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="profile-card p-4">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-pane" type="button" role="tab">
                    <i class="bi bi-person-lines-fill me-2"></i> Profile Details
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bids-tab" data-bs-toggle="tab" data-bs-target="#bids-pane" type="button" role="tab">
                    <i class="bi bi-gavel me-2"></i> My Bids ({{ count($myBids) }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="winning-tab" data-bs-toggle="tab" data-bs-target="#winning-pane" type="button" role="tab">
                    <i class="bi bi-trophy-fill me-2 text-warning"></i> Approved / Winning Bids ({{ count($winningBids) }})
                </button>
            </li>
        </ul>

        <!-- Tabs Content -->
        <div class="tab-content" id="profileTabsContent">
            <!-- TAB 1: PROFILE DETAILS FORM -->
            <div class="tab-pane fade show active" id="details-pane" role="tabpanel">
                <form action="{{ route('front.profile.update') }}" method="POST">
                    @csrf
                    
                    <!-- Your Personal Details -->
                    <div class="section-title">
                        <i class="bi bi-person-fill text-warning me-2"></i> Your Personal Details
                    </div>

                    @php
                        $nameParts = explode(' ', $user->name, 2);
                        $defaultFirstName = $user->first_name ?: ($nameParts[0] ?? '');
                        $defaultLastName = $user->last_name ?: ($nameParts[1] ?? '');
                    @endphp

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $defaultFirstName) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $defaultLastName) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $user->company_name) }}" placeholder="e.g. Acme Corp">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Job Title</label>
                            <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $user->job_title) }}" placeholder="e.g. Manager">
                        </div>
                    </div>

                    <!-- Your Contact Details -->
                    <div class="section-title">
                        <i class="bi bi-geo-alt-fill text-warning me-2"></i> Your Contact Details
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                            <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1', $user->address_line1 ?: $user->address) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2', $user->address_line2) }}" placeholder="Apartment, suite, unit, etc.">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">County</label>
                            <input type="text" name="county" class="form-control" value="{{ old('county', $user->county) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Postcode | Zip Code <span class="text-danger">*</span></label>
                            <input type="text" name="postcode" class="form-control" value="{{ old('postcode', $user->postcode) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Country <span class="text-danger">*</span></label>
                            <input type="text" name="country" class="form-control" value="{{ old('country', $user->country ?: 'United Kingdom') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Main Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="main_phone" class="form-control" value="{{ old('main_phone', $user->main_phone ?: $user->phone) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" value="{{ old('mobile_number', $user->mobile_number ?: $user->mobile) }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-warning px-4 fw-bold">
                            <i class="bi bi-save me-1"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: MY BIDS -->
            <div class="tab-pane fade" id="bids-pane" role="tabpanel">
                @if($myBids->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-gavel fs-1 d-block mb-2"></i>
                        <h5>No Bids Placed Yet</h5>
                        <p class="small">Explore our live auction products and place your first bid!</p>
                        <a href="{{ route('front.auctions.index') }}" class="btn btn-dark btn-sm mt-2">
                            <i class="bi bi-eye me-1"></i> View Auction Products
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Auction Item</th>
                                    <th>Bid Price</th>
                                    <th>Quantity</th>
                                    <th>Submitted Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myBids as $bid)
                                    @php
                                        $prod = $bid->auctionProduct;
                                        $imgSrc = null;
                                        if ($prod && !empty($prod->image)) {
                                            $imgArr = is_array($prod->image) ? $prod->image : [$prod->image];
                                            $first = $imgArr[0] ?? null;
                                            if ($first) {
                                                $imgSrc = (str_starts_with($first, 'uploads/') || str_starts_with($first, 'storage/'))
                                                    ? asset($first) 
                                                    : asset('uploads/auction_products/' . $first);
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if($imgSrc)
                                                    <img src="{{ $imgSrc }}" class="rounded" width="50" height="50" style="object-fit:cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                                        <i class="bi bi-image text-muted fs-4"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $bid->auctionProduct->title ?? 'Auction Item #' . $bid->auction_product_id }}</h6>
                                                    <small class="text-muted">{{ $bid->auctionProduct->category->name ?? 'General' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            £{{ number_format($bid->bid_price, 2) }}
                                        </td>
                                        <td>{{ $bid->qty }}</td>
                                        <td class="small text-muted">
                                            {{ $bid->created_at ? $bid->created_at->format('d M Y, h:i A') : '-' }}
                                        </td>
                                        <td>
                                            @if($bid->status === 'approved' || $bid->status === 'completed' || $bid->status === 'won')
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> {{ ucfirst($bid->status) }}</span>
                                            @elseif($bid->status === 'rejected')
                                                <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Rejected</span>
                                            @else
                                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> Pending Review</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($bid->auctionProduct)
                                                <a href="{{ route('front.auctions.show', $bid->auctionProduct->id) }}" class="btn btn-sm btn-outline-dark">
                                                    View Details
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- TAB 3: APPROVED / WINNING BIDS -->
            <div class="tab-pane fade" id="winning-pane" role="tabpanel">
                @if($winningBids->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-trophy fs-1 d-block mb-2"></i>
                        <h5>No Approved / Winning Bids Yet</h5>
                        <p class="small">Your winning and admin-approved auction bids will appear here.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Winning Item</th>
                                    <th>Approved Bid Price</th>
                                    <th>Quantity</th>
                                    <th>Date Approved</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($winningBids as $bid)
                                    @php
                                        $prod = $bid->auctionProduct;
                                        $winImgSrc = null;
                                        if ($prod && !empty($prod->image)) {
                                            $imgArr = is_array($prod->image) ? $prod->image : [$prod->image];
                                            $first = $imgArr[0] ?? null;
                                            if ($first) {
                                                $winImgSrc = (str_starts_with($first, 'uploads/') || str_starts_with($first, 'storage/'))
                                                    ? asset($first) 
                                                    : asset('uploads/auction_products/' . $first);
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                @if($winImgSrc)
                                                    <img src="{{ $winImgSrc }}" class="rounded" width="50" height="50" style="object-fit:cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                                        <i class="bi bi-image text-muted fs-4"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $bid->auctionProduct->title ?? 'Auction Item #' . $bid->auction_product_id }}</h6>
                                                    <small class="text-muted">{{ $bid->auctionProduct->category->name ?? 'General' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-success fs-5">
                                            £{{ number_format($bid->bid_price, 2) }}
                                        </td>
                                        <td>{{ $bid->qty }}</td>
                                        <td class="small text-muted">
                                            {{ $bid->updated_at ? $bid->updated_at->format('d M Y, h:i A') : '-' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-success px-3 py-2"><i class="bi bi-trophy-fill me-1"></i> {{ ucfirst($bid->status) }}</span>
                                        </td>
                                        <td class="text-end">
                                            @if($bid->auctionProduct)
                                                <a href="{{ route('front.auctions.show', $bid->auctionProduct->id) }}" class="btn btn-sm btn-dark">
                                                    View Item
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection