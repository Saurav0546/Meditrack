<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // protected $appends = ['quantity'];
    protected $appends = ['total_price_with_tax'];


    protected $fillable = [
        'customer_name', 
        'order_date', 
        'total_price',
    ];

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class)->withPivot('quantity');
    }
    protected function totalPriceWithTax(): Attribute
    {
        return new Attribute(
            get: fn() => number_format($this->calculateTotalPriceWithTax(), 2),
        );
    }
    protected function calculateTotalPriceWithTax()
    {
        return $this->total_price * 1.1; // 10% tax rate
    }
}