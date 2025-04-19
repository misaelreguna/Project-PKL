<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use function Laravel\Prompts\alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->where('role', 'user')->get();
        $transactions = Transaksi::latest()->get();
        return view ('bank.index', compact('users', 'transactions') );
         
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
        $request->validate([
            'email'=> 'required|unique:users', //Tidak boleh ada user lain yang sudah memakai email itu.Kalau ada, maka akan gagal validasi.
            'password' => 'required ',
            'name'=> 'required'
        ]);

        User::create ([
            'email'=> $request->email,
            'role'=> 'user',
            'password'=> $request->password,
            'name'=> $request->name
        ]);
        return redirect()->route ('bank.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function tariktunai (Request $request){
        $targetUser = User::find($request->user_id); // disini user_id dimaksudkan name dalam tampilan view bank
        if($request-> amount < 1){
            return redirect()->route('bank.index')->alert('topup minimal Rp 1');
        }

        if($targetUser->balance < $request->amount){
            return redirect()->route('bank.index')->alert('saldo tidak cukup');
        }
        Transaksi::create([
            'sender_id'=>$targetUser->id,
            'tipe_transaksi'=>'tarik',
            'confirmed'=> true,
            'amount'=>$request->amount
        ]);
        // kurangkan saldo user
        $targetUser->update(['balance'=> $targetUser->balance - $request->amount]);
        return redirect()->route('bank.index')->with('success','withdrawl berhasil');
    }

    public function topup(Request $request, $id){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1'
        ]);
    
        // Cek minimal topup
        if($request->amount < 1){
            return redirect()->route('bank.index')->with('error', 'Topup minimal Rp 1');
        }
    
        // Dapatkan user yang akan ditopup
        $targetUser = User::findOrFail($request->user_id);
    
        // Buat transaksi baru
        $transaksi = Transaksi::create([
            'sender_id' => $targetUser->id,
            'tipe_transaksi' => 'topup',
            'confirmed' => true,
            'amount' => $request->amount
        ]);
    
        // Update saldo user yang ditopup
        $targetUser->update([
            'balance' => $targetUser->balance + $request->amount
        ]);
        
        return redirect()->route('bank.index')->with('success', 'Top up success');
    }
    
    public function confirmTopup($id){
        $transaksi = Transaksi::findOrFail($id); // Cari transaksi berdasarkan ID

        //pastikan transaksi adalah topup dan belum dikonfirmasi
        if ($transaksi->tipe_transaksi === 'topup' && !$transaksi->confirmed){

            //tambahkan saldo ke user
            $user = User::find($transaksi->sender_id);
            $user-> update(['balance'=> $user->balance + $transaksi->amount]);

            //update transaksi menjadi dikondfirmasi
            $transaksi ->update(['confirmed'=> true]);

            return redirect()->route('bank.index')->with('succes','TopUp telah dikonfirmasi');
        }
        return redirect()->route('bank.index')->with('error', 'Transaksi tidak valid atau sudah dikonfirmasi.');
    }

    public function edit(string $id)
    {
        $bank = User::find($id);
        return view ('bank.edit',compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user,$id)
    {
        $user = User::find($id);
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|max:8',
            'name'=> 'required|string'
        ]);
        $user->update([
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'name' => $request->name,
        ]);
        $user->save();
        return redirect()->route('admin.index')->with(['success' => 'Data Berhasil Di Ubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $users = User::findOrfail($id); //mengambil id user yang ingin di delete
        $users->delete(); //melakukan delete

        return redirect()->route('bank.index'); // mengembalikan ke sebuah route bank.index == kembali ke tampilan bank.index
    }
}
