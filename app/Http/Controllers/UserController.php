<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $users = User::where('id', '!=', Auth::id())->where('role', 'user')->get();
        $transactions = Transaksi::where('sender_id', Auth::id())->orWhere('receiver_id', Auth::id())->latest()->get();
        return view('user.index', compact('users', 'transactions', 'currentUser'));
    }

    public function topup(Request $request)
    {
        $user = User::findOrfail(Auth::id());

        if($request->amount < 1){
            return redirect()->route('user.index')->alert('Topup minimal Rp 1');
        }

        Transaksi::create([
            'sender_id' => $user->id,
            'tipe_transaksi' => 'topup',
            'amount' => $request->amount,
            'confirmed' => false,
        ]);
        return redirect()->route('user.index')->with('success', 'Topup Success');
    }

    public function tariktunai(Request $request)
    {
        $user = User::findOrfail(Auth::id());
        if($request->amount < 1){
            return redirect()->route('user.index')->alert('Tarik tunai minimal Rp 1');
        }

        if($user->balance < $request->amount){
            return redirect()->route('user.index')->alert('Saldo tidak cukup');
        }

        Transaksi::create([
            'sender_id' => $user->id,
            'tipe_transaksi' => 'tarik',
            'amount' => $request->amount,
            'confirmed' => true,
        ]);
        // kurangkan saldo ke user
        $user->update(['balance' => $user->balance - $request->amount]);

        return redirect()->route('user.index')->with('success', 'Tarik Tunai Success');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
        ]);

        
        $sender = User::findOrFail(Auth::id());
        $receiver = User::findorfail($request->receiver_id);

        if($request->amount < 1){   
            return redirect()->route('user.index')->alert('Saldo tidak cukup'); 
        }
        if($sender->balance < $request->amount){
            return redirect()->route('user.index')->alert('Saldo tidak cukup');
        }
        $receiver->update ([
            'balance' => $receiver->balance + $request->amount
        ]);

        $sender->update ([
            'balance' => $sender->balance - $request->amount
        ]);


        Transaksi::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'tipe_transaksi' => 'transfer',
            'amount' => $request->amount,
            'confirmed' => true,
        ]);

        return redirect()->route('user.index')->with('success', 'Transfer berhasil.');
    }
}
