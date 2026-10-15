@extends('front.layouts.app')

@section('title', 'Open an Account – Light As Air')
@section('meta_description', 'Apply to open a trade account with Light As Air. Professional cinematic lighting equipment rental, purchasing and auction services across the UK.')

@section('content')

<style>
    .account-hero {
        background: linear-gradient(135deg, #111111 0%, #1e1e1e 100%);
        color: #ffffff;
        padding: 50px 0 35px;
        text-align: center;
        border-bottom: 3px solid var(--brand);
    }
    .account-hero h1 {
        font-size: 2.4rem;
        font-weight: 800;
        margin-bottom: 10px;
    }
    .account-hero h1 span {
        color: var(--brand);
    }
    .form-container-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #E8E6DF;
        box-shadow: 0 6px 30px rgba(0,0,0,0.06);
        padding: 40px;
        margin-top: -25px;
        margin-bottom: 60px;
    }
    .section-divider-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #111;
        border-bottom: 2px solid var(--brand);
        padding-bottom: 10px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .form-label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #222;
        margin-bottom: 6px;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #E2E0D8;
        padding: 10px 14px;
        font-size: 0.92rem;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(255, 199, 0, 0.25);
    }
    .business-type-card {
        border: 1.5px solid #E2E0D8;
        border-radius: 10px;
        padding: 12px 14px;
        cursor: pointer;
        transition: all .2s;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
    }
    .business-type-card:hover {
        border-color: var(--brand);
        background: #FFFDF0;
    }
    .business-type-card input[type="radio"]:checked + label {
        font-weight: 700;
        color: #111;
    }
    .btn-submit-account {
        background: var(--brand);
        color: #111;
        font-weight: 700;
        font-size: 1.05rem;
        padding: 14px 35px;
        border-radius: 10px;
        border: none;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit-account:hover {
        background: var(--brand-dk);
        color: #111;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 199, 0, 0.35);
    }
</style>

<!-- Hero Section -->
<div class="account-hero">
    <div class="container">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase rounded-pill mb-2">
            <i class="bi bi-file-earmark-person me-1"></i> Official Trade Application
        </span>
        <h1>ACCOUNT <span>OPENING FORM</span></h1>
        <p class="text-light fs-5 mx-auto mb-0" style="max-width: 650px;">
            Open an account with Light As Air to access trade rental rates, live auctions, and priority equipment sourcing.
        </p>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="form-container-card">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show p-4 rounded-4 mb-4" role="alert" style="background:#EDFAF0; border:1px solid #b3e6c2; color:#1a7a3a;">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                            <div>
                                <h5 class="fw-bold mb-1">Application Submitted Successfully!</h5>
                                <p class="mb-0 small">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
                        <strong class="d-block mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Please check the required fields:</strong>
                        <ul class="mb-0 small ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('front.account-opening.store') }}">
                    @csrf

                    <!-- Section: Company / Customer Details -->
                    <div class="section-divider-title">
                        <i class="bi bi-building text-warning fs-5"></i> Company / Customer Details
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Contact Person Name <span class="text-danger">*</span></label>
                            <input type="text" name="contact_person_name" class="form-control" value="{{ old('contact_person_name') }}" placeholder="e.g. John Doe" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Full Legal Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_legal_name" class="form-control" value="{{ old('full_legal_name') }}" placeholder="Registered Company or Legal Name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Trading Name <span class="text-muted">(if any)</span></label>
                            <input type="text" name="trading_name" class="form-control" value="{{ old('trading_name') }}" placeholder="Trading / Doing Business As name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Company Registration No. <span class="text-muted">(if applicable)</span></label>
                            <input type="text" name="company_registration_no" class="form-control" value="{{ old('company_registration_no') }}" placeholder="e.g. 12345678">
                        </div>

                        <div class="col-12">
                            <label class="form-label d-block">Type of Business <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                @php
                                    $types = ['Sole Trader', 'Partnership', 'Ltd Co', 'Other'];
                                    $selectedType = old('business_type', 'Ltd Co');
                                @endphp
                                @foreach($types as $type)
                                    <div class="col-6 col-md-3">
                                        <div class="business-type-card">
                                            <input class="form-check-input mt-0" type="radio" name="business_type" id="type_{{ Str::slug($type) }}" value="{{ $type }}" {{ $selectedType === $type ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100 cursor-pointer mb-0 small" for="type_{{ Str::slug($type) }}">
                                                {{ $type }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">VAT Number <span class="text-muted">(if applicable)</span></label>
                            <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number') }}" placeholder="e.g. GB123456789">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Website <span class="text-muted">(optional)</span></label>
                            <input type="text" name="website" class="form-control" value="{{ old('website') }}" placeholder="https://www.example.com">
                        </div>
                    </div>

                    <!-- Section: Address & Contact Details -->
                    <div class="section-divider-title">
                        <i class="bi bi-geo-alt text-warning fs-5"></i> Address & Contact Information
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Registered Address <span class="text-danger">*</span></label>
                            <textarea name="registered_address" class="form-control" rows="3" placeholder="Full registered official address..." required>{{ old('registered_address') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Trading Address <span class="text-muted">(if different from registered)</span></label>
                            <textarea name="trading_address" class="form-control" rows="3" placeholder="Studio, warehouse, or operating address...">{{ old('trading_address') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Telephone <span class="text-danger">*</span></label>
                            <input type="tel" name="telephone" class="form-control" value="{{ old('telephone') }}" placeholder="e.g. +44 01932 55 3284" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="accounts@example.com" required>
                            <small class="text-muted">A confirmation copy of this form will be sent to this email.</small>
                        </div>
                    </div>

                    <!-- Declaration & Submit -->
                    <div class="p-3 rounded-3 mb-4" style="background: #F8F7F3; border: 1px solid #E8E6DF;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="declarationCheck" required>
                            <label class="form-check-label small text-muted" for="declarationCheck">
                                I confirm that all information provided in this Account Opening Form is accurate and true to the best of my knowledge.
                            </label>
                        </div>
                    </div>

                    <div class="text-center pt-2">
                        <button type="submit" class="btn-submit-account">
                            <i class="bi bi-send-check"></i> Submit Account Opening Application
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

@endsection
