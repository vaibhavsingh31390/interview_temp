<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'name',
        'txn_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productCycles()
    {
        return $this->hasMany(ProductCycle::class);
    }

    public function activeCycle()
    {
        return $this->hasOne(ProductCycle::class)->latestOfMany();
    }
}
