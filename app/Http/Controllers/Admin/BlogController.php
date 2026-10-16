<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    /**
     * Display a listing of the blogs.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Blog::latest();

        if ($status && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $blogs = $query->paginate(15)->withQueryString();

        $counts = [
            'all'      => Blog::count(),
            'active'   => Blog::where('status', 'active')->count(),
            'inactive' => Blog::where('status', 'inactive')->count(),
        ];

        return view('admin.blogs.index', compact('blogs', 'counts', 'status', 'search'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created blog in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'required|string',
            'author'            => 'nullable|string|max:100',
            'status'            => 'required|in:active,inactive',
        ], [
            'title.required'       => 'Blog title is required.',
            'description.required' => 'Blog content/description is required.',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/blogs'), $imageName);
        }

        $slug = Str::slug($request->title);
        $count = Blog::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        Blog::create([
            'title'             => $request->title,
            'slug'              => $slug,
            'image'             => $imageName,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'author'            => $request->author ?: 'Light As Air Team',
            'status'            => $request->status,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully!');
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update the specified blog in storage.
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title'             => 'required|string|max:255',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'required|string',
            'author'            => 'nullable|string|max:100',
            'status'            => 'required|in:active,inactive',
        ]);

        $imageName = $blog->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($blog->image && File::exists(public_path('uploads/blogs/' . $blog->image))) {
                File::delete(public_path('uploads/blogs/' . $blog->image));
            }
            $file = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path('uploads/blogs'), $imageName);
        }

        $blog->update([
            'title'             => $request->title,
            'image'             => $imageName,
            'short_description' => $request->short_description,
            'description'       => $request->description,
            'author'            => $request->author ?: 'Light As Air Team',
            'status'            => $request->status,
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully!');
    }

    /**
     * Quick status update toggle from listing table.
     */
    public function updateStatus(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $request->validate(['status' => 'required|in:active,inactive']);

        $blog->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Blog status updated successfully.');
    }

    /**
     * Remove the specified blog from storage.
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->image && File::exists(public_path('uploads/blogs/' . $blog->image))) {
            File::delete(public_path('uploads/blogs/' . $blog->image));
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully.');
    }
}
