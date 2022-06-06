<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publisher extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name'];

    protected $dates = ['deleted_at'];

    // products of any publisher can ne grabbed by this function
    public function products(){
        return $this->hasOne(Product::class);
    }
}
