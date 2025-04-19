<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi; // Pastikan model ini sudah sesuai dengan struktur tabel transaksi

class MutasiController extends Controller
{
    /**
     * Mutasi: Cetak PDF semua transaksi milik user yang sedang login.
     */
    public function userAll()
    {
        // Ambil transaksi dimana user login sebagai pengirim atau penerima.
        $transactions = Transaksi::where(function($query) {
            $query->where('sender_id', Auth::id())
                  ->orWhere('receiver_id', Auth::id());
        })->latest()->get();

        // Load view PDF dan kirim data transaksi.
        $pdf = PDF::loadView('mutasi.pdf', compact('transactions'));

        // Download file PDF dengan nama "mutasi_user.pdf"
        return $pdf->download('mutasi_user.pdf');
    }

    /**
     * Mutasi: Cetak PDF detail satu transaksi milik user.
     *
     * @param int $id  ID transaksi
     */
    public function userSingle($id)
    {
        // Ambil transaksi sesuai ID dan pastikan transaksi tersebut
        // adalah milik user yang sedang login (sebagai sender atau receiver)
        $transaction = Transaksi::where('id', $id)
            ->where(function($query) {
                $query->where('sender_id', Auth::id())
                      ->orWhere('receiver_id', Auth::id());
            })->firstOrFail();

        $pdf = PDF::loadView('mutasi.pdf_single', compact('transaction'));
        return $pdf->download('mutasi_single.pdf');
    }

    /**
     * Mutasi: Cetak PDF semua transaksi untuk admin atau bank.
     * Pastikan route ini dilindungi oleh middleware untuk role admin atau bank.
     */
    public function mutasiAll()
    {
        // Ambil semua transaksi (jika ingin penyaringan khusus, bisa dikembangkan)
        $transactions = Transaksi::latest()->get();
        $pdf = PDF::loadView('mutasi.pdf_all', compact('transactions'));
        return $pdf->download('mutasi_all.pdf');
    }
}
