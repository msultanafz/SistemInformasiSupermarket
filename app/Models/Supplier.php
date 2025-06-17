<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import HasMany

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
    ];

    /**
     * Dapatkan produk-produk yang dipasok oleh supplier ini.
     * Sesuai ERD: suppliers ||--o{ products : 'memasok'
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}