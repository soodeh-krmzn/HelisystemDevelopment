<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function loginStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!Auth::attempt($validated)) {
            return back()->withErrors(['auth' => 'اطلاعات وارد شده صحیح نمی باشد.']);
        }

        return redirect()->route('dashboard');
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function registerStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required',
            'family' => 'required',
            'mobile' => 'required|numeric|regex:/(09)[0-9]{9}/|unique:admins,mobile',
            'password' => 'required|confirmed'
        ]);

        $user = Admin::create([
            'name' => $validated['name'],
            'family' => $validated['family'],
            'mobile' => $validated['mobile'],
            'username' => $validated['mobile'],
            'password' => $validated['password']
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout(): RedirectResponse
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return redirect()->route('login');
    }
}
