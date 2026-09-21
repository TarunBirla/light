@extends('layouts.admin')

@section('page-title', 'Add Auction Product')
@section('breadcrumb', 'Admin / Auction Products / Create')

@section('content')

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-gavel me-2" style="color:#FFC700"></i>Add Auction Product</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('auction-products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold mb-1">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold mb-1">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="Enter Auction Product Title" required>
                        @error('title')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="fw-semibold mb-1">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold mb-1">Qty <span class="text-danger">*</span></label>
                        <input type="number" name="qty" value="{{ old('qty', 1) }}" class="form-control @error('qty') is-invalid @enderror" required>
                        @error('qty')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold mb-1">Min Price (£) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="minprice" value="{{ old('minprice') }}" class="form-control @error('minprice') is-invalid @enderror" placeholder="0.00" required>
                        @error('minprice')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="fw-semibold mb-2">Shipping Cost Option <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-4 align-items-center border p-3 rounded">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shipping_type" id="shipping_excluded" value="excluded" {{ old('shipping_type', 'excluded') == 'excluded' ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="shipping_excluded">
                                    Excluded Shipping cost
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shipping_type" id="shipping_included" value="included" {{ old('shipping_type') == 'included' ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="shipping_included">
                                    Included Shipping cost
                                </label>
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold mb-1">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold mb-1">Images</label>
                        <input type="file" name="image[]" multiple class="form-control" accept="image/*">
                        <small class="text-muted">You can select multiple images.</small>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning px-4 text-dark fw-bold me-2" style="background:#FFC700; border:none;">
                        <i class="fa-solid fa-save me-1"></i> Save Auction Product
                    </button>
                    <a href="{{ route('auction-products.index') }}" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            ClassicEditor
                .create(document.querySelector('#description'))
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@endsection
