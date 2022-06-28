<?php

namespace App\Observers;

use App\Models\Basket;

class BaskerObserver
{
    /**
     * Handle the Basket "created" event.
     *
     * @param  \App\Models\Basket  $basket
     * @return void
     */
    public function creating(Basket $basket)
    {

    }

    public function created(Basket $basket)
    {
        $user = $basket->user;
        $user->order = $basket->count;
        $user->update([
            'bought_game_number' => $user->order + 1,
        ]);
    }

    /**
     * Handle the Basket "updated" event.
     *
     * @param  \App\Models\Basket  $basket
     * @return void
     */
    public function updated(Basket $basket)
    {
        //
    }

    /**
     * Handle the Basket "deleted" event.
     *
     * @param  \App\Models\Basket  $basket
     * @return void
     */
    public function deleted(Basket $basket)
    {
        /* Orderlarni hammasini delete qilgandan keyin, Basketni o'zi qandaydir bir soatdan keyin
        avtomatik ravishda o'chadigan qilib ishlasa bo'lama? */

        // if($basket->count == 0){
        //     $basket->sleep()->delete(2);
        // }
    }

    /**
     * Handle the Basket "restored" event.
     *
     * @param  \App\Models\Basket  $basket
     * @return void
     */
    public function restored(Basket $basket)
    {
        //
    }

    /**
     * Handle the Basket "force deleted" event.
     *
     * @param  \App\Models\Basket  $basket
     * @return void
     */
    public function forceDeleted(Basket $basket)
    {
        //
    }
}
