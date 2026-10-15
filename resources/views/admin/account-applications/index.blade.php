@extends('layouts.admin')

@section('page-title', 'Account Opening Applications')
@section('breadcrumb', 'Admin / Account Applications')

@section('content')

<style>
    .status-badge {
        font-size: 12px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-pending { background: #FFF3B0; color: #B38A00; }
    .status-under_review { background: #EAF3FF; color: #1a5fb4; }
    .status-approved { background: #EDFAF0; color: #1a7a3a; }
    .status-rejected { background: #FEF0F0; color: #c0392b; }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-file-signature me-2" style="color:#FFC700"></i>Account Opening Applications</h3>
        <p class="text-muted small mb-0">Manage customer & company trade account opening requests and verification.</p>
    </div>
</div>

<!-- Filter Pills & Search -->
<div class="row align-items-center g-3 mb-4">
    <div class="col-lg-7">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.account-applications.index') }}" class="btn btn-sm {{ !$status ? 'btn-dark' : 'btn-light border' }}">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.account-applications.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status == 'pending' ? 'btn-warning text-dark fw-bold' : 'btn-light border' }}">
                Pending ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.account-applications.index', ['status' => 'under_review']) }}" class="btn btn-sm {{ $status == 'under_review' ? 'btn-primary' : 'btn-light border' }}">
                Under Review ({{ $counts['under_review'] }})
            </a>
            <a href="{{ route('admin.account-applications.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $status == 'approved' ? 'btn-success' : 'btn-light border' }}">
                Approved ({{ $counts['approved'] }})
            </a>
            <a href="{{ route('admin.account-applications.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status == 'rejected' ? 'btn-danger' : 'btn-light border' }}">
                Rejected ({{ $counts['rejected'] }})
            </a>
        </div>
    </div>
    <div class="col-lg-5 text-lg-end">
        <form method="GET" action="{{ route('admin.account-applications.index') }}" class="d-flex gap-2">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="text" name="search" value="{{ $search }}" placeholder="Search company, name, email..." class="form-control form-control-sm">
            <button type="submit" class="btn btn-sm btn-dark"><i class="fa-solid fa-search"></i></button>
            @if($search)
                <a href="{{ route('admin.account-applications.index', ['status' => $status]) }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="table-card p-3">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#ID</th>
                    <th>Company / Legal Name</th>
                    <th>Contact Person</th>
                    <th>Business Type</th>
                    <th>Contact Info</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td><span class="badge bg-light text-dark border">#{{ $app->id }}</span></td>
                        <td>
                            <strong class="d-block text-dark">{{ $app->full_legal_name }}</strong>
                            @if($app->trading_name)
                                <small class="text-muted">Trading: {{ $app->trading_name }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $app->contact_person_name }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $app->business_type ?: 'N/A' }}</span>
                        </td>
                        <td>
                            <div class="small">
                                <a href="mailto:{{ $app->email }}" class="text-decoration-none text-dark d-block">
                                    <i class="fa-solid fa-envelope text-warning me-1"></i>{{ $app->email }}
                                </a>
                                <a href="tel:{{ $app->telephone }}" class="text-decoration-none text-muted">
                                    <i class="fa-solid fa-phone text-warning me-1"></i>{{ $app->telephone }}
                                </a>
                            </div>
                        </td>
                        <td>
                            <small class="text-muted">{{ $app->created_at ? $app->created_at->format('d M Y, h:i A') : '-' }}</small>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $app->status }}">
                                @if($app->status === 'approved')
                                    <i class="fa-solid fa-circle-check"></i> Approved
                                @elseif($app->status === 'under_review')
                                    <i class="fa-solid fa-clock"></i> Under Review
                                @elseif($app->status === 'rejected')
                                    <i class="fa-solid fa-circle-xmark"></i> Rejected
                                @else
                                    <i class="fa-solid fa-hourglass-start"></i> Pending
                                @endif
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.account-applications.show', $app->id) }}" class="btn btn-sm btn-dark me-1" title="View Full Application">
                                <i class="fa-solid fa-eye me-1"></i> View
                            </a>
                            <form action="{{ route('admin.account-applications.destroy', $app->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this application?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fa-regular fa-folder-open fs-2 d-block mb-2"></i>
                            No account applications found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $applications->links() }}
    </div>
</div>

@endsection
