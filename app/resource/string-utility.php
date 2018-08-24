<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 4/16/2018
 * Time: 2:26 PM
 */

function Str2Hex($str){
    $hex='';
    for ($i=0; $i < strlen($str); $i++){
        $hex .= dechex(ord($str[$i]));
    }
    return $hex;
}


function Hex2Str($hex){
    $str='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $str.= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $str;
}