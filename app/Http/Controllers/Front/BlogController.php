<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * Display blogs listing with 6 cards per page
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Blog::where('status', 'active')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Per page 6 cards as requested
        $blogs = $query->paginate(6)->withQueryString();

        $recentBlogs = Blog::where('status', 'active')->latest()->take(4)->get();

        return view('front.blogs.index', compact('blogs', 'recentBlogs', 'search'));
    }

    /**
     * Display single blog detail page
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Increment view count
        $blog->increment('views');

        // Recent / Related blogs
        $recentBlogs = Blog::where('status', 'active')
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(3)
            ->get();

        return view('front.blogs.show', compact('blog', 'recentBlogs'));
    }
}
