<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCycle extends Model
{

    protected $fillable = [
        'user_id',
        'order_product_id',
        'duration',
        'start_date',
        'end_date',
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
}
