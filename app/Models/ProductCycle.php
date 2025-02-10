<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCycle extends Model
{
    use SoftDeletes,  HasFactory;

    protected $fillable = [
        'user_id',
        'order_product_id',
        'product_id',
        'duration',
        'start_date',
        'end_date',
        'status',
        'canceled',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->orderByDesc('start_date')->first();
    }
}
