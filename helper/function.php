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

    function convert_dated(string $date, $format = 'Y-m-d')
    {
        return Carbon::parse(strtotime($date))->format($format);
    }


    function createDateRangeArray($strDateFrom,$strDateTo)
    {
        $aryRange = [];

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }

    if (!function_exists('convert_price')) {
    function convert_price(int $price)
    {
        return number_format($price, 0, '.', '.');
    }
    }
