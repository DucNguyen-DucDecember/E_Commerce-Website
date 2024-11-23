<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['total_quantity', 'order_code', 'total_amount', 'order_date', 'note', 'payment_method', 'shipping_address', 'customer_id'];

    function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    function order_item()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
