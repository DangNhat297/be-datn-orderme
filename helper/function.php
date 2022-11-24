<?php

use Carbon\Carbon;
use Illuminate\Support\Arr;

if (!function_exists('uploadFile')) {
    function uploadFile($file, $path)
    {
        $filename = time() . '.' . $file->getClientOriginalExtension();
        return  $saveFile = $file->storeAs($path, $filename);
    }
}

if (!function_exists('fakeImage')) {
    function fakeImage()
    {
        return  $random='https://imgs.search.brave.com/Pg-BE8OUCEy1ImFphEFn7NdgDWsadWSnjUwCZkeNrho/rs:fit:350:225:1/g:ce/aHR0cHM6Ly90c2U0/LmV4cGxpY2l0LmJp/bmcubmV0L3RoP2lk/PU9JUC5NSWY4Y1JC/Wm82TFg4cmpRTVNX/YXV3QUFBQSZwaWQ9/QXBp';
    }
}



if (!function_exists('generate_order_code')) {
    function generate_order_code()
    {
        $str_random = uniqid();
        $code = strtoupper(substr($str_random, 6, 6));
        return 'o' . $code;
    }
}

function convert_date(string $date, $format = 'Y-m-d H:i:s')
{
    return Carbon::parse(strtotime($date))->format($format);
}

function convert_end_date(string $date, $format = 'Y-m-d H:i:s')
{
    return Carbon::parse(strtotime($date))->endOfDay()->format($format);
}
