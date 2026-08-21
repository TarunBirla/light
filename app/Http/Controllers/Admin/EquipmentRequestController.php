<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentRequest;
use Illuminate\Http\Request;

class EquipmentRequestController extends Controller
{
    public function index()
    {
        $requests = EquipmentRequest::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.equipment-requests.index', compact('requests'));
    }

    public function show($id)
    {
        $equipmentRequest = EquipmentRequest::with(['user', 'items.product', 'items.category'])
            ->findOrFail($id);

        $groupedItems = $equipmentRequest->items->groupBy(function ($item) {
            return $item->category ? $item->category->name : 'Other / Uncategorized';
        });

        return view('admin.equipment-requests.show', compact('equipmentRequest', 'groupedItems'));
    }

    public function destroy($id)
    {
        $equipmentRequest = EquipmentRequest::findOrFail($id);
        $equipmentRequest->delete();

        return redirect()
            ->route('admin.equipment-requests.index')
            ->with('success', 'Equipment Request deleted successfully.');
    }
}
