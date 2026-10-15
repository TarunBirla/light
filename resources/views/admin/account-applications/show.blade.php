@extends('layouts.admin')

@section('page-title', 'Account Application #' . $application->id)
@section('breadcrumb', 'Admin / Account Applications / Details')

@section('content')

<style>
    .detail-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #E8E6DF;
        padding: 28px;
        margin-bottom: 24px;
    }
    .detail-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        border-bottom: 2px solid #FFC700;
        padding-bottom: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .field-label {
        font-size: 13px;
        font-weight: 600;
        color: #777;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .field-value {
        font-size: 15px;
        font-weight: 600;
        color: #111;
        margin-bottom: 18px;
    }
    .status-badge {
        font-size: 13px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-pending { background: #FFF3B0; color: #B38A00; }
    .status-under_review { background: #EAF3FF; color: #1a5fb4; }
    .status-approved { background: #EDFAF0; color: #1a7a3a; }
    .status-rejected { background: #FEF0F0; color: #c0392b; }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1">
            <i class="fa-solid fa-file-invoice me-2" style="color:#FFC700"></i>Application #{{ $application->id }}
        </h3>
        <p class="text-muted small mb-0">Submitted on {{ $application->created_at ? $application->created_at->format('d M Y, h:i A') : '-' }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.account-applications.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to List
        </a>
        <button onclick="window.print()" class="btn btn-light border">
            <i class="fa-solid fa-print me-1"></i> Print Application
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Main Application Details -->
    <div class="col-lg-8">
        <div class="detail-card">
            <!-- Company / Customer Details -->
            <div class="detail-section-title">
                <i class="fa-solid fa-building text-warning"></i> Company / Customer Details
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="field-label">Contact Person Name</div>
                    <div class="field-value text-dark">{{ $application->contact_person_name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="field-label">Full Legal Name</div>
                    <div class="field-value text-dark">{{ $application->full_legal_name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="field-label">Trading Name</div>
                    <div class="field-value">{{ $application->trading_name ?: 'N/A' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="field-label">Type of Business</div>
                    <div class="field-value"><span class="badge bg-dark">{{ $application->business_type ?: 'Not specified' }}</span></div>
                </div>
                <div class="col-md-6">
                    <div class="field-label">Company Registration No.</div>
                    <div class="field-value">{{ $application->company_registration_no ?: 'N/A' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="field-label">VAT Number</div>
                    <div class="field-value">{{ $application->vat_number ?: 'N/A' }}</div>
                </div>
                <div class="col-md-12">
                    <div class="field-label">Website</div>
                    <div class="field-value">
                        @if($application->website)
                            <a href="{{ $application->website }}" target="_blank" class="text-primary text-decoration-none">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>{{ $application->website }}
                            </a>
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>

            <!-- Address & Contact Details -->
            <div class="detail-section-title mt-2">
                <i class="fa-solid fa-location-dot text-warning"></i> Address & Contact Details
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="field-label">Registered Address</div>
                    <div class="field-value p-3 bg-light rounded-3" style="font-weight: normal; line-height: 1.6;">
                        {!! nl2br(e($application->registered_address)) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="field-label">Trading Address</div>
                    <div class="field-value p-3 bg-light rounded-3" style="font-weight: normal; line-height: 1.6;">
                        @if($application->trading_address)
                            {!! nl2br(e($application->trading_address)) !!}
                        @else
                            <span class="text-muted fst-italic">Same as registered address</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                    <div class="field-label">Telephone Number</div>
                    <div class="field-value">
                        <a href="tel:{{ $application->telephone }}" class="text-dark text-decoration-none">
                            <i class="fa-solid fa-phone text-warning me-1"></i>{{ $application->telephone }}
                        </a>
                    </div>
                </div>
                <div class="col-md-6 mt-2">
                    <div class="field-label">Email Address</div>
                    <div class="field-value">
                        <a href="mailto:{{ $application->email }}" class="text-primary text-decoration-none">
                            <i class="fa-solid fa-envelope text-warning me-1"></i>{{ $application->email }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status & Admin Action Sidecard -->
    <div class="col-lg-4">
        <div class="detail-card">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-sliders text-warning me-2"></i>Status & Verification</h5>

            <div class="mb-4">
                <div class="field-label">Current Status</div>
                <span class="status-badge status-{{ $application->status }} mt-1">
                    @if($application->status === 'approved')
                        <i class="fa-solid fa-circle-check"></i> Approved
                    @elseif($application->status === 'under_review')
                        <i class="fa-solid fa-clock"></i> Under Review
                    @elseif($application->status === 'rejected')
                        <i class="fa-solid fa-circle-xmark"></i> Rejected
                    @else
                        <i class="fa-solid fa-hourglass-start"></i> Pending Review
                    @endif
                </span>
            </div>

            <form method="POST" action="{{ route('admin.account-applications.status', $application->id) }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="field-label">Update Status</label>
                    <select name="status" class="form-select">
                        <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="under_review" {{ $application->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="approved" {{ $application->status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="field-label">Internal Admin Notes</label>
                    <textarea name="admin_notes" class="form-control" rows="4" placeholder="Add internal verification notes or comments...">{{ old('admin_notes', $application->admin_notes) }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">
                    <i class="fa-solid fa-check me-1"></i> Save Changes
                </button>
            </form>

            <hr class="my-4">

            <div class="d-flex justify-content-between">
                <form action="{{ route('admin.account-applications.destroy', $application->id) }}" method="POST" class="w-100" onsubmit="return confirm('Delete this application permanently?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="fa-solid fa-trash me-1"></i> Delete Application
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
