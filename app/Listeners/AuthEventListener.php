<?php

namespace App\Listeners;

use Illuminate\Http\Request;

use Auth;
use App\Models\Event;
use App\Models\AuthEvent;

class AuthEventListener
{
    /**
     * Handle user login events.
     */
    public function onUserLogin($event) {
        $authEvent           =  new AuthEvent;
        $authEvent->user_id  = Auth::id();
        $authEvent->event_id = Event::getLoginEvent()->id;
        $authEvent->ip       = Request::capture()->ip();
        $authEvent->save();
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event) {
        $authEvent           =  new AuthEvent;
        $authEvent->user_id  = Auth::id();
        $authEvent->event_id = Event::getLogoutEvent()->id;
        $authEvent->ip       = Request::capture()->ip();
        $authEvent->save();   
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'auth.login',
            'App\Listeners\AuthEventListener@onUserLogin'            
        );

        $events->listen(
            'auth.logout',
            'App\Listeners\AuthEventListener@onUserLogout'
        );
    }

}