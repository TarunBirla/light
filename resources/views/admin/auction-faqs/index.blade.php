@extends('layouts.admin')

@section('page-title', 'Auction FAQs')
@section('breadcrumb', 'Admin / Auction FAQs')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fa-solid fa-circle-question me-2" style="color:#FFC700"></i>Auction FAQs</h3>
        <a href="{{ route('admin.auction-faqs.create') }}" class="btn btn-warning fw-bold text-dark" style="background:#FFC700; border:none;">
            <i class="fa-solid fa-plus me-1"></i> Add New FAQ
        </a>
    </div>

    <div class="table-card">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Sort Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($faqs as $faq)
                    <tr>
                        <td><span class="badge bg-light text-dark border">{{ $faq->id }}</span></td>
                        <td style="max-width:280px; word-break:break-word;">
                            <strong>{{ $faq->question }}</strong>
                        </td>
                        <td style="max-width:380px; word-break:break-word;">
                            <small class="text-secondary">{{ Str::limit($faq->answer, 120) }}</small>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $faq->sort_order }}</span></td>
                        <td>
                            @if($faq->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.auction-faqs.edit', $faq->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="fa-solid fa-pen"></i> Edit
                            </a>
                            <form action="{{ route('admin.auction-faqs.destroy', $faq->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this FAQ?')">
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
                        <td colspan="6" class="text-center py-4 text-muted">No FAQs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
