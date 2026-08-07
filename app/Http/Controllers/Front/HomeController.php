<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Item;
use App\Models\GeneratorBanner;
use App\Models\RequestLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestLeadMail;


class HomeController extends Controller
{


public function guestRequest(Request $request)
{
    $request->validate([
        'items' => 'required|array|min:1',
        'name'  => 'required',
        'email' => 'required|email',
        'phone' => 'required'
    ]);

    $rawType = strtolower($request->product_type ?? 'rental');
    $isSell  = in_array($rawType, ['sell', 'selling', 'selling request']);

    $productTypeDb    = $isSell ? 'Sell' : 'Rental';
    $requestTypeTitle = $isSell ? 'Selling Request' : 'Rental Request';

    $itemsList = [];
    $itemsText = '';

    foreach ($request->items as $item)
    {
        $itemTitle = is_array($item) ? ($item['title'] ?? 'Item') : $item;
        $itemId    = is_array($item) ? ($item['id'] ?? null) : null;

        RequestLead::create([
            'item_id'      => $itemId,
            'item_name'    => $itemTitle,
            'product_type' => $productTypeDb,
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'message'      => $request->message
        ]);

        $itemsList[] = $itemTitle;
        $itemsText  .= "• " . $itemTitle . "\n";
    }

    $mailData = [
        'items'        => $itemsList,
        'product_type' => $requestTypeTitle,
        'name'         => $request->name,
        'email'        => $request->email,
        'phone'        => $request->phone,
        'message'      => $request->message,
    ];

    try {
        Mail::to('tbirla120@gmail.com')
            ->send(new RequestLeadMail($mailData));
    } catch (\Exception $e) {
        \Log::error('Mail sending error: ' . $e->getMessage());
    }

    return response()->json([
        'status'       => true,
        'items'        => $itemsText,
        'product_type' => $requestTypeTitle,
        'name'         => $request->name,
        'email'        => $request->email,
        'phone'        => $request->phone,
        'message'      => $request->message
    ]);
}
public function index()
{
    $banners = Banner::where('status','active')
        ->get();

    $generatorbanners = GeneratorBanner::where('status',1)
        ->get();

    $categories = Category::where('status','active')
        ->orderBy('number','asc')
        ->take(8)
        ->get();

    $items = Item::where('status','active')
        ->orderBy('category_id','asc')
        ->orderBy('sort_order','asc')
        ->take(8)
        ->get();

    $rental_items = Item::where('status','active')
        ->where('is_rental', 1)
        ->orderBy('category_id','asc')
        ->orderBy('sort_order','asc')
        ->take(8)
        ->get();

    $selling_items = Item::where('status','active')
        ->where('is_sell', 1)
        ->orderBy('category_id','asc')
        ->orderBy('sort_order','asc')
        ->take(8)
        ->get();

    return view(
        'front.home',
        compact(
            'banners',
            'categories',
            'items',
            'rental_items',
            'selling_items',
            'generatorbanners'
        )
    );
}

    public function itemDetail($id)
{
    $item = Item::findOrFail($id);

    return view(
        'front.item-detail',
        compact('item')
    );
}
}