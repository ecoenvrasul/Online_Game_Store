<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Basket;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\ResponseController;

// All good
class BasketController extends Controller
{
    public function show(Request $request){
        $baskets = Basket::paginate(15);
        $collection = [
            "last_page" => $baskets->lastPage(),
            "baskets" => [],
        ];
        foreach($baskets as $basket){
            $collection["baskets"][] = [
                "basket_id" => $basket->id,
                "user" => [
                    "user_id" => $basket->user->id,
                    "user_name" => $basket->user->name,
                    "email" => $basket->user->email,
                    "point" => $basket->user->point,
                ],
                "basket_status" => $basket->status,
                "basket_price" => $basket->price,
                "basket_discount" => $basket->discount,
                "price_after_discount" => $basket->current_price,
                "number_of_products" => $basket->count
            ];
        }
        if(empty($collection)){
            return ResponseController::error('No basket yet');
        }
        return ResponseController::data($collection);
    }
    public function basketOrders(Basket $basket){
        $orders = Order::where('basket_id', $basket->id)
        ->paginate(20);
        $collection = [
            'last_page' => $orders->lastPage(),
            "orders" => [],
        ];
        foreach($orders as $order){
            $collection['orders'][] = [
                "order_id" => $order->id,
                "basket_id" => $order->basket_id,
                "product_id" => $order->product_id,
                "price" => $order->price,
                "discount" => $order->discount ?? 0,
                "price_after_discount" => $order->current_price
            ];
        }
        if(!$orders){
            return ResponseController::error('No orders yet here, please create first!');
        }
        return ResponseController::data($collection);
    }
    public function countProducts(Basket $basket){
        return ResponseController::data('Number of products-'.$basket->count);
    }
    public function deleteBasket(Request $request, Basket $basket){
        try {
            $this->authorize('delete', Basket::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to delete any Basket');
        }
        $basket->orders()->delete(); // orders delete
        $basket->delete(); //basket delete
        return ResponseController::error('Basket and Orders belongs to the Basket have been destroyed!');
    }
    public function deleteSingleOrder(Request $request, Basket $basket) {
        try {
            $this->authorize('delete', Basket::class);
        } catch (\Throwable $th) {
            return ResponseController::error('You are not allowed to delete any order here.');
        }
        $order = Order::where('basket_id', $basket->id)->first();
        $order->find($request->order_id)->delete();
        return ResponseController::success();
    }
    public function price(Basket $basket){
        return "Total price: ".$basket->current_price."$ US";
    }
    public function basketUser(Basket $basket){
        return $basket->user;
    }
    public function userBasket(User $user){
        $collection = [];
        $basket = Basket::where('user_id', $user->id)
        ->where('status', 'unpurchased')
        ->first();
        if(empty($basket)){
            return ResponseController::error('You have no Basket yet, please create one first!', 404);
        }
        $collection['basket_id'] = $basket->id;
        $collection['orders'] = $basket->orders;
        return ResponseController::data($collection);
    }
    public function payment($id, Request $request){
        $basket = Basket::find($id);
        $orders = $basket->orders;
        // $user = $basket->user();
        return $user;
    }
}

// payment method has not been done only


