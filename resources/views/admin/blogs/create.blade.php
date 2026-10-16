@extends('layouts.admin')

@section('page-title', 'Add New Blog')
@section('breadcrumb', 'Admin / Blogs / Create')

@section('content')

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-plus-circle me-2" style="color:#FFC700"></i>Create New Blog Post</h5>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Blogs
        </a>
    </div>

    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger rounded-3 mb-4">
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="fw-bold mb-1">Blog Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control form-control-lg" placeholder="Enter an engaging blog title..." required>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold mb-1">Author Name</label>
                    <input type="text" name="author" value="{{ old('author', 'Light As Air Team') }}" class="form-control" placeholder="Author name">
                </div>

                <div class="col-md-6">
                    <label class="fw-bold mb-1">Featured Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Recommended size: 1200x630px (JPG, PNG, WEBP)</small>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold mb-1">Publication Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Publish Immediately)</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (Draft)</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="fw-bold mb-1">Short Summary / Excerpt</label>
                    <textarea name="short_description" class="form-control" rows="2" placeholder="Brief summary of the article for blog cards (optional)...">{{ old('short_description') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="fw-bold mb-1">Blog Content / Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="blogEditor" class="form-control" rows="12" placeholder="Write your full blog post here...">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-4 pt-2 border-top d-flex gap-2">
                <button type="submit" class="btn btn-warning px-4 text-dark fw-bold" style="background:#FFC700; border:none; border-radius:10px;">
                    <i class="fa-solid fa-cloud-arrow-up me-1"></i> Publish Blog
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-light px-4 border rounded-3">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(document.querySelector('#blogEditor'), {
                    toolbar: [
                        'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                        'insertTable', '|', 'undo', 'redo'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });
        }
    });
</script>

@endsection
