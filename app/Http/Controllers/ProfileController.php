<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Booking;

class ProfileController extends Controller
{
    public function showPelangganProfile()
    {
        $user = Auth::user();
        $recentBookings = Booking::where('user_id', $user->id)
            ->with('barbershop')
            ->latest()
            ->take(5)
            ->get();

        return view('profile.pelanggan', compact('user', 'recentBookings'));
    }

    public function showMitraProfile()
    {
        $user = Auth::user();
        $barbershops = $user->barbershops;
        $barbershopIds = $barbershops->pluck('id');

        $stats =[
            // hitung total booking yang pernah dilakukan di semua barbershop milik mitra
            'total_bookings'=>Booking::whereIn('barbershop_id', $barbershopIds)->count(),

            //hitung total pendapatan semua booking yang sudah selesai
            'total_earnings'=>Booking::whereIn('barbershop_id', $barbershopIds)
            ->where('status', 'Selesai')
            ->sum('total_price'),

        ];
        return view('profile.mitra', compact('user', 'stats'));
    }

    public function showEditForm()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20|unique:users,phone_number,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;

        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        //redirect ke halaman yang sesuai berdasarkan peran pengguna
        $routeName = $user->role =='mitra' ? 'profile.mitra.show' : 'profile.pelanggan.show';
        return redirect()->route($routeName)->with('success', 'Update profile berhasil.');
    }
}
