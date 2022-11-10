<?php
    use Illuminate\Support\Arr;
if(!file_exists('uploadFile')){
    function uploadFile($file,$path){
        $filename = time() . '.' . $file->getClientOriginalExtension();
         return  $saveFile = $file->storeAs($path, $filename);
    }
}

    if(!file_exists('fakeImage')){
        function fakeImage(){
           $arrayImage=array(
               'https://imgs.search.brave.com/yXKY6F17DGFMMxKas-JwSGtIS9Yv4rtgA_GYNpYxOiE/rs:fit:474:225:1/g:ce/aHR0cHM6Ly90c2Uy/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC40/UF8yUDBFZ1V0OTdE/LXVPbVZVckVnSGFI/YSZwaWQ9QXBp',
               'https://imgs.search.brave.com/cldefpZOWe80bLdZ-og8uTfaFDqRNdEoWUd4RiQpd4s/rs:fit:300:225:1/g:ce/aHR0cHM6Ly90c2U0/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5Z/dGJUdGpfN1lGdDVH/RXJDUXJpVlBBQUFB/QSZwaWQ9QXBp',
               'https://imgs.search.brave.com/h7qqZ3E6_1f5Zc56UveVr8GiLkysPmwc5Ggc2xnvgmw/rs:fit:474:225:1/g:ce/aHR0cHM6Ly90c2Uz/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5E/Sl90eHJuRU1SaHM5/N3RxZ2RGbE1nSGFI/YSZwaWQ9QXBp',
               'https://imgs.search.brave.com/M9-qqgaCerzpScV_Hy4vlQ6ggQ5rpgIjI5o0oQo2Xng/rs:fit:381:225:1/g:ce/aHR0cHM6Ly90c2U0/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5N/OUI2TXBmenV6U09L/ZkVEQ2dZV3BRQUFB/QSZwaWQ9QXBp',
               'https://imgs.search.brave.com/Crho4tfP2clJ3GcaUzd_djuuMgDwNmU0uaqEuFnAQhg/rs:fit:474:225:1/g:ce/aHR0cHM6Ly90c2Uy/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5X/UHRsMzZmRzZjRnhO/d2U4T0RFb2lBSGFI/YSZwaWQ9QXBp',
               'https://imgs.search.brave.com/YfUOJCQm3AyAyuT1w_E1O_3Zz7vsQGyBMxzl8ViSztU/rs:fit:474:225:1/g:ce/aHR0cHM6Ly90c2Ux/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5z/LU1BNVFySTBlQ2Vr/UUs3OG0wSWtRSGFI/YSZwaWQ9QXBp',
               'https://imgs.search.brave.com/WqmO6ccuNcqhZy2ohiVyKyWYnNusr0C0KJx-bTfiMj4/rs:fit:474:225:1/g:ce/aHR0cHM6Ly90c2U0/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5m/VTJEUTU0ME1sTzE4/XzFiR3JDV1N3SGFI/YSZwaWQ9QXBp',
               'https://imgs.search.brave.com/8mtZMPm5aaksTAz6aAgq1D0BtVzcs2eFcDNnfUuoFRI/rs:fit:316:225:1/g:ce/aHR0cHM6Ly90c2Uz/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5H/RkhKeG1zZk1MODZT/bVdNb3VkUkx3SGFM/SCZwaWQ9QXBp',
               'https://imgs.search.brave.com/xrLmyKHU_hm18U1RPZo1lqw66Kz-Ug09n6KpO94VBW4/rs:fit:474:225:1/g:ce/aHR0cHM6Ly90c2U0/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5z/a0YwdU1wZE1IWjlT/VGp0OTJoTFZRSGFI/YSZwaWQ9QXBp',
               'https://imgs.search.brave.com/ADbDcwOSkBYaVfMOSSCBcTaQtDzWsp2GRkLonceOU1g/rs:fit:474:225:1/g:ce/aHR0cHM6Ly90c2Uz/Lm1tLmJpbmcubmV0/L3RoP2lkPU9JUC5Q/S1BneERCVlUzTEN1/c0p1YkVlSE1BSGFI/YSZwaWQ9QXBp'
           );
           $random= Arr::random($arrayImage);
            return  $random;
        }
    }

    if (!function_exists('makeSlug')) {
        function makeSlug($string){
            $string=vn_to_str($string);
            return    preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $string));
        }
    }

    if (!function_exists('vn_to_str')) {
        function vn_to_str($str){
            $unicode = array(
                'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
                'd'=>'đ',
                'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                'i'=>'í|ì|ỉ|ĩ|ị',
                'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
                'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
                'D'=>'Đ',
                'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
                'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
                'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
                'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
                'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            );
            foreach($unicode as $nonUnicode=>$uni){
                $str = preg_replace("/($uni)/i", $nonUnicode, $str);
            }
            $str = str_replace(' ',' ',$str);
            return $str;
        }
    }




