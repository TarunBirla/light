<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\AuctionProduct;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')->latest()->get();
        $items      = Item::where('status', 'active')->latest()->get();
        $auctions   = AuctionProduct::where('status', 'active')->latest()->get();

        $content = view('front.sitemap', compact('categories', 'items', 'auctions'))->render();

        return response($content, 200)->header('Content-Type', 'text/xml');
    }
}
