<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Basket;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

// All good
class OrderController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            "product_id" => 'required|exists:products,id',
        ]);
        if($validator->fails()){
            return ResponseController::error($validator->errors()->first());
        }
        $user = $request->user();
        $product_id = $request->product_id;
        $basket = Basket::where('user_id', $user->id)
        ->where('status', 'unpurchased')
        ->first();

        $product = Order::where('product_id', $product_id)
        ->where('basket_id', $basket?->id)
        ->first();
        if(!$basket){
            $basket = Basket::create([
                'user_id' => $user->id,
                'ordered_at' => Carbon::now(),
            ]);
            Order::create([
                'basket_id' => $basket->id,
                'product_id' => $product_id
            ]);
            return "Basket and Order have been successfully created";
        }elseif($product) {
            return "The product is already exists!";
        }else{
            Order::create([
                'basket_id' => $basket->id,
                'product_id' => $product_id
            ]);
            return "Order has been created";
        }
    }
}

