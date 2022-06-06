<?php

namespace App\Models;

use App\Models\Basket;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    //need
    public function product(){                  //order table
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    // bu OrderObserverda pricelarni jadvalga created qilib beradi
    // $basket = $order->basket - it is showm in such way
    public function basket(){
        return $this->belongsTo(Basket::class, 'basket_id', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
