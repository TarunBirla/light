<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\EquipmentRequest;
use App\Models\EquipmentRequestItem;
use App\Models\Item;
use App\Mail\EquipmentRequestSubmittedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class EquipmentRequestController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')
            ->orderBy('number', 'asc')
            ->get();

        foreach ($categories as $category) {
            $category->products = Item::where('category_id', $category->id)
                ->where('status', 'active')
                ->orderBy('sort_order', 'asc')
                ->get();
        }

        return view('front.equipment-request', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'gaffer' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact' => 'nullable|string|max:255',
            'production_company' => 'nullable|string|max:255',
            'production_title' => 'required|string|max:255',
            'production_contact' => 'nullable|string|max:255',
            'dop' => 'nullable|string|max:255',
            'rig_from' => 'nullable|date',
            'rig_to' => 'nullable|date|after_or_equal:rig_from',
            'prelight_from' => 'nullable|date',
            'prelight_to' => 'nullable|date|after_or_equal:prelight_from',
            'shoot_from' => 'nullable|date',
            'shoot_to' => 'nullable|date|after_or_equal:shoot_from',
            'derig_from' => 'nullable|date',
            'derig_to' => 'nullable|date|after_or_equal:derig_from',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'address_line_3_postcode' => 'nullable|string|max:255',
            'quantities' => 'required|array',
        ]);

        $selectedItems = [];
        if ($request->has('quantities') && is_array($request->quantities)) {
            foreach ($request->quantities as $productId => $qty) {
                $qtyInt = (int)$qty;
                if ($qtyInt > 0) {
                    $item = Item::find($productId);
                    if ($item && $item->status === 'active') {
                        $selectedItems[] = [
                            'product_id' => $item->id,
                            'category_id' => $item->category_id,
                            'quantity' => $qtyInt
                        ];
                    }
                }
            }
        }

        if (empty($selectedItems)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please select at least one product with a quantity greater than 0.'
                ], 422);
            }
            return back()->withInput()->with('error', 'Please select at least one product with a quantity greater than 0.');
        }

        DB::beginTransaction();
        try {
            $locationAddress = implode(', ', array_filter([
                $request->address_line_1,
                $request->address_line_2,
                $request->address_line_3_postcode
            ]));

            $equipmentRequest = EquipmentRequest::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'gaffer' => $request->gaffer,
                'email' => $request->email,
                'contact' => $request->contact,
                'production_company' => $request->production_company,
                'production_title' => $request->production_title,
                'production_contact' => $request->production_contact,
                'dop' => $request->dop,
                'rig_from' => $request->rig_from,
                'rig_to' => $request->rig_to,
                'prelight_from' => $request->prelight_from,
                'prelight_to' => $request->prelight_to,
                'shoot_from' => $request->shoot_from,
                'shoot_to' => $request->shoot_to,
                'derig_from' => $request->derig_from,
                'derig_to' => $request->derig_to,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'address_line_3_postcode' => $request->address_line_3_postcode,
                'location_address' => $locationAddress,
                'status' => 'submitted',
            ]);

            foreach ($selectedItems as $itemData) {
                EquipmentRequestItem::create([
                    'equipment_request_id' => $equipmentRequest->id,
                    'category_id' => $itemData['category_id'],
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                ]);
            }

            DB::commit();

            // Send admin notification email
            try {
                $equipmentRequest->load(['items.product', 'items.category']);
                Mail::to('mohammednasar.uk@gmail.com')->send(new EquipmentRequestSubmittedMail($equipmentRequest));
            } catch (\Exception $e) {
                Log::error('Failed sending equipment request admin email: ' . $e->getMessage());
            }


            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Your equipment request has been submitted successfully!',
                    'request_id' => $equipmentRequest->id
                ]);
            }

            return redirect()->back()->with('success', 'Your equipment request has been submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while submitting your request: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->with('error', 'An error occurred while submitting your request: ' . $e->getMessage());
        }
    }

    public function indexNew()
    {
        return view('front.equipment-requestnew');
    }

    public function storeNew(Request $request)
    {
        $validatedData = $request->validate([
            'gaffer' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'contact' => 'nullable|string|max:255',
            'production_company' => 'nullable|string|max:255',
            'production_title' => 'required|string|max:255',
            'production_contact' => 'nullable|string|max:255',
            'dop' => 'nullable|string|max:255',
            'rig_from' => 'nullable|date',
            'rig_to' => 'nullable|date|after_or_equal:rig_from',
            'prelight_from' => 'nullable|date',
            'prelight_to' => 'nullable|date|after_or_equal:prelight_from',
            'shoot_from' => 'nullable|date',
            'shoot_to' => 'nullable|date|after_or_equal:shoot_from',
            'derig_from' => 'nullable|date',
            'derig_to' => 'nullable|date|after_or_equal:derig_from',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'address_line_3_postcode' => 'nullable|string|max:255',
            'quantities' => 'required|array',
        ]);

        $selectedItems = [];
        if ($request->has('quantities') && is_array($request->quantities)) {
            foreach ($request->quantities as $catName => $products) {
                if (is_array($products)) {
                    foreach ($products as $title => $qty) {
                        $qtyInt = (int)$qty;
                        if ($qtyInt > 0) {
                            $category = Category::firstOrCreate(
                                ['name' => $catName],
                                ['status' => 'active', 'number' => 99]
                            );

                            $item = Item::firstOrCreate(
                                ['title' => $title],
                                [
                                    'category_id' => $category->id,
                                    'status' => 'active',
                                    'price_per_day' => 0,
                                    'qty' => 100
                                ]
                            );

                            $selectedItems[] = [
                                'product_id' => $item->id,
                                'category_id' => $category->id,
                                'quantity' => $qtyInt
                            ];
                        }
                    }
                }
            }
        }

        if (empty($selectedItems)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please select at least one product with a quantity greater than 0.'
                ], 422);
            }
            return back()->withInput()->with('error', 'Please select at least one product with a quantity greater than 0.');
        }

        DB::beginTransaction();
        try {
            $locationAddress = implode(', ', array_filter([
                $request->address_line_1,
                $request->address_line_2,
                $request->address_line_3_postcode
            ]));

            $equipmentRequest = EquipmentRequest::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'gaffer' => $request->gaffer,
                'email' => $request->email,
                'contact' => $request->contact,
                'production_company' => $request->production_company,
                'production_title' => $request->production_title,
                'production_contact' => $request->production_contact,
                'dop' => $request->dop,
                'rig_from' => $request->rig_from,
                'rig_to' => $request->rig_to,
                'prelight_from' => $request->prelight_from,
                'prelight_to' => $request->prelight_to,
                'shoot_from' => $request->shoot_from,
                'shoot_to' => $request->shoot_to,
                'derig_from' => $request->derig_from,
                'derig_to' => $request->derig_to,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'address_line_3_postcode' => $request->address_line_3_postcode,
                'location_address' => $locationAddress,
                'status' => 'submitted',
            ]);

            foreach ($selectedItems as $itemData) {
                EquipmentRequestItem::create([
                    'equipment_request_id' => $equipmentRequest->id,
                    'category_id' => $itemData['category_id'],
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                ]);
            }

            DB::commit();

            // Send admin notification email
            try {
                $equipmentRequest->load(['items.product', 'items.category']);
                Mail::to('mohammednasar.uk@gmail.com')->send(new EquipmentRequestSubmittedMail($equipmentRequest));
            } catch (\Exception $e) {
                Log::error('Failed sending equipment request admin email: ' . $e->getMessage());
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Your equipment request has been submitted successfully!',
                    'request_id' => $equipmentRequest->id
                ]);
            }

            return redirect()->back()->with('success', 'Your equipment request has been submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while submitting your request: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->with('error', 'An error occurred while submitting your request: ' . $e->getMessage());
        }
    }
}

