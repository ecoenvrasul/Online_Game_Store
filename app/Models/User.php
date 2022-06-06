<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Favourite;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'point',
        'but_game_number'
    ];
    // protected $guarded = ['id'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function product(){
        return $this->hasMany(Product::class);
    }
    public function basket(){
        return $this->belongsTo(Basket::class);
    }
    //for updating a token
    public function token(){
        return $this->hasOne(Token::class);
    }

    // This relationship brings me all favourable products which a user has has
    public function favourite(){
        return $this->hasMany(Favourite::class);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }


    //This redirects the user if the validation fails,
    // protected $redirect = '/create';

    // This haltes the validation if there is occurance of the first failure in validation process
    // protected $stopOnFirstFailure = true;



}
