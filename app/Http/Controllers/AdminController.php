<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        $transaksi = Transaksi::all();
        return view('admin.index', compact('user', 'transaksi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users', //Tidak boleh ada user lain yang sudah memakai email itu.Kalau ada, maka akan gagal validasi.
            'role' => 'required',
            'password' => 'required',
            'name' => 'required'
        ]);

        User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        return redirect()->route('admin.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = User::find($id);
        return view('admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, $id)
    {
        $user = User::find($id);
        $request->validate([
            'email' => 'required|email|unique:users,name',
            'role' => 'required|string',
            'password' => 'nullable|max:8',
            'name' => 'required|string'
        ]);

        $user->update([
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role,
            'name' => $request->name,
        ]);
        $user->save();
        return redirect()->route('admin.index')->with(['success' => 'Data Berhasil Di Ubah!']);
    }

    public function confirmTopup($id)
    {
        $transaksi = Transaksi::findOrFail($id); // Cari transaksi berdasarkan ID

        // Pastikan transaksi adalah topup dan belum dikonfirmasi
        if ($transaksi->tipe_transaksi === 'topup' && !$transaksi->confirmed) {
            // Tambahkan saldo ke user
            $user = User::find($transaksi->sender_id);
            $user->update(['balance' => $user->balance + $transaksi->amount]);

            // Tandai transaksi sebagai dikonfirmasi
            $transaksi->update(['confirmed' => true]);

            return redirect()->route('admin.index')->with('success', 'Topup telah dikonfirmasi.');
        }
        
        return redirect()->route('admin.index')->with('error', 'Transaksi tidak valid atau sudah dikonfirmasi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = User::findOrfail($id);
        $users->delete();

        return redirect()->route('admin.index');
    }
}
