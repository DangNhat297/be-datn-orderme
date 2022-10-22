<?php

if(!file_exists('uploadFile')){
    function uploadFile($file,$path){
        $filename = time() . '.' . $file->getClientOriginalExtension();
         return  $saveFile = $file->storeAs($path, $filename);
    }
}
