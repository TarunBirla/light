@extends('layouts.admin')

@section('content')

    <div class="card">

        <div class="card-header">
            Add Item
        </div>

        <div class="card-body">

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label>Category</label>

                        <select name="category_id" class="form-control">

                            <option value="">
                                Select Category
                            </option>

                            @foreach($categories as $category)

                                <option value="{{ $category->id }}">

                                    {{ $category->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label>Title</label>

                        <input type="text" name="title" class="form-control">

                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Description</label>

                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Qty</label>

                        <input type="number" name="qty" class="form-control">

                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="d-block fw-bold">Product Type <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4 align-items-center mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_sell" id="is_sell" value="1" {{ old('is_sell', '1') == '1' ? 'checked' : '' }} onchange="togglePriceFields()">
                                <label class="form-check-label fw-semibold" for="is_sell">
                                     Sell
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_rental" id="is_rental" value="1" {{ old('is_rental', '1') == '1' ? 'checked' : '' }} onchange="togglePriceFields()">
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
                        <input type="number" step="0.01" name="selling_price" id="selling_price" value="{{ old('selling_price') }}" class="form-control" placeholder="0.00">
                        @error('selling_price')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3" id="rental_price_wrapper">
                        <label class="fw-bold">Rental Price / Day (£) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="rental_price" id="rental_price" value="{{ old('rental_price') }}" class="form-control" placeholder="0.00">
                        @error('rental_price')
                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">

                        <label>Status</label>

                        <select name="status" class="form-control">

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>

                    </div>
                    <div class="col-md-3 mb-3">

                        <label>Sort Order</label>

                        <input type="number" name="sort_order" value="0" class="form-control">

                    </div>

                    <div class="col-md-12 mb-3">

                        <label>Image</label>

                        <input type="file" name="image[]" multiple class="form-control">

                    </div>

                </div>

                <button class="btn btn-success">

                    Save Item

                </button>

            </form>
            <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

            <script>
                function togglePriceFields() {
                    const isSell = document.getElementById('is_sell').checked;
                    const isRental = document.getElementById('is_rental').checked;

                    const sellWrap = document.getElementById('selling_price_wrapper');
                    const rentWrap = document.getElementById('rental_price_wrapper');

                    if (sellWrap) {
                        sellWrap.style.display = isSell ? 'block' : 'none';
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

        </div>

    </div>

@endsection