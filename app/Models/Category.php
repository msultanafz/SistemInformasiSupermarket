<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relasi: Satu kategori memiliki BANYAK produk.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
