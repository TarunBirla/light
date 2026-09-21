@extends('layouts.admin')

@section('page-title', 'Edit Auction Product')
@section('breadcrumb', 'Admin / Auction Products / Edit')

@section('content')

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-gavel me-2" style="color:#FFC700"></i>Edit Auction Product</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('auction-products.update', $auctionProduct->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold mb-1">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $auctionProduct->category_id) == $category->id ? 'selected' : '' }}>
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
                        <input type="text" name="title" value="{{ old('title', $auctionProduct->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="fw-semibold mb-1">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $auctionProduct->description) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold mb-1">Qty <span class="text-danger">*</span></label>
                        <input type="number" name="qty" value="{{ old('qty', $auctionProduct->qty) }}" class="form-control @error('qty') is-invalid @enderror" required>
                        @error('qty')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="fw-semibold mb-1">Min Price (£) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="minprice" value="{{ old('minprice', $auctionProduct->minprice) }}" class="form-control @error('minprice') is-invalid @enderror" placeholder="0.00" required>
                        @error('minprice')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="fw-semibold mb-2">Shipping Cost Option <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-4 align-items-center border p-3 rounded">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shipping_type" id="shipping_excluded" value="excluded" {{ old('shipping_type', $auctionProduct->shipping_type ?? 'excluded') == 'excluded' ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="shipping_excluded">
                                    Excluded Shipping cost
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="shipping_type" id="shipping_included" value="included" {{ old('shipping_type', $auctionProduct->shipping_type) == 'included' ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="shipping_included">
                                    Included Shipping cost
                                </label>
                            </div>
                            
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold mb-1">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $auctionProduct->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $auctionProduct->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-semibold mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $auctionProduct->sort_order) }}" class="form-control">
                    </div>

                    @php
                        $images = [];
                        if ($auctionProduct->image) {
                            if (is_array($auctionProduct->image)) {
                                $images = $auctionProduct->image;
                            } else {
                                $decoded = json_decode($auctionProduct->image, true);
                                $images = is_array($decoded) ? $decoded : [$auctionProduct->image];
                            }
                        }
                    @endphp

                    <div class="col-md-12 mb-3">
                        <label class="fw-semibold mb-2">Existing Images</label>
                        <div class="row">
                            @foreach($images as $index => $img)
                                <div class="col-md-2 col-6 mb-3 image-box">
                                    <div style="position:relative">
                                        <img src="{{ asset('uploads/auction_products/' . $img) }}" class="img-fluid border rounded" style="height:120px;width:100%;object-fit:cover;">
                                        <button type="button" class="btn btn-danger btn-sm remove-image" data-index="{{ $index }}"
                                            style="position:absolute; top:5px; right:5px; border-radius:50%; width:28px; height:28px; padding:0; line-height: 1;">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <input type="hidden" name="deleted_images" id="deleted_images">

                    <div class="col-md-12 mb-3">
                        <label class="fw-semibold mb-1">Add More Images</label>
                        <input type="file" name="image[]" multiple class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning px-4 text-dark fw-bold me-2" style="background:#FFC700; border:none;">
                        <i class="fa-solid fa-sync me-1"></i> Update Auction Product
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

        let deletedImages = [];
        document.querySelectorAll('.remove-image').forEach(btn => {
            btn.addEventListener('click', function () {
                let index = this.dataset.index;
                deletedImages.push(index);
                document.getElementById('deleted_images').value = JSON.stringify(deletedImages);
                this.closest('.image-box').remove();
            });
        });
    </script>
@endsection
