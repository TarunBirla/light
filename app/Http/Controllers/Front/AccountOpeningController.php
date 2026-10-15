<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountApplication;
use App\Mail\AccountOpeningAdminMail;
use App\Mail\AccountOpeningCustomerMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AccountOpeningController extends Controller
{
    /**
     * Show Account Opening Form
     */
    public function create()
    {
        return view('front.account-opening');
    }

    /**
     * Handle form submission & send emails to admin and customer
     */
    public function store(Request $request)
    {
        $request->validate([
            'contact_person_name'     => 'required|string|max:255',
            'full_legal_name'         => 'required|string|max:255',
            'trading_name'            => 'nullable|string|max:255',
            'business_type'           => 'required|string|in:Sole Trader,Partnership,Ltd Co,Other',
            'company_registration_no' => 'nullable|string|max:100',
            'vat_number'              => 'nullable|string|max:100',
            'registered_address'      => 'required|string|max:1000',
            'trading_address'         => 'nullable|string|max:1000',
            'telephone'               => 'required|string|max:50',
            'email'                   => 'required|email|max:255',
            'website'                 => 'nullable|string|max:255',
        ], [
            'contact_person_name.required' => 'Contact Person Name is required.',
            'full_legal_name.required'     => 'Full Legal Name is required.',
            'business_type.required'       => 'Please select the Type of Business.',
            'registered_address.required'  => 'Registered Address is required.',
            'telephone.required'           => 'Telephone number is required.',
            'email.required'               => 'Email address is required.',
            'email.email'                  => 'Please enter a valid email address.',
        ]);

        $application = AccountApplication::create([
            'contact_person_name'     => $request->contact_person_name,
            'full_legal_name'         => $request->full_legal_name,
            'trading_name'            => $request->trading_name,
            'business_type'           => $request->business_type,
            'company_registration_no' => $request->company_registration_no,
            'vat_number'              => $request->vat_number,
            'registered_address'      => $request->registered_address,
            'trading_address'         => $request->trading_address,
            'telephone'               => $request->telephone,
            'email'                   => $request->email,
            'website'                 => $request->website,
            'status'                  => 'pending',
        ]);

        // 1. Send Email to Admin (mohammednasar.uk@gmail.com)
        try {
            Mail::to('mohammednasar.uk@gmail.com')->send(new AccountOpeningAdminMail($application));
        } catch (\Exception $e) {
            Log::error('Admin Account Opening Email Failed: ' . $e->getMessage());
        }

        // 2. Send Confirmation Email to Customer
        try {
            if (!empty($application->email)) {
                Mail::to($application->email)->send(new AccountOpeningCustomerMail($application));
            }
        } catch (\Exception $e) {
            Log::error('Customer Account Opening Email Failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Thank you! Your Account Opening application has been submitted successfully. A confirmation email has been sent to your email address.');
    }
}
