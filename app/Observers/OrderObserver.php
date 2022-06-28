<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Str;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
        $product = $order->product;
        $order->activation_code = Str::uuid();
        $price = $product->price;
        $discount =  $product->discount;
        $order->price = $price ?? 0;
        $order->discount = $discount ?? 0;

    }
    public function created(Order $order)
    {
        //there is the relationship written in Order model, Order has basket method
        $basket = $order->basket;
        $basket->price = $order->price + $basket->price;
        $basket->discount = $order->discount;
        $count = Order::where('basket_id', $basket->id)->count();

        $discount = $basket->discount ?? 0;
        $price = $order->current_price + $basket->price;
        $basket->update([
            'price' => $price,
            'current_price' => $price - (($price*$discount)/100),
            'count' => $count,
        ]);
        $order->update([
            'current_price' => $order->price - (($order->price*$order->discount)/100),
        ]);


    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {

    }


    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $basket = $order->basket;
        $basket_count = $basket->count-1;

        /* This updates PRICE, COUNT, CURRENT_PRICE column when a User deletes
        a single Order from the basket */
        $basket->update([
            'count' => $basket_count,
            "price" => $basket->price-$order->price,
            "current_price" => $basket->current_price-$order->current_price,
        ]);

        /* This updated DISCOUNT column when there is no product in Basket
        so that the value of the DISCOUNT column becomes zero, */
        if($basket->count == 0) {
            $basket->update([
                "discount" => 0,
            ]);
        }


    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
