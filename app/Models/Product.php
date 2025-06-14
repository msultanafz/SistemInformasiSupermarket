<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category; // <-- Pastikan ini di-import juga!

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'category_id', // Pastikan category_id ada di $fillable
    ];

    /**
     * Get the category that owns the product.
     * Mendefinisikan relasi Product belongs to Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
