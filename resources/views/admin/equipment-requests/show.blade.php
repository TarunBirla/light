@extends('layouts.admin')

@section('page-title', 'Equipment Request #' . $equipmentRequest->id)
@section('breadcrumb', 'Admin / Equipment Requests / Request #' . $equipmentRequest->id)

@section('content')
<style>
    .info-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #E8E6DF;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .info-card-header {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111;
        padding-bottom: 12px;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--brand);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #777;
        margin-bottom: 2px;
    }
    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #111;
    }
    .category-group-card {
        background: #fff;
        border: 1px solid #E8E6DF;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .category-group-header {
        background: #111;
        color: #fff;
        padding: 12px 20px;
        font-weight: 700;
        font-size: 1.05rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .category-group-header span.cat-title {
        color: var(--brand);
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.equipment-requests.index') }}" class="btn btn-outline-dark btn-sm mb-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Requests List
        </a>
        <h4 class="fw-bold mb-0">Request Details #{{ $equipmentRequest->id }}</h4>
        <span class="text-muted small">Submitted on {{ $equipmentRequest->created_at ? $equipmentRequest->created_at->format('d M Y, h:i A') : 'N/A' }}</span>
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-dark btn-sm">
            <i class="fa-solid fa-print me-1"></i> Print Request
        </button>
    </div>
</div>

<!-- 1. PERMANENT PRODUCTION INFORMATION -->
<div class="info-card">
    <div class="info-card-header">
        <i class="fa-solid fa-film text-warning"></i> Production Information
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="info-label">Gaffer</div>
            <div class="info-value">{{ $equipmentRequest->gaffer ?: 'N/A' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $equipmentRequest->email ?: 'N/A' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Contact Phone</div>
            <div class="info-value">{{ $equipmentRequest->contact ?: 'N/A' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Production Company</div>
            <div class="info-value">{{ $equipmentRequest->production_company ?: 'N/A' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Production Title</div>
            <div class="info-value text-primary fs-5">{{ $equipmentRequest->production_title ?: 'N/A' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Production Contact</div>
            <div class="info-value">{{ $equipmentRequest->production_contact ?: 'N/A' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Director of Photography (DoP)</div>
            <div class="info-value">{{ $equipmentRequest->dop ?: 'N/A' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label">User Account</div>
            <div class="info-value">
                @if($equipmentRequest->user)
                    {{ $equipmentRequest->user->name }} ({{ $equipmentRequest->user->email }})
                @else
                    <span class="text-muted">Guest Submission</span>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Status</div>
            <div class="info-value">
                <span class="badge bg-success">{{ ucfirst($equipmentRequest->status) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- 2. PRODUCTION DATES -->
<div class="info-card">
    <div class="info-card-header">
        <i class="fa-solid fa-calendar-days text-warning"></i> Production Dates
    </div>
    <div class="row g-4">
        <!-- Rig -->
        <div class="col-md-3 col-6">
            <div class="info-label">Rig - From</div>
            <div class="info-value">{{ $equipmentRequest->rig_from ? \Carbon\Carbon::parse($equipmentRequest->rig_from)->format('d/m/Y') : 'N/A' }}</div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-label">Rig - To</div>
            <div class="info-value">{{ $equipmentRequest->rig_to ? \Carbon\Carbon::parse($equipmentRequest->rig_to)->format('d/m/Y') : 'N/A' }}</div>
        </div>

        <!-- Prelight -->
        <div class="col-md-3 col-6">
            <div class="info-label">Prelight - From</div>
            <div class="info-value">{{ $equipmentRequest->prelight_from ? \Carbon\Carbon::parse($equipmentRequest->prelight_from)->format('d/m/Y') : 'N/A' }}</div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-label">Prelight - To</div>
            <div class="info-value">{{ $equipmentRequest->prelight_to ? \Carbon\Carbon::parse($equipmentRequest->prelight_to)->format('d/m/Y') : 'N/A' }}</div>
        </div>

        <!-- Shoot -->
        <div class="col-md-3 col-6">
            <div class="info-label">Shoot - From</div>
            <div class="info-value text-success">{{ $equipmentRequest->shoot_from ? \Carbon\Carbon::parse($equipmentRequest->shoot_from)->format('d/m/Y') : 'N/A' }}</div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-label">Shoot - To</div>
            <div class="info-value text-success">{{ $equipmentRequest->shoot_to ? \Carbon\Carbon::parse($equipmentRequest->shoot_to)->format('d/m/Y') : 'N/A' }}</div>
        </div>

        <!-- Derig -->
        <div class="col-md-3 col-6">
            <div class="info-label">Derig - From</div>
            <div class="info-value">{{ $equipmentRequest->derig_from ? \Carbon\Carbon::parse($equipmentRequest->derig_from)->format('d/m/Y') : 'N/A' }}</div>
        </div>
        <div class="col-md-3 col-6">
            <div class="info-label">Derig - To</div>
            <div class="info-value">{{ $equipmentRequest->derig_to ? \Carbon\Carbon::parse($equipmentRequest->derig_to)->format('d/m/Y') : 'N/A' }}</div>
        </div>
    </div>
</div>

<!-- 3. LOCATION ADDRESS -->
<div class="info-card">
    <div class="info-card-header">
        <i class="fa-solid fa-location-dot text-warning"></i> Location Address
    </div>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="info-label">Address Line 1</div>
            <div class="info-value">{{ $equipmentRequest->address_line_1 ?: 'N/A' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Address Line 2</div>
            <div class="info-value">{{ $equipmentRequest->address_line_2 ?: 'N/A' }}</div>
        </div>
        <div class="col-md-4">
            <div class="info-label">Address Line 3 / Postcode</div>
            <div class="info-value">{{ $equipmentRequest->address_line_3_postcode ?: 'N/A' }}</div>
        </div>
        <div class="col-12 mt-2">
            <div class="info-label">Full Combined Address</div>
            <div class="info-value text-dark p-3 bg-light rounded border">{{ $equipmentRequest->location_address ?: 'N/A' }}</div>
        </div>
    </div>
</div>

<!-- 4. CATEGORY-WISE REQUESTED PRODUCTS -->
<h4 class="fw-bold mt-5 mb-3"><i class="fa-solid fa-boxes-packing me-2 text-warning"></i> Requested Products & Quantities</h4>

@forelse($groupedItems as $categoryName => $items)
    <div class="category-group-card">
        <div class="category-group-header">
            <span><i class="fa-solid fa-folder-open me-2"></i> <span class="cat-title">{{ $categoryName }}</span></span>
            <span class="badge bg-warning text-dark font-monospace fs-6">{{ $items->sum('quantity') }} Items</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">#</th>
                        <th>Product Title</th>
                        <th style="width: 150px;" class="text-end">Quantity Requested</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                        <tr>
                            <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->product ? $item->product->title : 'Deleted Product ID #' . $item->product_id }}</div>
                                @if($item->product && $item->product->description)
                                    <div class="text-muted small">{!! Str::limit(strip_tags($item->product->description), 120) !!}</div>
                                @endif
                            </td>
                            <td class="text-end">
                                <span class="badge bg-dark fs-6 px-3 py-2" style="border-radius: 8px;">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="alert alert-warning text-center py-4">
        No equipment items recorded for this request.
    </div>
@endforelse

@endsection
