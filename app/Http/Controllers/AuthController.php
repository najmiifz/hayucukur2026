<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister(string $role = 'pelanggan') {
        return view("register", ['role' => $role]); // Menampilkan halaman registrasi dengan role yang sesuai
    }
    public function register(Request $request) {
        $request->validate([
            'name'=> 'required',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|min:6|confirmed',
            'role' => 'required|in:pelanggan,mitra', // Validasi role
            'phone_number' => 'required|string|max:20',
        ]);

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'role' => $request->role, // Simpan role sesuai input
            'phone_number' => $request->phone_number,
        ]);

        Auth::login($user); // Login user setelah registrasi
        if ($request->role == 'mitra') {
            return redirect()->route('dashboard.mitra')->with('success', 'Registrasi Mitra Berhasil');
        }
        return redirect()->route('dashboard')->with('success', 'Registrasi Berhasil');

        // Jika ingin mengarahkan ke halaman login setelah registrasi, bisa uncomment baris berikut
        // return redirect()->route('login')->with('success','Registrasi Berhasil');
    }
    public function showLogin(Request $request){
        $role =$request->query('as', 'pelanggan'); // Ambil role dari query string, default 'pelanggan'
        return view('login', ['role' => $role]); // Tampilkan halaman login dengan role yang sesuai
    }
    public function login(Request $request) {
        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Regenerasi session untuk keamanan

            if (Auth::user()->role == 'mitra') {
                return redirect()->route('dashboard.mitra')->with('success', 'Login berhasil!');
            }
            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah. Silakan coba lagi.',
        ])->onlyInput('email');
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Berhasil Logout');
    }

    public function mitraRegister(Request $request) {
        $request->validate([
            'name'=> 'required',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|min:6|confirmed'
        ]);

        User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'role' => 'mitra'
        ]);
        return redirect()->route('dashboard.mitra')->with('success','Registrasi Mitra Berhasil');
    }
}
