@extends('front.layouts.app')

@section('title', $blog->title . ' – Light As Air Blog')
@section('meta_description', $blog->short_description ?: Str::limit(strip_tags($blog->description), 155))

@section('content')

<style>
    .blog-detail-header {
        background: linear-gradient(135deg, #111111 0%, #1e1e1e 100%);
        color: #ffffff;
        padding: 50px 0 40px;
        border-bottom: 3px solid var(--brand, #FFC700);
    }
    .blog-breadcrumbs {
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 20px;
    }
    .blog-breadcrumbs a {
        color: var(--brand, #FFC700);
        text-decoration: none;
    }
    .blog-breadcrumbs a:hover {
        text-decoration: underline;
    }
    .blog-detail-title {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -0.5px;
        margin-bottom: 20px;
        color: #ffffff;
    }
    .blog-detail-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 20px;
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.75);
    }
    .blog-detail-meta .meta-item {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }
    .blog-detail-meta i {
        color: var(--brand, #FFC700);
        font-size: 1rem;
    }

    /* Featured Banner Image */
    .blog-featured-banner {
        width: 100%;
        max-height: 520px;
        border-radius: 18px;
        overflow: hidden;
        margin-top: -30px;
        margin-bottom: 40px;
        box-shadow: 0 12px 35px rgba(0,0,0,0.12);
        background: #111;
    }
    .blog-featured-banner img {
        width: 100%;
        height: 100%;
        max-height: 520px;
        object-fit: cover;
        display: block;
    }

    /* Content Styling */
    .blog-article-content {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #ECEAE3;
        padding: 45px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        font-size: 1.05rem;
        line-height: 1.85;
        color: #2b2b2b;
    }
    .blog-article-content h1,
    .blog-article-content h2,
    .blog-article-content h3,
    .blog-article-content h4 {
        color: #111;
        font-weight: 700;
        margin-top: 1.8rem;
        margin-bottom: 1rem;
        line-height: 1.35;
    }
    .blog-article-content h2 {
        font-size: 1.65rem;
        border-left: 4px solid var(--brand, #FFC700);
        padding-left: 14px;
    }
    .blog-article-content h3 {
        font-size: 1.35rem;
    }
    .blog-article-content p {
        margin-bottom: 1.5rem;
    }
    .blog-article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 20px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .blog-article-content blockquote {
        background: #FBF9F2;
        border-left: 4px solid var(--brand, #FFC700);
        padding: 20px 24px;
        margin: 25px 0;
        font-style: italic;
        color: #444;
        border-radius: 0 12px 12px 0;
    }
    .blog-article-content ul,
    .blog-article-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .blog-article-content li {
        margin-bottom: 0.5rem;
    }
    .blog-article-content a {
        color: #111;
        text-decoration: underline;
        font-weight: 600;
    }
    .blog-article-content a:hover {
        color: #b38600;
    }

    /* Share & Tags bar */
    .article-footer-actions {
        border-top: 2px solid #F0EFE9;
        margin-top: 40px;
        padding-top: 25px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }
    .share-btn-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .share-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #F4F3ED;
        color: #111;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all .2s;
        border: 1px solid #E2E0D8;
    }
    .share-btn:hover {
        background: var(--brand, #FFC700);
        color: #111;
        transform: translateY(-2px);
    }

    /* Related Posts */
    .related-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #ECEAE3;
        overflow: hidden;
        transition: transform .25s, box-shadow .25s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border-color: var(--brand, #FFC700);
    }
    .related-img {
        width: 100%;
        padding-top: 56.25%;
        position: relative;
        background: #111;
        overflow: hidden;
    }
    .related-img img {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: cover;
    }
    .related-body {
        padding: 18px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .related-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #111;
        line-height: 1.35;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .related-card:hover .related-title {
        color: #b38600;
    }

    @media (max-width: 768px) {
        .blog-detail-title {
            font-size: 1.85rem;
        }
        .blog-article-content {
            padding: 24px 18px;
        }
    }
</style>

<!-- Header -->
<header class="blog-detail-header">
    <div class="container">
        <!-- Breadcrumbs -->
        <div class="blog-breadcrumbs">
            <a href="/"><i class="bi bi-house-door me-1"></i> Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('front.blogs.index') }}">Blogs</a>
            <span class="mx-2">/</span>
            <span class="text-white">{{ Str::limit($blog->title, 40) }}</span>
        </div>

        <h1 class="blog-detail-title">{{ $blog->title }}</h1>

        <div class="blog-detail-meta">
            <span class="meta-item">
                <i class="bi bi-person-circle"></i>
                By <strong>{{ $blog->author ?: 'Light As Air Team' }}</strong>
            </span>
            <span class="meta-item">
                <i class="bi bi-calendar3"></i>
                {{ $blog->created_at->format('F d, Y') }}
            </span>
            <span class="meta-item">
                <i class="bi bi-eye"></i>
                {{ number_format($blog->views) }} views
            </span>
            <span class="meta-item">
                <i class="bi bi-clock"></i>
                {{ ceil(str_word_count(strip_tags($blog->description)) / 200) }} min read
            </span>
        </div>
    </div>
</header>

<!-- Main Content Area -->
<main class="py-5" style="background: #FAFAFA;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <!-- Featured Image -->
                @if($blog->image_url)
                    <div class="blog-featured-banner">
                        <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}">
                    </div>
                @endif

                <!-- Article Body -->
                <article class="blog-article-content">
                    @if($blog->short_description)
                        <div class="lead fw-medium mb-4 pb-3 border-bottom" style="color: #444; font-size: 1.15rem; line-height: 1.7;">
                            {{ $blog->short_description }}
                        </div>
                    @endif

                    <div class="article-body-text">
                        {!! $blog->description !!}
                    </div>

                    <!-- Footer actions (Share & Back) -->
                    <div class="article-footer-actions">
                        <div>
                            <a href="{{ route('front.blogs.index') }}" class="btn btn-outline-dark rounded-pill px-4 btn-sm fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i> Back to all articles
                            </a>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small fw-semibold me-1">Share this:</span>
                            <div class="share-btn-group">
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->title) }}" target="_blank" class="share-btn" title="Share on X">
                                    <i class="bi bi-twitter-x"></i>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' ' . url()->current()) }}" target="_blank" class="share-btn" title="Share via WhatsApp">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($blog->title) }}" target="_blank" class="share-btn" title="Share on LinkedIn">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                                <button type="button" class="share-btn" title="Copy Link" onclick="navigator.clipboard.writeText(window.location.href); alert('Article link copied to clipboard!');">
                                    <i class="bi bi-link-45deg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>

                <!-- Rental Banner Callout inside article -->
                <div class="card border-0 rounded-4 shadow-sm p-4 mt-5 text-white" style="background: linear-gradient(135deg, #111, #222); border-left: 5px solid var(--brand, #FFC700) !important;">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-3 mb-md-0">
                            <h4 class="fw-bold mb-1" style="color: var(--brand, #FFC700);">Official Godox KNOWLED Dealer</h4>
                            <p class="text-white-50 mb-0 small">
                                Need high-performance lighting kits, optical accessories, or audio gear for your next shoot? We provide flexible rental rates and direct purchase options.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="/items?type=rental" class="btn btn-warning fw-bold px-4 rounded-pill">
                                View Equipment <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Related Blogs -->
        @if($recentBlogs->count() > 0)
            <div class="row justify-content-center mt-5 pt-4">
                <div class="col-lg-9">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h3 class="fw-bold text-dark mb-0">More From Our Blog</h3>
                        <a href="{{ route('front.blogs.index') }}" class="text-dark fw-bold text-decoration-none small">
                            View All <i class="bi bi-chevron-right"></i>
                        </a>
                    </div>

                    <div class="row g-4">
                        @foreach($recentBlogs as $recent)
                            <div class="col-md-4">
                                <a href="{{ route('front.blogs.show', $recent->slug) }}" class="text-decoration-none">
                                    <div class="related-card">
                                        <div class="related-img">
                                            @if($recent->image_url)
                                                <img src="{{ $recent->image_url }}" alt="{{ $recent->title }}" loading="lazy">
                                            @else
                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white" style="position: absolute; top:0; left:0; background: #222;">
                                                    <i class="bi bi-journal-richtext text-warning" style="font-size: 2rem;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="related-body">
                                            <span class="text-muted small mb-2 d-block">
                                                <i class="bi bi-calendar3 me-1 text-warning"></i> {{ $recent->created_at->format('M d, Y') }}
                                            </span>
                                            <h4 class="related-title">{{ $recent->title }}</h4>
                                            <span class="text-warning fw-bold small mt-auto">
                                                Read More <i class="bi bi-arrow-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
</main>

@endsection
