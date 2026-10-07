@extends('layouts.admin')

@section('page-title', 'Edit Auction FAQ')
@section('breadcrumb', 'Admin / Auction FAQs / Edit')

@section('content')

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-circle-question me-2" style="color:#FFC700"></i>Edit Auction FAQ</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.auction-faqs.update', $faq->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="fw-semibold mb-1">Question <span class="text-danger">*</span></label>
                        <input type="text" name="question" value="{{ old('question', $faq->question) }}" class="form-control" required>
                    </div>

                    <div class="col-md-12">
                        <label class="fw-semibold mb-1">Answer <span class="text-danger">*</span></label>
                        <textarea name="answer" class="form-control" rows="4" required>{{ old('answer', $faq->answer) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $faq->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $faq->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning px-4 text-dark fw-bold me-2" style="background:#FFC700; border:none;">
                        <i class="fa-solid fa-sync me-1"></i> Update FAQ
                    </button>
                    <a href="{{ route('admin.auction-faqs.index') }}" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>

@endsection
