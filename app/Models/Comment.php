<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];


    public function product(){
        return $this->belongsTo(Product::class);
    }
}
