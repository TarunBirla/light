<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Auction Request Submitted</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f7; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background: #111111; color: #ffffff; padding: 25px 30px; text-align: center; }
        .header h2 { margin: 0; font-size: 22px; color: #FFC700; font-weight: 700; }
        .content { padding: 30px; }
        .badge-bid { display: inline-block; background: #FFC700; color: #111; padding: 6px 14px; font-weight: 700; font-size: 16px; border-radius: 20px; margin-top: 5px; }
        .table-details { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-details th, .table-details td { padding: 12px 15px; border-bottom: 1px solid #eeeeee; text-align: left; font-size: 14px; }
        .table-details th { background-color: #fcfcfc; color: #666; font-weight: 600; width: 35%; }
        .footer { background-color: #fafafa; padding: 15px 30px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eeeeee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><span style="color:#ffffff;">Light As Air</span> — New Auction Bid Request</h2>
        </div>
        <div class="content">
            <p style="font-size: 15px; margin-top:0;">Hello Admin,</p>
            <p style="font-size: 14px; color: #555;">A new auction request/bid has been submitted on the website. Below are the details:</p>

            <table class="table-details">
                <tr>
                    <th>Product Title</th>
                    <td><strong>{{ $auctionRequest->auctionProduct->title ?? 'N/A' }}</strong></td>
                </tr>
                <tr>
                    <th>Minimum Price</th>
                    <td>£{{ number_format($auctionRequest->auctionProduct->minprice ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <th>Offered Bid Price</th>
                    <td><span class="badge-bid">£{{ number_format($auctionRequest->bid_price, 2) }}</span></td>
                </tr>
                <tr>
                    <th>Requested Quantity</th>
                    <td><strong>{{ $auctionRequest->qty }}</strong></td>
                </tr>
                <tr>
                    <th>Customer Name</th>
                    <td><strong>{{ $auctionRequest->name }}</strong></td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><a href="mailto:{{ $auctionRequest->email }}" style="color:#0066cc;">{{ $auctionRequest->email }}</a></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><a href="tel:{{ $auctionRequest->phone }}" style="color:#0066cc;">{{ $auctionRequest->phone }}</a></td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $auctionRequest->address ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Message / Notes</th>
                    <td>{{ $auctionRequest->message ?: 'No message provided' }}</td>
                </tr>
                <tr>
                    <th>Submission Date</th>
                    <td>{{ $auctionRequest->created_at ? $auctionRequest->created_at->format('d M Y, h:i A') : date('d M Y, h:i A') }}</td>
                </tr>
            </table>

            <div style="margin-top: 25px; text-align: center;">
                <a href="{{ url('/admin/auction-requests') }}" style="background: #111111; color: #FFC700; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: 700; display: inline-block;">
                    View in Admin Panel
                </a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Light As Air. All rights reserved.
        </div>
    </div>
</body>
</html>
