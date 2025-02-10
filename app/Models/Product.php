<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes,  HasFactory;

    protected $fillable = ['name'];

    public function orders()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function productCycles()
    {
        return $this->hasMany(ProductCycle::class);
    }
}
