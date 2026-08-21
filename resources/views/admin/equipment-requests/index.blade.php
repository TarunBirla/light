@extends('layouts.admin')

@section('page-title', 'Equipment Requests')
@section('breadcrumb', 'Admin / Equipment Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Equipment Requests</h4>
        <p class="text-muted small mb-0">View and manage all submitted production equipment request forms.</p>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 80px;">Req ID</th>
                    <th>Production Title</th>
                    <th>Gaffer</th>
                    <th>Email / Contact</th>
                    <th>Shoot Dates</th>
                    <th>Total Items</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                    <tr>
                        <td class="fw-bold">#{{ $request->id }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $request->production_title ?? 'N/A' }}</div>
                            @if($request->production_company)
                                <div class="text-muted small">{{ $request->production_company }}</div>
                            @endif
                        </td>
                        <td>{{ $request->gaffer ?? 'N/A' }}</td>
                        <td>
                            @if($request->email)
                                <div><i class="fa-regular fa-envelope me-1 text-muted"></i>{{ $request->email }}</div>
                            @endif
                            @if($request->contact)
                                <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i>{{ $request->contact }}</div>
                            @endif
                            @if(!$request->email && !$request->contact)
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($request->shoot_from || $request->shoot_to)
                                <span class="badge bg-light text-dark border">
                                    {{ $request->shoot_from ? \Carbon\Carbon::parse($request->shoot_from)->format('d/m/Y') : '-' }} 
                                    to 
                                    {{ $request->shoot_to ? \Carbon\Carbon::parse($request->shoot_to)->format('d/m/Y') : '-' }}
                                </span>
                            @else
                                <span class="text-muted small">Not specified</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="border-radius: 6px;">
                                {{ $request->items_count ?? $request->items()->count() }} items
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success text-white px-2 py-1" style="border-radius: 6px;">
                                {{ ucfirst($request->status ?? 'Submitted') }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $request->created_at ? $request->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.equipment-requests.show', $request->id) }}" class="btn btn-sm btn-dark me-1" title="View Full Request">
                                <i class="fa-solid fa-eye me-1"></i> View
                            </a>
                            <form action="{{ route('admin.equipment-requests.destroy', $request->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this request?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Request">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-clipboard-list fs-1 d-block mb-3 text-secondary"></i>
                            No equipment requests submitted yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $requests->links() }}
</div>
@endsection
