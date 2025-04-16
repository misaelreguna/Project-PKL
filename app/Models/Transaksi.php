<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'tipe_transaksi',
        'amount',
        'confirmed',
    ];
    public function senders():BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relasi ke user sebagai receiver
    public function receiver():BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}


