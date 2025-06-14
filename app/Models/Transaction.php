<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'user_id',
        'total_amount',
    ];

    /**
     * Relasi: Satu transaksi memiliki BANYAK detail.
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Relasi: Satu transaksi dimiliki oleh SATU user (kasir).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
