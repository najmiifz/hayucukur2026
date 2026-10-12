<?php

namespace App\Http\Controllers;

use App\Models\Barbershop;
use Illuminate\Http\Request;

class BarbershopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //Filter berdasarkan kota
        $query = Barbershop::query();
        if ($request->filled('kota')){
            $query->where('location', $request->kota);
        };
        //Filter berdasarkan layanan
        if ($request->filled('layanan')){
            $query->whereJsonContains('services', ['name' => $request->layanan]);
        }
        //Filter dari hasil pencarian
        $barbershops = $query->latest()->get();

        //menampilkan barbershop dengan jumlah rating
        $barbershops = $query
                    ->withCount('ratings')
                    ->latest()
                    ->get();

        // Ambil semua lokasi unik dari kolom 'location'
        $locations = Barbershop::select('location')->distinct()->pluck('location');

        // Ambil semua layanan unik dari kolom 'services'
        $allServices = Barbershop::pluck('services')->flatMap(function ($services) {
            // Cek bila services adalah array
            if (is_array($services)) {
                return collect($services)->pluck('name');
            }
            return []; // mengembalikan array kosong jika services bukan array
        })->unique()->sort();

        return view('dashboard', compact('barbershops', 'locations', 'allServices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'location' => 'required|stringmax:255',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'phone_number' => 'nullable|string|max:20',
            'service_name.*' => 'required|string|max:255',
            'service_price.*' => 'required|integer|',
            'service_duration.*' => 'required|integer',

        ]);

        $imagePath = $request->filr('image')->store('barbershop_images', 'public');

        $services = [];
        if ($request->has('service_name')) {
            foreach ($request->service_name as $index => $name) {
                $services[] =[
                    'name' => $name,
                    'price' => $request->service_price[$index],
                    'duration' => $request->service_duration[$index],
                ];
            }
        }
        Barbershop::create([
            'name' => $validatedData['name'],
            'address' => $validatedData['address'],
            'location' => $validatedData['location'],
            'open_time' => $validatedData['open_time'],
            'close_time' => $validatedData['close_time'],
            'description' => $validatedData['description'],
            'phone_number' => $validatedData['phone_number'],
            'image' => $imagePath,
            'services' => $services,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('mitra.barbershops.index')->with('success', 'Barbershop baru berhasil ditambahkan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Barbershop $barbershop)
    {
        return view('barbershop.show', compact('barbershop'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barbershop $barbershop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barbershop $barbershop)
    {
        //1. Validasi data yang diterima
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'nullable|string|max:20',
            'service_name.*' => 'reqruied|string|max:255',
            'service_price.*' => 'required|integer',
            'service_duration.*' => 'required|integer',
        ]);
        //Siapkan data untuk update
        $updateData = [
            'name' => $validatedData['name'],
            'phone_number' => $validatedData['phone_number'],
        ];
        //2. Cek apakah ada gambar baru yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            $updateData['image'] = $request->file('image')->store('barbershop_images', 'public');
        }
        //3. Cek apakah ada layanan baru yang ditambahkan
        $services =[];
        if($request->has('service_name')) {
            foreach ($request->service_name as $index => $name) {
                $services[] =[
                    'name' => $name,
                    'price' => $request->service_price[$index],
                    'duration' => $request->service_duration[$index],
                ];
            }
        }
        //4. Update Record Barbershop
        $barbershop->update($updateData);

        //5. Redirect ke halaman index dengan pesan sukses
        return back()->with('success', 'Barbershop berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barbershop $barbershop)
    {
        //
    }
}
