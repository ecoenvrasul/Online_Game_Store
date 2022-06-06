<?php

namespace App\Observers;

use App\Models\Token;

class TokenObserver
{
    /**
     * Handle the Token "created" event.
     *
     * @param  \App\Models\Token  $token
     * @return void
     */
    public function creating(Token $token)
    {
        $token_user = Token::where('user_id', $user->id)->first();
        $sign = $user->createToken('user')->plainTextToken;
            $token_user->update([
                'token' => $sign
            ]);
        // $user = $token->user;
        // $sign = $user->plainTextToken;
        // $token->update([
        //     'token' => $user->plainTextToken,
        //     'id' => $user->id
        // ]);
    }

    /**
     * Handle the Token "updated" event.
     *
     * @param  \App\Models\Token  $token
     * @return void
     */
    public function updated(Token $token)
    {
        //
    }

    /**
     * Handle the Token "deleted" event.
     *
     * @param  \App\Models\Token  $token
     * @return void
     */
    public function deleted(Token $token)
    {
        //
    }

    /**
     * Handle the Token "restored" event.
     *
     * @param  \App\Models\Token  $token
     * @return void
     */
    public function restored(Token $token)
    {
        //
    }

    /**
     * Handle the Token "force deleted" event.
     *
     * @param  \App\Models\Token  $token
     * @return void
     */
    public function forceDeleted(Token $token)
    {
        //
    }
}
