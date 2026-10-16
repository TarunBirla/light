@extends('layouts.admin')

@section('page-title', 'Edit Blog: ' . $blog->title)
@section('breadcrumb', 'Admin / Blogs / Edit')

@section('content')

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-pen-to-square me-2" style="color:#FFC700"></i>Edit Blog Post</h5>
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

        <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="fw-bold mb-1">Blog Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $blog->title) }}" class="form-control form-control-lg" required>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold mb-1">Author Name</label>
                    <input type="text" name="author" value="{{ old('author', $blog->author) }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="fw-bold mb-1">Featured Image</label>
                    @if($blog->image_url)
                        <div class="mb-2">
                            <img src="{{ $blog->image_url }}" class="rounded shadow-sm" width="120" height="80" style="object-fit:cover; border:1px solid #ddd;">
                            <span class="small text-muted d-block mt-1">Current Image</span>
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Upload a new image to replace current image</small>
                </div>

                <div class="col-md-6">
                    <label class="fw-bold mb-1">Publication Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status', $blog->status) == 'active' ? 'selected' : '' }}>Active (Published)</option>
                        <option value="inactive" {{ old('status', $blog->status) == 'inactive' ? 'selected' : '' }}>Inactive (Draft)</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="fw-bold mb-1">Short Summary / Excerpt</label>
                    <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $blog->short_description) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="fw-bold mb-1">Blog Content / Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="blogEditor" class="form-control" rows="12">{{ old('description', $blog->description) }}</textarea>
                </div>
            </div>

            <div class="mt-4 pt-2 border-top d-flex gap-2">
                <button type="submit" class="btn btn-warning px-4 text-dark fw-bold" style="background:#FFC700; border:none; border-radius:10px;">
                    <i class="fa-solid fa-save me-1"></i> Update Blog
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
