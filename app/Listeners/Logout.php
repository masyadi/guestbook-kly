<?php

namespace App\Listeners;

use App\Events\OnLogout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\User;
use Helper;

class Logout
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OnLogout  $event
     * @return void
     */
    public function handle(OnLogout $event)
    {
        User::where('id', $event->user->id)
            ->update(['logged_out_at' => Helper::formatDate(null)]);
    }
}
