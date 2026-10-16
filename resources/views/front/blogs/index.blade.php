@extends('front.layouts.app')

@section('title', 'Blogs & Insights – Light As Air')
@section('meta_description', 'Explore the latest articles, cinematic lighting guides, gear reviews, and equipment rental insights from Light As Air UK.')

@section('content')

<style>
    .blogs-hero {
        background: linear-gradient(135deg, #111111 0%, #1e1e1e 100%);
        color: #ffffff;
        padding: 55px 0 45px;
        position: relative;
        border-bottom: 3px solid var(--brand, #FFC700);
    }
    .blogs-hero h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
    }
    .blogs-hero h1 span {
        color: var(--brand, #FFC700);
    }
    .blogs-hero p {
        color: rgba(255, 255, 255, 0.75);
        font-size: 1.05rem;
        max-width: 650px;
        margin: 0 auto 20px;
    }

    /* Search Bar in Hero */
    .blog-search-form {
        max-width: 580px;
        margin: 0 auto;
    }
    .blog-search-box {
        display: flex;
        background: #ffffff;
        border-radius: 50px;
        padding: 6px 8px 6px 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        border: 2px solid transparent;
        transition: border-color .2s;
    }
    .blog-search-box:focus-within {
        border-color: var(--brand, #FFC700);
    }
    .blog-search-box input {
        border: none;
        outline: none;
        flex: 1;
        font-size: 0.95rem;
        color: #111;
        background: transparent;
    }
    .blog-search-box button {
        background: var(--brand, #FFC700);
        color: #111;
        border: none;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 10px 22px;
        border-radius: 50px;
        cursor: pointer;
        transition: background .2s, transform .15s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .blog-search-box button:hover {
        background: #e6b300;
        transform: translateY(-1px);
    }

    /* Blog Card */
    .blog-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #ECEAE3;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    }
    .blog-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        border-color: var(--brand, #FFC700);
    }
    .blog-img-wrapper {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        background: #1a1a1a;
        overflow: hidden;
    }
    .blog-img-wrapper img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
    }
    .blog-card:hover .blog-img-wrapper img {
        transform: scale(1.06);
    }
    .blog-badge-date {
        position: absolute;
        top: 14px;
        right: 14px;
        background: rgba(17, 17, 17, 0.85);
        backdrop-filter: blur(4px);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid rgba(255, 199, 0, 0.4);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .blog-badge-date i {
        color: var(--brand, #FFC700);
    }
    .blog-card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .blog-meta {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 0.8rem;
        color: #777;
        margin-bottom: 12px;
    }
    .blog-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .blog-meta i {
        color: var(--brand, #FFC700);
        font-size: 0.88rem;
    }
    .blog-title {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.35;
        color: #111;
        margin-bottom: 12px;
        transition: color .2s;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blog-card:hover .blog-title {
        color: #b38600;
    }
    .blog-excerpt {
        color: #666;
        font-size: 0.92rem;
        line-height: 1.6;
        margin-bottom: 20px;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .blog-card-footer {
        border-top: 1px solid #F0EFE9;
        padding-top: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .blog-read-btn {
        color: #111;
        font-weight: 700;
        font-size: 0.88rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: gap .2s, color .2s;
    }
    .blog-read-btn i {
        color: var(--brand, #FFC700);
        font-size: 1rem;
        transition: transform .2s;
    }
    .blog-card:hover .blog-read-btn {
        color: #111;
    }
    .blog-card:hover .blog-read-btn i {
        transform: translateX(4px);
    }
    .blog-views-count {
        font-size: 0.8rem;
        color: #888;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* Custom Pagination */
    .pagination-wrapper {
        margin-top: 45px;
        display: flex;
        justify-content: center;
    }
    .pagination-wrapper .pagination {
        gap: 6px;
    }
    .pagination-wrapper .page-item .page-link {
        color: #111;
        border: 1.5px solid #E2E0D8;
        border-radius: 8px !important;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all .2s;
    }
    .pagination-wrapper .page-item.active .page-link {
        background-color: var(--brand, #FFC700);
        border-color: var(--brand, #FFC700);
        color: #111;
        font-weight: 700;
    }
    .pagination-wrapper .page-item .page-link:hover {
        background-color: #f7f7f5;
        border-color: var(--brand, #FFC700);
    }

    /* Empty state */
    .blog-empty-state {
        text-align: center;
        padding: 70px 20px;
        background: #fff;
        border-radius: 16px;
        border: 1px dashed #d5d3cb;
        margin: 30px 0;
    }
    .blog-empty-state i {
        font-size: 3.5rem;
        color: var(--brand, #FFC700);
        margin-bottom: 16px;
        display: block;
    }
</style>

<!-- Hero Section -->
<section class="blogs-hero">
    <div class="container text-center">
        <h1>Latest News & <span>Insights</span></h1>
        <p>Stay informed with the latest updates on cinematic lighting, equipment rentals, industry trends, and practical guides from Light As Air.</p>

        <!-- Search Box -->
        <form action="{{ route('front.blogs.index') }}" method="GET" class="blog-search-form">
            <div class="blog-search-box">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles, guides, gear...">
                @if(request('search'))
                    <a href="{{ route('front.blogs.index') }}" class="btn btn-sm text-muted me-2" title="Clear search" style="line-height:2.2;">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                @endif
                <button type="submit">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Main Blogs Section -->
<section class="py-5" style="background: #FAFAFA; min-height: 550px;">
    <div class="container">

        @if(request('search'))
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-0 text-dark fw-bold">
                    Search results for: <span class="text-warning">"{{ request('search') }}"</span>
                    <small class="text-muted fw-normal">({{ $blogs->total() }} found)</small>
                </h5>
                <a href="{{ route('front.blogs.index') }}" class="btn btn-sm btn-outline-dark rounded-pill">
                    <i class="bi bi-arrow-left me-1"></i> View All Blogs
                </a>
            </div>
        @endif

        @if($blogs->count() > 0)
            <!-- Grid of 6 cards per page -->
            <div class="row g-4">
                @foreach($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <article class="blog-card">
                            <div class="blog-img-wrapper">
                                @if($blog->image_url)
                                    <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" loading="lazy">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="position: absolute; top:0; left:0; background: linear-gradient(135deg, #222, #333);">
                                        <i class="bi bi-journal-richtext" style="font-size: 3rem; color: var(--brand, #FFC700);"></i>
                                    </div>
                                @endif
                                <span class="blog-badge-date">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $blog->created_at->format('M d, Y') }}
                                </span>
                            </div>

                            <div class="blog-card-body">
                                <div class="blog-meta">
                                    <span>
                                        <i class="bi bi-person-circle"></i>
                                        {{ $blog->author ?: 'Light As Air' }}
                                    </span>
                                    <span>
                                        <i class="bi bi-eye"></i>
                                        {{ number_format($blog->views) }} views
                                    </span>
                                </div>

                                <a href="{{ route('front.blogs.show', $blog->slug) }}" class="text-decoration-none">
                                    <h3 class="blog-title" title="{{ $blog->title }}">{{ $blog->title }}</h3>
                                </a>

                                <p class="blog-excerpt">
                                    {{ $blog->short_description ?: Str::limit(strip_tags($blog->description), 130) }}
                                </p>

                                <div class="blog-card-footer">
                                    <a href="{{ route('front.blogs.show', $blog->slug) }}" class="blog-read-btn">
                                        Read Article <i class="bi bi-arrow-right"></i>
                                    </a>
                                    <span class="blog-views-count">
                                        <i class="bi bi-clock"></i> {{ ceil(str_word_count(strip_tags($blog->description)) / 200) }} min read
                                    </span>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($blogs->hasPages())
                <div class="pagination-wrapper">
                    {{ $blogs->links('pagination::bootstrap-5') }}
                </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="blog-empty-state">
                <i class="bi bi-newspaper"></i>
                <h4 class="fw-bold text-dark">No Articles Found</h4>
                <p class="text-muted mb-4">
                    @if(request('search'))
                        We couldn't find any blogs matching "{{ request('search') }}". Try searching with different keywords.
                    @else
                        We are currently preparing insightful articles. Please check back soon!
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('front.blogs.index') }}" class="btn btn-warning fw-bold px-4 rounded-pill">
                        <i class="bi bi-arrow-clockwise me-1"></i> Clear Search
                    </a>
                @else
                    <a href="/" class="btn btn-warning fw-bold px-4 rounded-pill">
                        <i class="bi bi-house-door me-1"></i> Return Home
                    </a>
                @endif
            </div>
        @endif

    </div>
</section>

<!-- Rental CTA Section -->
<section class="py-5 bg-dark text-white text-center position-relative" style="border-top: 1px solid rgba(255,255,255,0.08);">
    <div class="container py-3">
        <h2 class="fw-bold mb-3">Looking for Professional Cinematic Gear?</h2>
        <p class="text-muted mx-auto mb-4" style="max-width: 600px;">
            Light As Air is the official Godox KNOWLED dealer across the UK. Rent or purchase industry-grade lighting and film equipment.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="/items?type=rental" class="btn btn-warning fw-bold px-4 py-2 rounded-pill">
                <i class="bi bi-cart me-1"></i> Explore Rental Gear
            </a>
            <a href="{{ route('front.account-opening') }}" class="btn btn-outline-light fw-bold px-4 py-2 rounded-pill">
                <i class="bi bi-file-earmark-person me-1"></i> Open Trade Account
            </a>
        </div>
    </div>
</section>

@endsection
