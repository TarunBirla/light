@extends('layouts.admin')

@section('page-title', 'Add Auction Step')
@section('breadcrumb', 'Admin / Auction Steps / Create')

@section('content')

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-list-ol me-2" style="color:#FFC700"></i>Add Auction Participation Step</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.steps.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="fw-semibold mb-1">Step Number <span class="text-danger">*</span></label>
                        <input type="number" name="step_number" value="{{ old('step_number', 1) }}" class="form-control" required>
                    </div>

                    <div class="col-md-9">
                        <label class="fw-semibold mb-1">Step Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="e.g. Register your interest" required>
                    </div>

                    <div class="col-md-12">
                        <label class="fw-semibold mb-1">Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Enter step description..." required>{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 1) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="fw-semibold mb-1">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning px-4 text-dark fw-bold me-2" style="background:#FFC700; border:none;">
                        <i class="fa-solid fa-save me-1"></i> Save Step
                    </button>
                    <a href="{{ route('admin.steps.index') }}" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>

@endsection
