<?php

namespace Hafiz\Checkout;

use Hafiz\Checkout\Payment;

class Checkout extends Payment{

    // static protected $clientId;
    // static protected $secretKey;
    // static protected $token;

    public static function requestToken(){

        return [
            'client_id' => env('CLIENT_ID'),
            'secret_key' => env('SECRET_KEY')
        ];
    }

    public static function requestUser(){

        $params = self::requestToken();

        print_r($params); die;
    } 
    
}