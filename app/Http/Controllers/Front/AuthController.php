<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('front.auth.login');
    }

    public function register()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('front.auth.register');
    }

    public function registerSubmit(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'phone'      => 'required|string|max:50',
            'address'    => 'required|string|max:500',
            'password'   => 'required|string|min:6',
            'terms'      => 'required|accepted',
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required'  => 'Last name is required.',
            'email.required'       => 'Email address is required.',
            'email.unique'         => 'This email address is already registered.',
            'phone.required'       => 'Phone number is required.',
            'address.required'     => 'Address is required.',
            'password.required'    => 'Password is required.',
            'password.min'         => 'Password must be at least 6 characters.',
            'terms.accepted'       => 'You must agree to the Terms and Conditions.',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'name'       => trim($request->first_name . ' ' . $request->last_name),
            'email'      => $request->email,
            'phone'      => $request->phone,
            'mobile'     => $request->phone,
            'address'    => $request->address,
            'password'   => Hash::make($request->password),
            'role'       => 'user',
            'status'     => 'active',
        ]);

        Auth::login($user);

        return redirect()->intended('/auctions')->with('success', 'Account registered successfully! Welcome to Light As Air.');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();
                return back()->with('error', 'Your account has been suspended by Admin. Please contact support.');
            }

            if ($user->status === 'inactive') {
                Auth::logout();
                return back()->with('error', 'Your account is currently inactive. Please contact support.');
            }

            return redirect()->intended('/auctions')->with('success', 'Logged in successfully!');
        }

        return back()->with('error', 'Invalid email or password credentials.')->withInput($request->only('email'));
    }

    public function profile()
    {
        return view('front.auth.profile');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}