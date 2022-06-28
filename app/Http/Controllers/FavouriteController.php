<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavouriteController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            "user_id" => 'required|exists:users,id',
            "product_id" => 'required|exists:products,id'
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $favourite = Favourite::where('user_id', $request->user_id)
        ->where('product_id', $request->product_id)
        ->first();
        if($favourite){
            return ResponseController::error('This value is exists', 200);
        }
        Favourite::create([
            "user_id" => $request->user_id,
            "product_id" => $request->product_id
        ]);
        return ResponseController::success('Here We Go, You have a favourite section now', 200);
    }
    public function delete(Request $request){
        $find = Favourite::where('product_id', $request->product_id)
        ->where('user_id', $request->user_id)
        ->first();
        if(!$find){
            return ResponseController::error('No product found in favourite section', 404);
        }
        $find->delete();
        return ResponseController::success();
    }
    public function showUserProduct(User $user){
        $favourites = $user
        ->favourite()
        ->paginate(20);
        if(count($favourites) == 0){
            return ResponseController::error('There is no product in favourit section', 404);
        }
        $collection = [
            "last_page" => $favourites->lastPage(),
            "favourites" => [],
        ];
        foreach($favourites as $favourite){
            $product = $favourite->product()->first();
            $collection["favourites"][] = [
                // "product_id" => $product->id,
                "product_title" => $product->title,
                "price" => $product->price,
                "discount" => $product->discount,
                "current_price" => $product->current_price,
                "title_image" => $product->title_img,
                "added_time" => $product->created_at,
            ];
        }
        return ResponseController::data($collection);
    }
}
