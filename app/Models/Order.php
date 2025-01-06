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

    // public function getQuantityAttribute()
    // {
    //     return $this->getAttribute('quantity');
    // }
    // public function getQuantityAttribute()
    // {
    //     // Calculate the total quantity of medicines in the order
    //     return $this->medicines->sum('pivot.quantity');
    // }
    protected function totalPriceWithTax(): Attribute
    {
        return new Attribute(
            get: fn() => $this->calculateTotalPriceWithTax(),
        );
    }

    protected function calculateTotalPriceWithTax()
    {
        return $this->total_price * 1.1; // 10% tax rate
    }

}