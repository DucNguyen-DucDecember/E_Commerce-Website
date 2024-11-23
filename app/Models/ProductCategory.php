<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_name', 'category_slug', 'user_id', 'parent_id'];

    function product()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
