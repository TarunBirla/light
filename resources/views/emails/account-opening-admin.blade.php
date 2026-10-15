<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Account Opening Application</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f7; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); }
        .header { background: #111111; color: #ffffff; padding: 25px 30px; text-align: center; border-bottom: 4px solid #FFC700; }
        .header h2 { margin: 0; font-size: 22px; color: #FFC700; font-weight: 700; }
        .content { padding: 30px; }
        .badge { display: inline-block; background: #FFC700; color: #111; padding: 4px 12px; font-weight: 700; font-size: 13px; border-radius: 12px; }
        .table-details { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-details th, .table-details td { padding: 12px 14px; border-bottom: 1px solid #eeeeee; text-align: left; font-size: 14px; vertical-align: top; }
        .table-details th { background-color: #fcfcfc; color: #666; font-weight: 600; width: 38%; }
        .footer { background-color: #fafafa; padding: 15px 30px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eeeeee; }
        .btn { background: #FFC700; color: #111111; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: 700; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Light As AIR</h2>
            <div style="color: #ffffff; font-size: 14px; margin-top: 5px;">New Account Opening Application Received</div>
        </div>
        <div class="content">
            <p style="font-size: 15px; margin-top:0;">Hello Admin,</p>
            <p style="font-size: 14px; color: #555;">A new account opening application has been submitted through the website. Here are the details:</p>

            <table class="table-details">
                <tr>
                    <th>Application ID</th>
                    <td><span class="badge">#{{ $application->id }}</span></td>
                </tr>
                <tr>
                    <th>Contact Person Name</th>
                    <td><strong>{{ $application->contact_person_name }}</strong></td>
                </tr>
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
                    <td><strong>{{ $application->business_type ?: 'Not specified' }}</strong></td>
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
                    <td><a href="tel:{{ $application->telephone }}" style="color:#0066cc;">{{ $application->telephone }}</a></td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><a href="mailto:{{ $application->email }}" style="color:#0066cc;">{{ $application->email }}</a></td>
                </tr>
                <tr>
                    <th>Website</th>
                    <td>
                        @if($application->website)
                            <a href="{{ $application->website }}" target="_blank" style="color:#0066cc;">{{ $application->website }}</a>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Submitted On</th>
                    <td>{{ $application->created_at ? $application->created_at->format('d M Y, h:i A') : date('d M Y, h:i A') }}</td>
                </tr>
            </table>

            <div style="margin-top: 30px; text-align: center;">
                <a href="{{ url('/admin/account-applications/' . $application->id) }}" class="btn">
                    View & Manage in Admin Panel
                </a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Light As Air. All rights reserved.
        </div>
    </div>
</body>
</html>
