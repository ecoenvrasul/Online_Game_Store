<?php

namespace App\Models;

use App\Models\Genre;
use App\Models\Order;
use App\Models\Comment;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\GenreProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $casts = [
        'minimal_system' => 'json',
        'recommend_system' => 'json',
        'screenshots' => 'json',
        'trailers' => 'json',
        'producted' => 'json',
        'genre'=> 'json'
    ];
    protected $dates = ['deleted_at'];

    public function developer(){
        return $this->hasOne(Developer::class);
    }
    public function publisher(){
        return $this->hasOne(Publisher::class);
    }
    // public function genre(){
    //     return $this->hasOne(Genre::class);
    // }

    //need
    public function comments(){
        return $this->HasMany(Comment::class);
    }
    public function genre(){
        return $this->hasMany(GenreProduct::class. 'product_id', 'id');
    }

    // no need
    // public function order(){
    //     return $this->hasOne(Order::class);
    // }

    public function user(){
        return $this->belongsTo(User::class);
    }


}
