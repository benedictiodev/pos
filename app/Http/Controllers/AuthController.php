<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function index() {
         if (Auth::check()) {
            return redirect()->route('/dashboard');
        } else {
            return view('auth.login');
        }
    }

    function post_login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function profile() {
        return view('dashboard.profile.edit');
    }

    public function post_profile(Request $request) {
        $validate = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'],
        ]);

        $data = User::findOrFail(Auth::user()->id);

        $update = $data->update([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        if ($update) {
            return redirect()->route('dashboard.profile')->with('success', "Successfully to update profile");
        } else {
            return redirect()->route('dashboard.profile')->with('failed', "Failed to update profile");
        }
    }
}
