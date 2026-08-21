@extends('front.layouts.app')

@section('content')
<style>
    .eq-page-header {
        background: #111;
        color: #fff;
        padding: 35px 0;
        margin-bottom: 25px;
        border-bottom: 4px solid var(--brand);
    }

    .eq-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .eq-card-header {
        background: #111;
        color: #fff;
        padding: 14px 20px;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: .5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .eq-card-header i {
        color: var(--brand);
    }

    .eq-card-body {
        padding: 22px;
    }

    .eq-form-label {
        font-weight: 600;
        font-size: 0.88rem;
        margin-bottom: 5px;
        color: #222;
    }

    .eq-form-control {
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 9px 13px;
        font-size: 0.92rem;
        transition: border-color .2s, box-shadow .2s;
    }

    .eq-form-control:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(255, 199, 0, 0.25);
        outline: none;
    }

    .eq-section-subtitle {
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--dark);
        border-bottom: 2px solid var(--brand);
        padding-bottom: 6px;
        margin-bottom: 16px;
        letter-spacing: 0.5px;
    }

    /* Category Tabs Styling */
    .category-tabs-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
        background: #f4f4f4;
        padding: 10px;
        border-radius: 12px;
        border: 1px solid #e2e2e2;
    }

    .cat-tab-btn {
        background: #fff;
        color: #333;
        border: 1px solid #ddd;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 9px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cat-tab-btn:hover {
        background: #e9e9e9;
        color: #111;
    }

    .cat-tab-btn.active {
        background: var(--brand);
        color: #111;
        border-color: var(--brand-dk);
        font-weight: 700;
        box-shadow: 0 3px 10px rgba(255, 199, 0, 0.3);
    }

    .badge-qty-count {
        background: #111;
        color: var(--brand);
        font-size: 0.75rem;
        padding: 2px 7px;
        border-radius: 10px;
        font-weight: 700;
    }

    /* 2-Column Product Grid & Compact Cards */
    .category-scroll-container {
        max-height: 440px;
        overflow-y: auto;
        padding-right: 6px;
    }

    .category-scroll-container::-webkit-scrollbar {
        width: 6px;
    }

    .category-scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .category-scroll-container::-webkit-scrollbar-thumb {
        background: var(--brand);
        border-radius: 3px;
    }

    .product-item-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px 14px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        height: 100%;
    }

    .product-item-card:hover {
        border-color: #bbb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .product-item-card.has-qty {
        border-color: var(--brand);
        background-color: #fffdf2;
        box-shadow: 0 0 0 1px var(--brand);
    }

    .product-title-text {
        font-weight: 600;
        font-size: 0.92rem;
        color: #111;
        word-break: break-word;
    }

    .qty-input-group {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        background: #eee;
        border: 1px solid #ccc;
        font-weight: bold;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        user-select: none;
        transition: background .15s;
    }

    .qty-btn:hover {
        background: var(--brand);
        border-color: var(--brand-dk);
    }

    .qty-btn.btn-minus {
        border-radius: 6px 0 0 6px;
    }

    .qty-btn.btn-plus {
        border-radius: 0 6px 6px 0;
    }

    .qty-number-input {
        width: 50px;
        height: 32px;
        text-align: center;
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        border-left: none;
        border-right: none;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .qty-number-input::-webkit-inner-spin-button,
    .qty-number-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Fixed Submit Form Card at Bottom */
    .submit-card {
        background: #111;
        color: #fff;
        border-radius: var(--radius);
        padding: 24px;
        box-shadow: var(--shadow);
        border-top: 4px solid var(--brand);
        margin-top: 25px;
    }

    .submit-summary-box {
        font-size: 1.1rem;
    }

    .submit-summary-box strong {
        color: var(--brand);
        font-size: 1.3rem;
    }
</style>



<div class="container pb-5">

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-x-circle-fill me-2"></i> Please fix the following errors:</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('equipment-request.store') }}" method="POST" id="equipmentRequestForm">
        @csrf

        <!-- SECTION 1: PERMANENT PRODUCTION INFORMATION FORM -->
        <div class="eq-card">
            <div class="eq-card-header">
                <i class="bi bi-film"></i>
                <span>1. PERMANENT PRODUCTION INFORMATION</span>
            </div>
            <div class="eq-card-body">

                <!-- Production Details -->
                <div class="eq-section-subtitle">
                    <i class="bi bi-person-lines-fill me-1"></i> Production Information
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="eq-form-label">Gaffer</label>
                        <input type="text" name="gaffer" class="form-control eq-form-control" placeholder="e.g. Stephen Mathie" value="{{ old('gaffer') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="eq-form-label">Email</label>
                        <input type="email" name="email" class="form-control eq-form-control" placeholder="e.g. contact@production.com" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="eq-form-label">Contact Phone</label>
                        <input type="text" name="contact" class="form-control eq-form-control" placeholder="e.g. 07973 4271..." value="{{ old('contact') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="eq-form-label">Production Co.</label>
                        <input type="text" name="production_company" class="form-control eq-form-control" placeholder="e.g. 72 Films" value="{{ old('production_company') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="eq-form-label">Production Title <span class="text-danger">*</span></label>
                        <input type="text" name="production_title" class="form-control eq-form-control" placeholder="e.g. Handcuffed" value="{{ old('production_title') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="eq-form-label">Production Contact</label>
                        <input type="text" name="production_contact" class="form-control eq-form-control" placeholder="Contact person name" value="{{ old('production_contact') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="eq-form-label">DoP (Director of Photography)</label>
                        <input type="text" name="dop" class="form-control eq-form-control" placeholder="e.g. Justin Frahms" value="{{ old('dop') }}">
                    </div>
                </div>

                <!-- Production Dates -->
                <div class="eq-section-subtitle">
                    <i class="bi bi-calendar-range me-1"></i> Production Dates
                </div>

                <div class="row g-3 mb-4">
                    <!-- Rig -->
                    <div class="col-md-3 col-6">
                        <label class="eq-form-label">Rig - From</label>
                        <input type="date" name="rig_from" class="form-control eq-form-control" value="{{ old('rig_from') }}">
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="eq-form-label">Rig - To</label>
                        <input type="date" name="rig_to" class="form-control eq-form-control" value="{{ old('rig_to') }}">
                    </div>

                    <!-- Prelight -->
                    <div class="col-md-3 col-6">
                        <label class="eq-form-label">Prelight - From</label>
                        <input type="date" name="prelight_from" class="form-control eq-form-control" value="{{ old('prelight_from') }}">
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="eq-form-label">Prelight - To</label>
                        <input type="date" name="prelight_to" class="form-control eq-form-control" value="{{ old('prelight_to') }}">
                    </div>

                    <!-- Shoot -->
                    <div class="col-md-3 col-6">
                        <label class="eq-form-label">Shoot - From</label>
                        <input type="date" name="shoot_from" class="form-control eq-form-control" value="{{ old('shoot_from') }}">
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="eq-form-label">Shoot - To</label>
                        <input type="date" name="shoot_to" class="form-control eq-form-control" value="{{ old('shoot_to') }}">
                    </div>

                    <!-- Derig -->
                    <div class="col-md-3 col-6">
                        <label class="eq-form-label">Derig - From</label>
                        <input type="date" name="derig_from" class="form-control eq-form-control" value="{{ old('derig_from') }}">
                    </div>
                    <div class="col-md-3 col-6">
                        <label class="eq-form-label">Derig - To</label>
                        <input type="date" name="derig_to" class="form-control eq-form-control" value="{{ old('derig_to') }}">
                    </div>
                </div>

                <!-- Location -->
                <div class="eq-section-subtitle">
                    <i class="bi bi-geo-alt-fill me-1"></i> Location Address
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="eq-form-label">Address Line 1</label>
                        <input type="text" name="address_line_1" class="form-control eq-form-control" placeholder="Building / Studio Name" value="{{ old('address_line_1') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="eq-form-label">Address Line 2</label>
                        <input type="text" name="address_line_2" class="form-control eq-form-control" placeholder="Street Name / Area" value="{{ old('address_line_2') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="eq-form-label">Address Line 3 / Postcode</label>
                        <input type="text" name="address_line_3_postcode" class="form-control eq-form-control" placeholder="City / Postcode (e.g. Dalston E9)" value="{{ old('address_line_3_postcode') }}">
                    </div>
                </div>

            </div>
        </div>

        <!-- SECTION 2: CATEGORY-WISE PRODUCT SELECTION -->
        <div class="eq-card">
            <div class="eq-card-header">
                <i class="bi bi-boxes"></i>
                <span>2. CATEGORY-WISE PRODUCT QUANTITY SELECTION</span>
            </div>
            <div class="eq-card-body">

                <!-- Category Tabs Navigation -->
                <div class="category-tabs-wrapper">
                    @foreach($categories as $index => $category)
                        <button type="button" 
                                class="cat-tab-btn {{ $index === 0 ? 'active' : '' }}" 
                                data-category-id="{{ $category->id }}"
                                onclick="switchCategoryTab({{ $category->id }})">
                            <span>{{ $category->name }}</span>
                            <span class="badge-qty-count" id="badge-cat-{{ $category->id }}" style="display:none;">0</span>
                        </button>
                    @endforeach
                </div>

                <!-- Category Product Lists (2-Column Grid with Fixed Height & Scrollbar) -->
                <div id="categoryProductsContainer">
                    @foreach($categories as $index => $category)
                        <div class="category-panel" id="cat-panel-{{ $category->id }}" style="{{ $index === 0 ? 'display: block;' : 'display: none;' }}">

                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">
                                    <i class="bi bi-folder2-open me-2 text-warning"></i>{{ $category->name }}
                                </h5>
                                <span class="text-muted small">{{ count($category->products) }} Products Available</span>
                            </div>

                            @if(count($category->products) > 0)
                                <div class="category-scroll-container">
                                    <div class="row row-cols-1 row-cols-md-2 g-2">
                                        @foreach($category->products as $pIndex => $product)
                                            <div class="col">
                                                <div class="product-item-card" id="card-product-{{ $product->id }}">
                                                    <div class="product-title-text me-auto">
                                                        {{ $product->title }}
                                                    </div>
                                                    <div class="qty-input-group">
                                                        <button type="button" class="qty-btn btn-minus" onclick="adjustQty({{ $product->id }}, {{ $category->id }}, -1)">-</button>
                                                        <input type="number" 
                                                               name="quantities[{{ $product->id }}]" 
                                                               id="qty-input-{{ $product->id }}" 
                                                               class="qty-number-input product-qty-field" 
                                                               data-product-id="{{ $product->id }}"
                                                               data-category-id="{{ $category->id }}"
                                                               data-category-name="{{ $category->name }}"
                                                               data-product-title="{{ $product->title }}"
                                                               min="0" 
                                                               value="{{ old('quantities.'.$product->id, 0) }}" 
                                                               oninput="onQtyChange({{ $product->id }}, {{ $category->id }})">
                                                        <button type="button" class="qty-btn btn-plus" onclick="adjustQty({{ $product->id }}, {{ $category->id }}, 1)">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-light text-center py-4 border">
                                    <i class="bi bi-info-circle text-muted fs-4 d-block mb-2"></i>
                                    <span class="text-muted">No products available in this category yet.</span>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>

            </div>
        </div>

        <!-- SECTION 3: FORM SUBMIT CARD (NON-STICKY AT BOTTOM) -->
        <div class="submit-card">
            <div class="row align-items-center g-3">
                <div class="col-md-7 text-center text-md-start">
                    <div class="submit-summary-box">
                        <i class="bi bi-cart-check me-2 text-warning fs-4"></i>
                        <span>Total Products Selected: <strong id="totalSelectedQty">0</strong> Items</span>
                    </div>
                    <small class="text-muted d-block mt-1" style="color: #aaa !important;">
                        Clicking submit will send your request via Email & WhatsApp.
                    </small>
                </div>
                <div class="col-md-5 text-center text-md-end">
                    <button type="submit" class="btn btn-brand btn-lg text-dark fw-bold px-5 py-3 rounded-3 w-100 w-md-auto" id="submitBtn">
                        <i class="bi bi-send-fill me-2"></i> SUBMIT FORM
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
    // Tab switching logic (preserves all form inputs across tabs)
    function switchCategoryTab(categoryId) {
        // Hide all category panels
        const panels = document.querySelectorAll('.category-panel');
        panels.forEach(panel => panel.style.display = 'none');

        // Show target panel
        const targetPanel = document.getElementById('cat-panel-' + categoryId);
        if (targetPanel) {
            targetPanel.style.display = 'block';
        }

        // Update active tab buttons
        const tabBtns = document.querySelectorAll('.cat-tab-btn');
        tabBtns.forEach(btn => {
            if (btn.getAttribute('data-category-id') == categoryId) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    // Adjust quantity with +/- buttons
    function adjustQty(productId, categoryId, delta) {
        const input = document.getElementById('qty-input-' + productId);
        if (!input) return;

        let val = parseInt(input.value) || 0;
        val += delta;
        if (val < 0) val = 0;
        input.value = val;

        onQtyChange(productId, categoryId);
    }

    // Handle quantity input change
    function onQtyChange(productId, categoryId) {
        const input = document.getElementById('qty-input-' + productId);
        if (input) {
            if (parseInt(input.value) < 0 || isNaN(parseInt(input.value))) {
                input.value = 0;
            }
        }
        updateTotals();
    }

    // Update overall totals & category badges & card highlighting
    function updateTotals() {
        const qtyInputs = document.querySelectorAll('.product-qty-field');
        let totalCount = 0;
        const categoryCounts = {};

        qtyInputs.forEach(input => {
            const val = parseInt(input.value) || 0;
            const productId = input.getAttribute('data-product-id');
            const card = document.getElementById('card-product-' + productId);

            if (val > 0) {
                totalCount += val;
                const catId = input.getAttribute('data-category-id');
                categoryCounts[catId] = (categoryCounts[catId] || 0) + val;
                if (card) card.classList.add('has-qty');
            } else {
                if (card) card.classList.remove('has-qty');
            }
        });

        // Update overall total badge
        const totalElem = document.getElementById('totalSelectedQty');
        if (totalElem) {
            totalElem.innerText = totalCount;
        }

        // Update category tab badges
        const tabBtns = document.querySelectorAll('.cat-tab-btn');
        tabBtns.forEach(btn => {
            const catId = btn.getAttribute('data-category-id');
            const badge = document.getElementById('badge-cat-' + catId);
            if (badge) {
                const count = categoryCounts[catId] || 0;
                if (count > 0) {
                    badge.innerText = count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        });
    }

    // Form Submit Validation & WhatsApp Trigger
    document.addEventListener('DOMContentLoaded', function () {
        updateTotals();

        const form = document.getElementById('equipmentRequestForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                let totalQty = 0;
                const qtyInputs = document.querySelectorAll('.product-qty-field');
                qtyInputs.forEach(input => {
                    totalQty += (parseInt(input.value) || 0);
                });

                if (totalQty <= 0) {
                    e.preventDefault();
                    alert('Please select at least one product with a valid quantity before submitting.');
                    return false;
                }

                // Construct WhatsApp Message
                const gaffer = (form.querySelector('[name="gaffer"]')?.value || '').trim();
                const email = (form.querySelector('[name="email"]')?.value || '').trim();
                const contact = (form.querySelector('[name="contact"]')?.value || '').trim();
                const prodCompany = (form.querySelector('[name="production_company"]')?.value || '').trim();
                const prodTitle = (form.querySelector('[name="production_title"]')?.value || '').trim();
                const prodContact = (form.querySelector('[name="production_contact"]')?.value || '').trim();
                const dop = (form.querySelector('[name="dop"]')?.value || '').trim();

                let msg = "*NEW EQUIPMENT REQUEST*\n\n";
                msg += "*Production Information:*\n";
                if (gaffer) msg += `• Gaffer: ${gaffer}\n`;
                if (email) msg += `• Email: ${email}\n`;
                if (contact) msg += `• Phone: ${contact}\n`;
                if (prodCompany) msg += `• Production Co.: ${prodCompany}\n`;
                if (prodTitle) msg += `• Production Title: ${prodTitle}\n`;
                if (prodContact) msg += `• Production Contact: ${prodContact}\n`;
                if (dop) msg += `• DoP: ${dop}\n`;

                const rigFrom = form.querySelector('[name="rig_from"]')?.value;
                const rigTo = form.querySelector('[name="rig_to"]')?.value;
                const prelightFrom = form.querySelector('[name="prelight_from"]')?.value;
                const prelightTo = form.querySelector('[name="prelight_to"]')?.value;
                const shootFrom = form.querySelector('[name="shoot_from"]')?.value;
                const shootTo = form.querySelector('[name="shoot_to"]')?.value;
                const derigFrom = form.querySelector('[name="derig_from"]')?.value;
                const derigTo = form.querySelector('[name="derig_to"]')?.value;

                let hasDates = false;
                let dateStr = "\n*Production Dates:*\n";
                if (rigFrom || rigTo) { dateStr += `• Rig: ${rigFrom} to ${rigTo}\n`; hasDates = true; }
                if (prelightFrom || prelightTo) { dateStr += `• Prelight: ${prelightFrom} to ${prelightTo}\n`; hasDates = true; }
                if (shootFrom || shootTo) { dateStr += `• Shoot: ${shootFrom} to ${shootTo}\n`; hasDates = true; }
                if (derigFrom || derigTo) { dateStr += `• Derig: ${derigFrom} to ${derigTo}\n`; hasDates = true; }
                if (hasDates) msg += dateStr;

                const addr1 = (form.querySelector('[name="address_line_1"]')?.value || '').trim();
                const addr2 = (form.querySelector('[name="address_line_2"]')?.value || '').trim();
                const addr3 = (form.querySelector('[name="address_line_3_postcode"]')?.value || '').trim();
                const fullAddr = [addr1, addr2, addr3].filter(Boolean).join(', ');
                if (fullAddr) {
                    msg += `\n*Location Address:*\n${fullAddr}\n`;
                }

                msg += `\n*Requested Equipment:*\n`;
                const selectedByCategory = {};

                qtyInputs.forEach(input => {
                    const qty = parseInt(input.value) || 0;
                    if (qty > 0) {
                        const catName = input.getAttribute('data-category-name') || 'Equipment';
                        const title = input.getAttribute('data-product-title') || 'Product';
                        if (!selectedByCategory[catName]) {
                            selectedByCategory[catName] = [];
                        }
                        selectedByCategory[catName].push({ title: title, qty: qty });
                    }
                });

                for (const catName in selectedByCategory) {
                    msg += `\n*${catName}*\n`;
                    selectedByCategory[catName].forEach(item => {
                        msg += `  - ${item.title} (Qty: ${item.qty})\n`;
                    });
                }

                // Open WhatsApp in new window
                window.open(
                    `https://wa.me/447879175585?text=${encodeURIComponent(msg)}`,
                    '_blank'
                );

                // Show spinner loading state
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Submitting...';
                }
            });
        }
    });
</script>
@endsection
