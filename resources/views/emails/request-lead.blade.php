<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Equipment Request</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111; line-height: 1.6; padding: 20px; background-color: #f4f4f5;">

    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e4e4e7; border-radius: 12px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        <h2 style="margin-top: 0; color: #111; font-size: 20px; border-bottom: 2px solid #FFC700; padding-bottom: 10px;">
            🔥 New Equipment Request
        </h2>

        <div style="margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; background: {{ ($lead['product_type'] ?? '') == 'Selling Request' ? '#EDFAF0' : '#EBF5FF' }}; border: 1px solid {{ ($lead['product_type'] ?? '') == 'Selling Request' ? '#86efac' : '#93c5fd' }};">
            <strong style="font-size: 16px; color: {{ ($lead['product_type'] ?? '') == 'Selling Request' ? '#166534' : '#1d4ed8' }};">
                Request Type: {{ $lead['product_type'] ?? 'Rental Request' }}
            </strong>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="padding: 8px 0; font-weight: bold; width: 130px; color: #555;">Name:</td>
                <td style="padding: 8px 0; font-size: 15px;">{{ $lead['name'] }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; color: #555;">Email:</td>
                <td style="padding: 8px 0; font-size: 15px;">{{ $lead['email'] }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold; color: #555;">Phone:</td>
                <td style="padding: 8px 0; font-size: 15px;">{{ $lead['phone'] }}</td>
            </tr>
            @if(!empty($lead['message']))
            <tr>
                <td style="padding: 8px 0; font-weight: bold; color: #555;">Message:</td>
                <td style="padding: 8px 0; font-size: 15px;">{{ $lead['message'] }}</td>
            </tr>
            @endif
        </table>

        <div style="background: #f9fafb; padding: 16px; border-radius: 8px; border: 1px solid #f3f4f6;">
            <strong style="display: block; margin-bottom: 8px; font-size: 15px; color: #111;">Requested Items:</strong>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($lead['items'] as $item)
                    <li style="margin-bottom: 4px; font-size: 14px; color: #333;">{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>

</body>
</html>