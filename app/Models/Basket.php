<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Basket extends Model
{
    use HasFactory;
    // protected $guarded = ['id'];

    protected $fillable = [
        'user_id',
        'status',
        'price',
        'discount',
        'current_price',
        'ordered_at',
    ];

        public function order(){
            return $this->hasMany(Order::class,);
        }
        public function product(){
            return $this->hasMany(Product::class);
        }
        public function user(){
            return $this->belongsTo(User::class);
        }

        // This function works in order to delete orders when deleting Basket
        public function orders(){
            return $this->hasMany(Order::class);
        }
}
