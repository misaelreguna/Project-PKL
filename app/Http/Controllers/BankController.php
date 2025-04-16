<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\alert;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->where('role', 'user')->get();
        $transactions = Transaksi::all();
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

    public function topup(Request $request){
        $targetUser = User::find($request->user_id); // disini user_id dimaksudkan name dalam tampilan view bank
        if($request->amount < 1){
            return redirect()->route('bank.index')-> alert('topup minimal Rp 1');
        }   

        Transaksi::create ([
            'sender_id'=> $targetUser->id,
            'tipe_transaksi'=> 'topup',
            'confirmed' => true,
            'amount'=> $request->amount
        ]);
        return redirect()->route('bank.index')->with('success','top up success');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
