<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuctionProduct;
use App\Models\Category;
use Illuminate\Http\Request;

class AuctionProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $auctionProducts = AuctionProduct::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(20);

        return view('admin.auction-products.index', compact('auctionProducts'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();

        return view('admin.auction-products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'title'       => 'required|string|max:255',
            'qty'         => 'required|integer|min:1',
            'minprice'    => 'required|numeric|min:0',
            'shipping_type' => 'nullable|in:excluded,included,both',
        ]);

        $images = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $imageName = time() . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path('uploads/auction_products'), $imageName);
                $images[] = $imageName;
            }
        }

        AuctionProduct::create([
            'category_id'   => $request->category_id,
            'title'         => $request->title,
            'description'   => $request->description,
            'image'         => $images,
            'qty'           => $request->qty,
            'minprice'      => $request->minprice,
            'status'        => $request->status ?? 'active',
            'shipping_type' => $request->shipping_type ?? 'excluded',
            'sort_order'    => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('auction-products.index')
            ->with('success', 'Auction Product Added Successfully');
    }

    public function edit($id)
    {
        $auctionProduct = AuctionProduct::findOrFail($id);
        $categories     = Category::all();

        return view('admin.auction-products.edit', compact('auctionProduct', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $auctionProduct = AuctionProduct::findOrFail($id);

        $request->validate([
            'category_id' => 'required',
            'title'       => 'required|string|max:255',
            'qty'         => 'required|integer|min:1',
            'minprice'    => 'required|numeric|min:0',
            'shipping_type' => 'nullable|in:excluded,included,both',
        ]);

        $oldImages = [];
        if (!empty($auctionProduct->image)) {
            if (is_array($auctionProduct->image)) {
                $oldImages = $auctionProduct->image;
            } else {
                $decoded = json_decode($auctionProduct->image, true);
                $oldImages = is_array($decoded) ? $decoded : [$auctionProduct->image];
            }
        }

        // Delete Images
        $deletedImages = json_decode($request->deleted_images, true) ?? [];
        foreach ($deletedImages as $index) {
            if (isset($oldImages[$index])) {
                $filePath = public_path('uploads/auction_products/' . $oldImages[$index]);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
                unset($oldImages[$index]);
            }
        }
        $oldImages = array_values($oldImages);

        // Upload New Images
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $imageName = time() . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path('uploads/auction_products'), $imageName);
                $oldImages[] = $imageName;
            }
        }

        $auctionProduct->update([
            'category_id'   => $request->category_id,
            'title'         => $request->title,
            'description'   => $request->description,
            'image'         => $oldImages,
            'qty'           => $request->qty,
            'minprice'      => $request->minprice,
            'status'        => $request->status,
            'shipping_type' => $request->shipping_type ?? 'excluded',
            'sort_order'    => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('auction-products.index')
            ->with('success', 'Auction Product Updated Successfully');
    }

    public function destroy($id)
    {
        $auctionProduct = AuctionProduct::findOrFail($id);
        
        // Remove image files
        if (!empty($auctionProduct->image) && is_array($auctionProduct->image)) {
            foreach ($auctionProduct->image as $img) {
                $filePath = public_path('uploads/auction_products/' . $img);
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }

        $auctionProduct->delete();

        return redirect()
            ->back()
            ->with('success', 'Auction Product Deleted Successfully');
    }
}
