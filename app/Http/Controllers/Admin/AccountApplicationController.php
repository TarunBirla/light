<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountApplication;

class AccountApplicationController extends Controller
{
    /**
     * Display a listing of account opening applications
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $query = AccountApplication::latest();

        if ($status && in_array($status, ['pending', 'under_review', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('contact_person_name', 'like', "%{$search}%")
                  ->orWhere('full_legal_name', 'like', "%{$search}%")
                  ->orWhere('trading_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('company_registration_no', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(15)->withQueryString();

        $counts = [
            'all'          => AccountApplication::count(),
            'pending'      => AccountApplication::where('status', 'pending')->count(),
            'under_review' => AccountApplication::where('status', 'under_review')->count(),
            'approved'     => AccountApplication::where('status', 'approved')->count(),
            'rejected'     => AccountApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.account-applications.index', compact('applications', 'counts', 'status', 'search'));
    }

    /**
     * Show details of a single account application
     */
    public function show($id)
    {
        $application = AccountApplication::findOrFail($id);
        return view('admin.account-applications.show', compact('application'));
    }

    /**
     * Update application status & admin notes
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'      => 'required|in:pending,under_review,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $application = AccountApplication::findOrFail($id);
        $application->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Application status updated to ' . ucfirst(str_replace('_', ' ', $request->status)) . ' successfully.');
    }

    /**
     * Delete an account application
     */
    public function destroy($id)
    {
        $application = AccountApplication::findOrFail($id);
        $application->delete();

        return redirect()->route('admin.account-applications.index')->with('success', 'Account application deleted successfully.');
    }
}
