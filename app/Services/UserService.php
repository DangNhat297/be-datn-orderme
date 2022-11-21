<?php

namespace App\Services;

class UserService
{
    public function getInfoGuest()
    {
        $guest = request()->cookie('guest');

        $guest = json_decode($guest, false); // return object
        
        return $guest;
    }
}
