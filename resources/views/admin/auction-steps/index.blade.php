@extends('layouts.admin')

@section('page-title', 'Auction Participation Steps')
@section('breadcrumb', 'Admin / Auction Steps')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fa-solid fa-list-ol me-2" style="color:#FFC700"></i>Auction Steps</h3>
        <a href="{{ route('admin.steps.create') }}" class="btn btn-warning fw-bold text-dark" style="background:#FFC700; border:none;">
            <i class="fa-solid fa-plus me-1"></i> Add New Step
        </a>
    </div>

    <div class="table-card">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Step #</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($steps as $step)
                    <tr>
                        <td><span class="badge bg-dark text-warning fs-6">#{{ $step->step_number }}</span></td>
                        <td><strong>{{ $step->title }}</strong></td>
                        <td style="max-width:350px; word-break:break-word;">
                            <small class="text-secondary">{{ Str::limit($step->description, 100) }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $step->sort_order }}</span></td>
                        <td>
                            @if($step->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.steps.edit', $step->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <form action="{{ route('admin.steps.destroy', $step->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this step?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No auction steps found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
