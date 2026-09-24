<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\AuctionProduct;
use App\Models\AuctionRequest;
use App\Models\Category;
use App\Mail\AuctionRequestSubmittedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->search;
        $categoryId = $request->category_id;
        $tab        = $request->get('tab', 'active');

        $categories = Category::where('status', 'active')->get();

        $query = AuctionProduct::with(['category', 'requests'])
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            });

        $allProducts = $query->orderBy('sort_order', 'asc')->latest()->get();

        if ($tab === 'old') {
            $filtered = $allProducts->filter(function ($item) {
                return $item->available_qty <= 0 || $item->status !== 'active';
            });
        } else {
            $filtered = $allProducts->filter(function ($item) {
                return $item->available_qty > 0 && $item->status === 'active';
            });
        }

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        $paginatedItems = $filtered->slice(($page - 1) * $perPage, $perPage)->values();

        $auctionProducts = new LengthAwarePaginator(
            $paginatedItems,
            $filtered->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('front.auctions.index', compact('auctionProducts', 'categories', 'tab'));
    }

    public function show($id)
    {
        $auctionProduct = AuctionProduct::with(['category', 'requests' => function ($query) {
            $query->orderBy('bid_price', 'desc')->latest();
        }])
        ->where('status', 'active')
        ->findOrFail($id);

        $highestBid = $auctionProduct->requests->max('bid_price') ?? $auctionProduct->minprice;

        return view('front.auctions.show', compact('auctionProduct', 'highestBid'));
    }

    public function storeRequest(Request $request)
    {
        $auctionProduct = AuctionProduct::findOrFail($request->auction_product_id);

        $availQty = (int)$auctionProduct->available_qty;

        if ($availQty <= 0) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'This product is SOLD OUT and no longer accepting bids.'
                ], 422);
            }
            return redirect()->back()->withErrors(['product' => 'This product is SOLD OUT and no longer accepting bids.']);
        }

        $minPrice = (float)$auctionProduct->minprice;

        $request->validate([
            'auction_product_id' => 'required|exists:auction_products,id',
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'phone'              => 'required|string|max:50',
            'address'            => 'required|string|max:500',
            'terms'              => 'required|accepted',
            'bid_price'          => 'required|numeric|min:' . $minPrice,
            'qty'                => 'required|integer|min:1|max:' . $availQty,
            'message'            => 'nullable|string',
        ], [
            'name.required'      => 'Full name is required.',
            'email.required'     => 'Email address is required.',
            'phone.required'     => 'Phone number is required.',
            'address.required'   => 'Address is required.',
            'terms.accepted'     => 'You must agree to the Terms and Conditions before submitting.',
            'bid_price.required' => 'Bid offer price is required.',
            'bid_price.min'      => 'Your bid offer price must be at least £' . number_format($minPrice, 2) . '.',
            'qty.min'            => 'Quantity must be at least 1.',
            'qty.max'            => 'Quantity cannot exceed available stock (' . $availQty . ').',
        ]);

        // Check if guest already has a PENDING request for this product (by email or phone)
        $existingRequest = AuctionRequest::where('auction_product_id', $request->auction_product_id)
            ->where('status', 'pending')
            ->where(function ($q) use ($request) {
                $q->where('email', $request->email)
                  ->orWhere('phone', $request->phone);
            })
            ->first();

        if ($existingRequest) {
            // Update existing pending bid
            $existingRequest->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'bid_price' => $request->bid_price,
                'qty'       => $request->qty,
                'message'   => $request->message,
            ]);

            $auctionReq = $existingRequest;
            $msg = 'Your pending auction bid for this product has been updated successfully!';
        } else {
            // Create a brand new bid (since previous ones are completed/approved/rejected or first time)
            $auctionReq = AuctionRequest::create([
                'auction_product_id' => $request->auction_product_id,
                'name'               => $request->name,
                'email'              => $request->email,
                'phone'              => $request->phone,
                'address'            => $request->address,
                'bid_price'          => $request->bid_price,
                'qty'                => $request->qty,
                'message'            => $request->message,
                'status'             => 'pending',
            ]);

            $msg = 'Your new auction bid has been submitted successfully!';
        }

        // Send Email Notification to Admin
        try {
            Mail::to('mohammednasar.uk@gmail.com')->send(new AuctionRequestSubmittedMail($auctionReq));
        } catch (\Exception $e) {
            Log::error('Auction Request Email Notification Error: ' . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status'    => true,
                'message'   => $msg,
                'item'      => $auctionProduct->title,
                'bid_price' => $request->bid_price,
                'qty'       => $request->qty,
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'address'   => $request->address,
                'user_msg'  => $request->message,
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }
}
