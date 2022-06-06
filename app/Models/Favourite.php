<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favourite extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Favourite model can grab all products that  user favourite has
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
