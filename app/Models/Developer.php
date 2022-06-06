<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Developer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];
    public function products(){
        return $this->hasMany(Product::class);
    }

}
