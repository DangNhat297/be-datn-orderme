<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
//
//Broadcast::channel('chat-channel', function () {
//    return true;
//});

//use Illuminate\Support\Facades\Broadcast;
//
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('count-message-not-seen.{phone}', function ($user, $phone) {
    return $user->phone === $phone;
});
