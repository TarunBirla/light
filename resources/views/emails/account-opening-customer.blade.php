<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Opening Application Received</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f7; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
        .header { background: #111111; color: #ffffff; padding: 25px 30px; text-align: center; border-bottom: 4px solid #FFC700; }
        .header h2 { margin: 0; font-size: 22px; color: #FFC700; font-weight: 700; }
        .content { padding: 30px; }
        .info-box { background: #FFF9E6; border-left: 4px solid #FFC700; padding: 15px 20px; border-radius: 6px; margin: 20px 0; font-size: 14px; }
        .table-details { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table-details th, .table-details td { padding: 10px 12px; border-bottom: 1px solid #eeeeee; text-align: left; font-size: 13px; vertical-align: top; }
        .table-details th { background-color: #fcfcfc; color: #666; font-weight: 600; width: 38%; }
        .footer { background-color: #fafafa; padding: 20px 30px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eeeeee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Light As AIR</h2>
            <div style="color: #ffffff; font-size: 14px; margin-top: 5px;">Account Opening Application Confirmation</div>
        </div>
        <div class="content">
            <p style="font-size: 16px; margin-top:0;">Dear <strong>{{ $application->contact_person_name }}</strong>,</p>
            <p style="font-size: 14px; color: #555; line-height: 1.6;">
                Thank you for applying to open an account with <strong>Light As Air</strong>. We have received your application successfully. Our team will review your details and contact you shortly.
            </p>

            <div class="info-box">
                <strong>Application Reference:</strong> #{{ $application->id }}<br>
                <strong>Status:</strong> Under Review
            </div>

            <h4 style="margin-bottom: 10px; color: #111; border-bottom: 2px solid #FFC700; padding-bottom: 5px; display: inline-block;">
                Copy of Submitted Details
            </h4>

            <table class="table-details">
                <tr>
                    <th>Full Legal Name</th>
                    <td><strong>{{ $application->full_legal_name }}</strong></td>
                </tr>
                <tr>
                    <th>Trading Name</th>
                    <td>{{ $application->trading_name ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Type of Business</th>
                    <td>{{ $application->business_type ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Company Registration No.</th>
                    <td>{{ $application->company_registration_no ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <th>VAT Number</th>
                    <td>{{ $application->vat_number ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Registered Address</th>
                    <td>{{ $application->registered_address }}</td>
                </tr>
                <tr>
                    <th>Trading Address</th>
                    <td>{{ $application->trading_address ?: 'Same as registered address' }}</td>
                </tr>
                <tr>
                    <th>Telephone</th>
                    <td>{{ $application->telephone }}</td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td>{{ $application->email }}</td>
                </tr>
                @if($application->website)
                <tr>
                    <th>Website</th>
                    <td>{{ $application->website }}</td>
                </tr>
                @endif
            </table>

            <p style="font-size: 13px; color: #777; margin-top: 25px;">
                If you have any urgent queries, please feel free to reach out to us at <a href="mailto:info@lightasair.co.uk" style="color: #111; font-weight: 600;">info@lightasair.co.uk</a> or via telephone at <a href="tel:+4401932553284" style="color: #111; font-weight: 600;">+44 01932 55 3284</a>.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Light As Air. Official Godox KNOWLED Dealer for Cinematic Lighting.<br>
            All rights reserved.
        </div>
    </div>
</body>
</html>
