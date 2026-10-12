<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MitraDashboardController extends Controller
{
    public function index()
    {
        $barbershops = Auth::user()->barbershops; // Ambil barbershop milik mitra yang sedang login
        $barbershopIds = $barbershops->pluck('id'); // Ambil ID barbershop untuk digunakan dalam query

        // Inisialisasi variabel untuk statistik
        $totalBookings = 0;
        $todayBookings = 0;
        $totalPendapatanHariIni = 0;

        if ($barbershopIds->isNotEmpty()) {
            //hitung total booking untuk semua barbershop milik mitra
            $totalBookings = Booking::whereIn('barbershop_id', $barbershopIds)->count();

            //hitung booking hari ini
            $todayBookings = Booking::whereIn('barbershop_id', $barbershopIds)
            ->whereDate('booking_time', Carbon::today())
            ->count();

            //Hitung total pendapatan hari ini dari status 'Selesai'
            $totalPendapatanHariIni = Booking::whereIn('barbershop_id', $barbershopIds)
            ->where('status', 'Selesai')
            ->whereDate('completed_at', Carbon::today())
            ->sum('total_price'); // Secara langsung jumlahkan total harga dari booking yang selesai hari ini
        }
        // Kembalikan view dengan data yang diperlukan
        return view('dashboard-mitra', compact(
                'barbershops',
                'totalBookings',
                'todayBookings',
                'totalPendapatanHariIni'
        ));
    }

    public function create()
    {
        return view('kelola-barber');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'location' => 'required|string',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'service_name.*' => 'sometimes|string',
            'service_price.*' => 'sometimes|numeric|min:0',
            'service_duration.*' => 'sometimes|integer|min:1',
        ]);
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('barbershop_images', 'public')
            : null;

        $services = [];
        if($request->has('service_name')){
            foreach ($request->service_name as $key => $name){
                if (isset($request->service_price[$key]) && isset($request->service_duration[$key])) {
                    $services[] = [
                        'name' => $name,
                        'price' => $request->service_price[$key],
                        'duration' => $request->service_duration[$key] ?? 30,
                    ];
                }
            }
        }

        $barbershop = Barbershop::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'location' => $request->location,
            'description' => $request->description,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
            'image' => $imagePath,
            'services' => $services,
        ]);
        return redirect()->route('mitra.barbershops.index')->with('success', 'Barbershop baru berhasil ditambahkan!');
    }

    public function edit(Barbershop $barbershop)
    {
        if ($barbershop->user_id !== Auth::id()){ // Cek apakah barbershop milik mitra yang sedang login
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit barbershop ini.');
        }
        return view ('detail-barber-mitra', compact('barbershop')); // Tampilkan halaman edit barbershop
    }

    public function update(Request $request, Barbershop $barbershop)
    {
        if ($barbershop->user_id !== Auth::id()){ // Cek apakah barbershop milik mitra yang sedang login
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit barbershop ini.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'location' => 'required|string',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'service_name.*' => 'sometimes|string',
            'service_price.*' => 'sometimes|numeric|min:0',
            'service_duration.*' => 'sometimes|integer|min:1',
        ]);
        $data = $request->except(['_token','_method','image','service_name','service_price','service_duration']);
        if ($request->hasFile('image')) {
            if($barbershop->image) // Cek apakah barbershop sudah memiliki gambar
                Storage::disk('public')->delete($barbershop->image); // Hapus gambar lama jika ada
            $data['image'] = $request->file('image')->store('barbershop_images', 'public'); // Simpan gambar baru
        }
        $services = [];
        if($request->has('service_name')){
            foreach ($request->service_name as $key=> $name) {
                if (isset($request->service_price[$key])) {
                    $services[] = [
                        'name' => $name,
                        'price' => $request->service_price[$key],
                        'duration' => $request->service_duration[$key] ?? 30,
                    ];
                }
            }
        }
        $data['services'] = $services; // Simpan layanan yang ditawarkan
        $barbershop->update($data); // Update barbershop dengan data baru

        return redirect()->route('mitra.barbershops.index', $barbershop->id)->with('success', 'Barbershop berhasil diperbarui!');
    }
    public function destroy(Barbershop $barbershop)
    {
        if ($barbershop->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk menghapus barbershop ini.');
        }
        if ($barbershop->image) {
            Storage::disk('public')->delete($barbershop->image); // Hapus gambar barbershop jika ada
        }
        $barbershop->delete(); // Hapus barbershop dari database
        return redirect()->route('mitra.barbershops.index')->with('success', 'Barbershop berhasil dihapus!');
    }
    public function barbershopIndex()
    {
        $barbershops = Auth::user()->barbershops;
        return view('mitra.barbershops.index', compact('barbershops'));
    }
    public function showBookings(){
        $barbershopsIds = Auth::user()->barbershops->pluck('id'); // Ambil semua barbershop milik mitra yang sedang login

        $bookings = Booking::whereIn('barbershop_id', $barbershopsIds)
                            ->with('user') // Mengambil relasi customer untuk menampilkan nama pelanggan
                            ->latest()
                            ->get(); // Ambil semua booking yang terkait dengan barbershop ini

        return view('booking-mitra', compact('bookings')); // Tampilkan daftar booking pada mitra
    }
    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $this->authorizeBookingManagement($booking); // memastikan mitra memiliki izin untuk mengelola booking ini

        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai,Dibatalkan',
        ]);
        $updateData = [
        'status' => $request->status,
    ];

        if ($request->status == 'Selesai') {
            $updateData['completed_at'] = Carbon::now(); // Set waktu penyelesaian booking ke sekarang
            if ($booking->payment_method === 'Bayar Ditempat') {
                $updateData['payment_status'] = 'Paid';
            }
        }
        $booking->update($updateData); // Update status booking

        return back()->with('success', 'Status booking berhasil diperbarui.'); // Kembali ke halaman sebelumnya dengan pesan sukses
    }

    private function authorizeBookingManagement(Booking $booking){
        $ownsBarbershop = Barbershop::where('user_id', Auth::id())
            ->whereKey($booking->barbershop_id)
            ->exists();
        if (!$ownsBarbershop){
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengedit Booking');
        }
    }


}

