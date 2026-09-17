<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuctionRequest;
use Illuminate\Http\Request;

class AuctionRequestController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $requests = AuctionRequest::with(['auctionProduct', 'auctionProduct.category'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%")
                        ->orWhereHas('auctionProduct', function ($subQ) use ($search) {
                            $subQ->where('title', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return view('admin.auction-requests.index', compact('requests'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $auctionRequest = AuctionRequest::with('auctionProduct')->findOrFail($id);
        $oldStatus      = $auctionRequest->status;
        $newStatus      = $request->status;

        $isOldApproved = in_array($oldStatus, ['approved', 'completed']);
        $isNewApproved = in_array($newStatus, ['approved', 'completed']);

        $product = $auctionRequest->auctionProduct;

        if ($product) {
            // Deduct quantity when request is approved or completed
            if (!$isOldApproved && $isNewApproved) {
                $product->qty = max(0, $product->qty - $auctionRequest->qty);
                $product->save();
            }
            // Restore quantity if status is reverted back to pending/rejected
            elseif ($isOldApproved && !$isNewApproved) {
                $product->qty = $product->qty + $auctionRequest->qty;
                $product->save();
            }
        }

        $auctionRequest->update([
            'status' => $newStatus
        ]);

        return redirect()->back()->with('success', 'Auction Request Status Updated & Product Stock Quantity adjusted successfully.');
    }

    public function destroy($id)
    {
        $auctionRequest = AuctionRequest::with('auctionProduct')->findOrFail($id);
        
        // Restore stock if deleting an approved/completed request
        if (in_array($auctionRequest->status, ['approved', 'completed']) && $auctionRequest->auctionProduct) {
            $product = $auctionRequest->auctionProduct;
            $product->qty = $product->qty + $auctionRequest->qty;
            $product->save();
        }

        $auctionRequest->delete();

        return redirect()->back()->with('success', 'Auction Request Deleted Successfully');
    }
}
