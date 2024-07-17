<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function index() {
         if (Auth::check()) {
            return redirect()->route('dashboard');
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
        } else {
            return redirect()->back()->with('failed', "Email atau Kata Sandi Salah");
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
            return redirect()->route('dashboard.profile')->with('success', "Berhasil memperbarui profil");
        } else {
            return redirect()->route('dashboard.profile')->with('failed', "Gagal memperbarui profil");
        }
    }

    public function change_password() {
        return view('dashboard.profile.password');
    }

    public function post_change_password(Request $request) {
        $validate = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);

        if ($validate['new_password'] != $validate['confirm_password']) {
            return redirect()->route('dashboard.change_password')->with('failed', "Kata sandi baru dan konfirmasi kata sandi berbeda");
        }

        $data = User::findOrFail(Auth::user()->id);

        if (!Hash::check($validate['old_password'], $data->password)) {
            return redirect()->route('dashboard.change_password')->with('failed', "Kata sandi lama berbeda");
        }

        $update = $data->update([
            'password' => Hash::make($validate['new_password']),
        ]);

        if ($update) {
            return redirect()->route('dashboard.change_password')->with('success', "Berhasil merubah kata sandi");
        } else {
            return redirect()->route('dashboard.change_password')->with('failed', "Gagal merubah kata sandi");
        }
    }
}
