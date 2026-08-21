<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Equipment Request Notification</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111; line-height: 1.6; padding: 20px; background-color: #f4f4f5;">

    <div style="max-width: 650px; margin: 0 auto; background: #ffffff; border: 1px solid #e4e4e7; border-radius: 12px; padding: 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="border-bottom: 3px solid #FFC700; padding-bottom: 12px; margin-bottom: 20px;">
            <h2 style="margin: 0; color: #111; font-size: 22px;">
                🎬 New Equipment Request #{{ $equipmentRequest->id }}
            </h2>
            <div style="font-size: 14px; color: #666; margin-top: 4px;">
                Production Title: <strong style="color: #111;">{{ $equipmentRequest->production_title }}</strong>
            </div>
        </div>

        <!-- 1. Production Information -->
        <h3 style="font-size: 16px; color: #111; margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 6px;">
            📋 Production Information
        </h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; font-weight: bold; width: 160px; color: #555;">Gaffer:</td>
                <td style="padding: 6px 0;">{{ $equipmentRequest->gaffer ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #555;">Email:</td>
                <td style="padding: 6px 0;">{{ $equipmentRequest->email ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #555;">Contact Phone:</td>
                <td style="padding: 6px 0;">{{ $equipmentRequest->contact ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #555;">Production Co.:</td>
                <td style="padding: 6px 0;">{{ $equipmentRequest->production_company ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #555;">Production Title:</td>
                <td style="padding: 6px 0; font-weight: bold; color: #111;">{{ $equipmentRequest->production_title ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #555;">Production Contact:</td>
                <td style="padding: 6px 0;">{{ $equipmentRequest->production_contact ?: 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #555;">DoP:</td>
                <td style="padding: 6px 0;">{{ $equipmentRequest->dop ?: 'N/A' }}</td>
            </tr>
        </table>

        <!-- 2. Production Dates -->
        <h3 style="font-size: 16px; color: #111; margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 6px;">
            📅 Production Dates
        </h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
            <tr>
                <td style="padding: 6px 0; font-weight: bold; width: 160px; color: #555;">Rig Dates:</td>
                <td style="padding: 6px 0;">
                    {{ $equipmentRequest->rig_from ? \Carbon\Carbon::parse($equipmentRequest->rig_from)->format('d/m/Y') : 'N/A' }} 
                    to 
                    {{ $equipmentRequest->rig_to ? \Carbon\Carbon::parse($equipmentRequest->rig_to)->format('d/m/Y') : 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #555;">Prelight Dates:</td>
                <td style="padding: 6px 0;">
                    {{ $equipmentRequest->prelight_from ? \Carbon\Carbon::parse($equipmentRequest->prelight_from)->format('d/m/Y') : 'N/A' }} 
                    to 
                    {{ $equipmentRequest->prelight_to ? \Carbon\Carbon::parse($equipmentRequest->prelight_to)->format('d/m/Y') : 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #555;">Shoot Dates:</td>
                <td style="padding: 6px 0; font-weight: bold; color: #166534;">
                    {{ $equipmentRequest->shoot_from ? \Carbon\Carbon::parse($equipmentRequest->shoot_from)->format('d/m/Y') : 'N/A' }} 
                    to 
                    {{ $equipmentRequest->shoot_to ? \Carbon\Carbon::parse($equipmentRequest->shoot_to)->format('d/m/Y') : 'N/A' }}
                </td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: bold; color: #555;">Derig Dates:</td>
                <td style="padding: 6px 0;">
                    {{ $equipmentRequest->derig_from ? \Carbon\Carbon::parse($equipmentRequest->derig_from)->format('d/m/Y') : 'N/A' }} 
                    to 
                    {{ $equipmentRequest->derig_to ? \Carbon\Carbon::parse($equipmentRequest->derig_to)->format('d/m/Y') : 'N/A' }}
                </td>
            </tr>
        </table>

        <!-- 3. Location Address -->
        <h3 style="font-size: 16px; color: #111; margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 6px;">
            📍 Location Address
        </h3>
        <div style="background: #f9fafb; padding: 12px 16px; border-radius: 8px; border: 1px solid #f3f4f6; margin-bottom: 20px; font-size: 14px;">
            {{ $equipmentRequest->location_address ?: 'N/A' }}
        </div>

        <!-- 4. Selected Products & Quantities -->
        <h3 style="font-size: 16px; color: #111; margin-top: 20px; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 6px;">
            📦 Requested Equipment List
        </h3>
        
        @php
            $groupedItems = $equipmentRequest->items->groupBy(function($item) {
                return $item->category ? $item->category->name : 'Other / Uncategorized';
            });
        @endphp

        @foreach($groupedItems as $catName => $items)
            <div style="margin-bottom: 16px; border: 1px solid #e4e4e7; border-radius: 8px; overflow: hidden;">
                <div style="background: #111; color: #FFC700; padding: 8px 14px; font-weight: bold; font-size: 14px;">
                    📂 {{ $catName }}
                </div>
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    @foreach($items as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 14px; color: #333;">
                                {{ $item->product ? $item->product->title : 'Product #' . $item->product_id }}
                            </td>
                            <td style="padding: 8px 14px; text-align: right; font-weight: bold; width: 100px; color: #111;">
                                Qty: {{ $item->quantity }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach

        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #eee; text-align: center; font-size: 12px; color: #888;">
            Sent automatically from Light As Air Equipment Request System
        </div>

    </div>

</body>
</html>
