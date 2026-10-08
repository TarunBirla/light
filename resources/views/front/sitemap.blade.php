{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    <!-- Static Main Pages -->
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/items') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/categories') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/auctions') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ url('/equipment-requestnew') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/about') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/brand') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/television') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/portfolio') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/terms') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ url('/login') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>
    <url>
        <loc>{{ url('/register') }}</loc>
        <lastmod>{{ date('Y-m-d') }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.4</priority>
    </url>

    <!-- Categories -->
    @foreach($categories as $category)
        <url>
            <loc>{{ url('/category/' . $category->id) }}</loc>
            <lastmod>{{ $category->updated_at ? $category->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
            @if($category->image)
                <image:image>
                    <image:loc>{{ asset('uploads/category/' . $category->image) }}</image:loc>
                    <image:title>{{ $category->name }}</image:title>
                </image:image>
            @endif
        </url>
    @endforeach

    <!-- Items -->
    @foreach($items as $item)
        @php
            $imgs = is_array($item->images) ? $item->images : (is_string($item->image) ? [$item->image] : []);
            $firstImg = $imgs[0] ?? null;
        @endphp
        <url>
            <loc>{{ url('/item/' . $item->id) }}</loc>
            <lastmod>{{ $item->updated_at ? $item->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.85</priority>
            @if($firstImg)
                <image:image>
                    <image:loc>{{ asset('uploads/items/' . $firstImg) }}</image:loc>
                    <image:title>{{ $item->title }}</image:title>
                </image:image>
            @endif
        </url>
    @endforeach

    <!-- Auction Products -->
    @foreach($auctions as $auc)
        @php
            $aucImgs = is_array($auc->image) ? $auc->image : [$auc->image];
            $aucImg = $aucImgs[0] ?? null;
        @endphp
        <url>
            <loc>{{ url('/auction/' . $auc->id) }}</loc>
            <lastmod>{{ $auc->updated_at ? $auc->updated_at->format('Y-m-d') : date('Y-m-d') }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>0.85</priority>
            @if($aucImg)
                <image:image>
                    <image:loc>{{ asset('uploads/auction_products/' . $aucImg) }}</image:loc>
                    <image:title>{{ $auc->title }}</image:title>
                </image:image>
            @endif
        </url>
    @endforeach

</urlset>
