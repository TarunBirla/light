<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuctionFaq;
use Illuminate\Http\Request;

class AuctionFaqController extends Controller
{
    public function index()
    {
        $faqs = AuctionFaq::orderBy('sort_order', 'asc')->latest()->get();
        return view('admin.auction-faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.auction-faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer',
            'status'     => 'required|in:active,inactive',
        ]);

        AuctionFaq::create([
            'question'   => $request->question,
            'answer'     => $request->answer,
            'sort_order' => $request->sort_order ?? 0,
            'status'     => $request->status,
        ]);

        return redirect()->route('admin.auction-faqs.index')->with('success', 'Auction FAQ Created Successfully');
    }

    public function edit($id)
    {
        $faq = AuctionFaq::findOrFail($id);
        return view('admin.auction-faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $faq = AuctionFaq::findOrFail($id);

        $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'sort_order' => 'nullable|integer',
            'status'     => 'required|in:active,inactive',
        ]);

        $faq->update([
            'question'   => $request->question,
            'answer'     => $request->answer,
            'sort_order' => $request->sort_order ?? 0,
            'status'     => $request->status,
        ]);

        return redirect()->route('admin.auction-faqs.index')->with('success', 'Auction FAQ Updated Successfully');
    }

    public function destroy($id)
    {
        $faq = AuctionFaq::findOrFail($id);
        $faq->delete();
        return redirect()->back()->with('success', 'Auction FAQ Deleted Successfully');
    }
}
