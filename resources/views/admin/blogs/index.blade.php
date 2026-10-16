@extends('layouts.admin')

@section('page-title', 'Blogs Management')
@section('breadcrumb', 'Admin / Blogs')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-newspaper me-2" style="color:#FFC700"></i>Blogs Management</h3>
        <p class="text-muted small mb-0">Create, edit, manage, and publish blog articles & news updates.</p>
    </div>
    <div>
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-warning fw-bold text-dark" style="background:#FFC700; border:none; padding:9px 20px; border-radius:10px;">
            <i class="fa-solid fa-plus me-1"></i> Add New Blog
        </a>
    </div>
</div>

<!-- Filter Tabs & Search -->
<div class="row align-items-center g-3 mb-4">
    <div class="col-lg-6">
        <div class="d-flex gap-2">
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm {{ !$status ? 'btn-dark' : 'btn-light border' }}">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.blogs.index', ['status' => 'active']) }}" class="btn btn-sm {{ $status == 'active' ? 'btn-success text-white fw-bold' : 'btn-light border' }}">
                Active ({{ $counts['active'] }})
            </a>
            <a href="{{ route('admin.blogs.index', ['status' => 'inactive']) }}" class="btn btn-sm {{ $status == 'inactive' ? 'btn-secondary text-white' : 'btn-light border' }}">
                Inactive ({{ $counts['inactive'] }})
            </a>
        </div>
    </div>
    <div class="col-lg-6 text-lg-end">
        <form method="GET" action="{{ route('admin.blogs.index') }}" class="d-flex gap-2">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input type="text" name="search" value="{{ $search }}" placeholder="Search blogs by title or author..." class="form-control form-control-sm">
            <button type="submit" class="btn btn-sm btn-dark"><i class="fa-solid fa-search"></i></button>
            @if($search)
                <a href="{{ route('admin.blogs.index', ['status' => $status]) }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Table Card -->
<div class="table-card p-3">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 80px;">Image</th>
                    <th>Title & Slug</th>
                    <th>Author</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                    <tr>
                        <td>
                            @if($blog->image_url)
                                <img src="{{ $blog->image_url }}" class="rounded" width="60" height="50" style="object-fit:cover; border:1px solid #ddd;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width:60px; height:50px; border:1px solid #eee;">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td style="max-width: 320px;">
                            <strong class="d-block text-dark text-truncate">{{ $blog->title }}</strong>
                            <small class="text-muted"><i class="fa-solid fa-link me-1"></i>/blog/{{ $blog->slug }}</small>
                        </td>
                        <td>
                            <span class="small fw-semibold text-secondary">{{ $blog->author }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border"><i class="fa-regular fa-eye me-1"></i>{{ number_format($blog->views) }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.blogs.status', $blog->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm fw-bold border-0 shadow-sm badge {{ $blog->status === 'active' ? 'bg-success text-white' : 'bg-secondary text-white' }}" onchange="this.form.submit()" style="cursor:pointer; width:100px;">
                                    <option value="active" class="bg-white text-dark" {{ $blog->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" class="bg-white text-dark" {{ $blog->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <small class="text-muted">{{ $blog->created_at ? $blog->created_at->format('d M Y') : '-' }}</small>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('front.blogs.show', $blog->slug) }}" target="_blank" class="btn btn-sm btn-outline-dark me-1" title="View Blog on Website">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-warning text-dark me-1" title="Edit Blog">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this blog?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fa-regular fa-newspaper fs-2 d-block mb-2 text-secondary"></i>
                            No blogs found. Click <strong>"Add New Blog"</strong> to publish your first article!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $blogs->links() }}
    </div>
</div>

@endsection
