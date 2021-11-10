<?php

namespace App\Listeners;

use App\Events\OnLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class Login
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
     * @param  OnLogin  $event
     * @return void
     */
    public function handle(OnLogin $event)
    {
        //update
        $update = \App\User::where('id', $event->user->id)->first();
        $update->ip_address = \Request::ip();
        $update->logged_in_at = date("Y-m-d H:i:s");

        if( isset($event->auth) && $event->auth )
        {
            $update->name = ucwords($event->auth->user['name']);
            //save image
            //if( !$event->user->photo && $event->auth->user['picture'])
            if( !$event->user->photo && $event->auth->avatar)
            {
                $photo = file_get_contents($event->auth->avatar);
                $path  = 'images/user/'.$event->user->id.'.png';

                if( $photo )
                {
                    if (\Storage::disk()->put($path, $photo))
                    {
                        $update->photo = $path.'?v='.uniqid();
                    }
                }
            }
        }

        $update->save();
    }
}
