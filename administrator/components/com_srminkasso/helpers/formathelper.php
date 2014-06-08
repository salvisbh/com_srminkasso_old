<?php
/**
 * Created by PhpStorm.
 * User: hps
 * Date: 11.02.14
 * Time: 17:41
 */

class FormatHelper {

    public static function formatDate($date){

        if(strchr($date,'0000')){
            $datRet='';
        }else{
            $jDate = new Jdate($date);
            $datRet = $jDate->format('d.m.Y');
        }

        return $datRet;
    }

    public static function formatWaehrung($value){
        return number_format($value,2);
    }
} 