<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Supplier; // <-- Import model Supplier
use App\Models\TransactionDetail; // <-- Import model TransactionDetail

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'description',
        'category_id',
        'supplier_id', // <-- Tambahkan ini ke fillable
    ];

    /**
     * Dapatkan kategori yang memiliki produk ini.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Dapatkan supplier yang memasok produk ini.
     * Sesuai ERD: suppliers ||--o{ products : 'memasok'
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Dapatkan detail transaksi yang mencakup produk ini.
     * Sesuai ERD: products ||--o{ transaction_details : 'termasuk_dalam'
     */
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}