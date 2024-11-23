<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_name', 'product_slug', 'product_detail', 'product_price', 'stock_quantity', 'is_featured', 'product_status', 'product_desc', 'product_thumb', 'user_id', 'category_id', 'product_thumb'];

    function product_category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    function product_image()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
