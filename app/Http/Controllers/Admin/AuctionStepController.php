<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuctionStep;
use Illuminate\Http\Request;

class AuctionStepController extends Controller
{
    public function index()
    {
        $steps = AuctionStep::orderBy('sort_order', 'asc')->orderBy('step_number', 'asc')->get();
        return view('admin.auction-steps.index', compact('steps'));
    }

    public function create()
    {
        return view('admin.auction-steps.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'step_number' => 'required|integer',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order'  => 'nullable|integer',
            'status'      => 'required|in:active,inactive',
        ]);

        AuctionStep::create([
            'step_number' => $request->step_number,
            'title'       => $request->title,
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? $request->step_number,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.steps.index')->with('success', 'Auction Step Created Successfully');
    }

    public function edit($id)
    {
        $step = AuctionStep::findOrFail($id);
        return view('admin.auction-steps.edit', compact('step'));
    }

    public function update(Request $request, $id)
    {
        $step = AuctionStep::findOrFail($id);

        $request->validate([
            'step_number' => 'required|integer',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'sort_order'  => 'nullable|integer',
            'status'      => 'required|in:active,inactive',
        ]);

        $step->update([
            'step_number' => $request->step_number,
            'title'       => $request->title,
            'description' => $request->description,
            'sort_order'  => $request->sort_order ?? $request->step_number,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.steps.index')->with('success', 'Auction Step Updated Successfully');
    }

    public function destroy($id)
    {
        $step = AuctionStep::findOrFail($id);
        $step->delete();
        return redirect()->back()->with('success', 'Auction Step Deleted Successfully');
    }
}
