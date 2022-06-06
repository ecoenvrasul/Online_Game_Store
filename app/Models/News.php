<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];


    public function comments(){
        return $this->HasMany(Comment::class);
    }

    protected $casts = [
        'created_at'=> 'datetime:Y-m-d H:i:s'
    ];
}
