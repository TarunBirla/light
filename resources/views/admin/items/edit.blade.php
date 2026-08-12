@extends('layouts.admin')

@section('content')

    <div class="card">

        <div class="card-header">
            Edit Item
        </div>

        <div class="card-body">

            <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Category</label>

                        <select name="category_id" class="form-control">

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Title</label>

                        <input type="text" name="title" value="{{ $item->title }}" class="form-control">

                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description</label>

                        <textarea name="description" id="description"
                            class="form-control">{{ $item->description }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Total Qty</label>

                        <input type="number" name="qty" value="{{ $item->qty }}" class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Available Qty</label>

                        <input type="number" name="available_qty" value="{{ $item->available_qty }}" class="form-control">

                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="d-block fw-bold">Product Type <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4 align-items-center mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_sell" id="is_sell" value="1" {{ old('is_sell', $item->is_sell) ? 'checked' : '' }} onchange="togglePriceFields()">
                                <label class="form-check-label fw-semibold" for="is_sell">
                                     Sell
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_rental" id="is_rental" value="1" {{ old('is_rental', $item->is_rental) ? 'checked' : '' }} onchange="togglePriceFields()">
                                <label class="form-check-label fw-semibold" for="is_rental">
                                     Rental
                                </label>
                            </div>
                        </div>
                        @error('product_type')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3" id="selling_price_wrapper">
                        <label class="fw-bold">Selling Price (£) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="selling_price" id="selling_price" value="{{ old('selling_price', $item->selling_price) }}" class="form-control" placeholder="0.00">
                        @error('selling_price')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3" id="condition_wrapper">
                        <label class="fw-bold">Item Condition <span class="text-danger">*</span></label>
                        <select name="condition" id="condition" class="form-control">
                            <option value="">Select Condition</option>
                            <option value="new" {{ old('condition', $item->condition) == 'new' ? 'selected' : '' }}>New</option>
                            <option value="used" {{ old('condition', $item->condition) == 'used' ? 'selected' : '' }}>Used</option>
                        </select>
                        @error('condition')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3" id="rental_price_wrapper">
                        <label class="fw-bold">Rental Price / Day (£) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="rental_price" id="rental_price" value="{{ old('rental_price', $item->rental_price ?? $item->price_per_day) }}" class="form-control" placeholder="0.00">
                        @error('rental_price')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Status</label>

                        <select name="status" class="form-control">

                            <option value="active" {{ $item->status == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive" {{ $item->status == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

    <label>Sort Order</label>

    <input
        type="number"
        name="sort_order"
        value="{{ $item->sort_order }}"
        class="form-control">

</div>

                    @php

                        $images = [];

                        if ($item->image) {

                            if (is_array($item->image)) {

                                $images = $item->image;

                            } else {

                                $decoded = json_decode($item->image, true);

                                if (is_array($decoded)) {
                                    $images = $decoded;
                                } else {
                                    $images = [$item->image];
                                }
                            }
                        }

                    @endphp

                    <div class="row">

                        @foreach($images as $index => $img)

                            <div class="col-md-2 mb-3 image-box">

                                <div style="position:relative">

                                    <img src="{{ asset('uploads/items/' . $img) }}" class="img-fluid border rounded"
                                        style="height:120px;width:100%;object-fit:cover;">

                                    <button type="button" class="btn btn-danger btn-sm remove-image" data-index="{{ $index }}"
                                        style="
                                    position:absolute;
                                    top:5px;
                                    right:5px;
                                    border-radius:50%;
                                    width:28px;
                                    height:28px;
                                    padding:0;">
                                        ×
                                    </button>

                                </div>

                            </div>

                        @endforeach

                    </div>

                    <input type="hidden" name="deleted_images" id="deleted_images">

                    <div class="col-md-12 mb-3">

                        <label>Add More Images</label>

                        <input type="file" name="image[]" multiple class="form-control">

                    </div>

                </div>

                <button class="btn btn-primary">

                    Update Item

                </button>

            </form>
            <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

            <script>
                function togglePriceFields() {
                    const isSell = document.getElementById('is_sell').checked;
                    const isRental = document.getElementById('is_rental').checked;

                    const sellWrap = document.getElementById('selling_price_wrapper');
                    const condWrap = document.getElementById('condition_wrapper');
                    const rentWrap = document.getElementById('rental_price_wrapper');

                    if (sellWrap) {
                        sellWrap.style.display = isSell ? 'block' : 'none';
                    }
                    if (condWrap) {
                        condWrap.style.display = isSell ? 'block' : 'none';
                    }
                    if (rentWrap) {
                        rentWrap.style.display = isRental ? 'block' : 'none';
                    }
                }

                document.addEventListener('DOMContentLoaded', function () {
                    togglePriceFields();
                    ClassicEditor
                        .create(document.querySelector('#description'))
                        .catch(error => {
                            console.error(error);
                        });
                });
            </script>
            <script>

                let deletedImages = [];

                document.querySelectorAll('.remove-image').forEach(btn => {

                    btn.addEventListener('click', function () {

                        let index = this.dataset.index;

                        deletedImages.push(index);

                        document.getElementById('deleted_images').value =
                            JSON.stringify(deletedImages);

                        this.closest('.image-box').remove();

                    });

                });

            </script>

        </div>

    </div>

@endsection