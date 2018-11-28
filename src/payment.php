<?php

namespace Quickcard\Checkout;

use Quickcard\Checkout\Checkout as checkout;

class Payment{

    static protected $client_id;
    static protected $secret_key;
    static protected $mode;

    public static function requiredParams(){

        return ['phone_number', 'expiry_date', 'card_number', 'card_cvv', 'amount', 'email', 'first_name', 'last_name'];
    }

    public static function paymentSubmit($params){

        $required_fields = self::requiredParams();

        $error = array();
        foreach($required_fields as $value){
            if(!isset($params["$value"]) || empty($params["$value"])){
                $error[] = "'$value' is required";
            }
        }

        if(count($error) > 0){
            return ['success' => false, 'message' => 'All fields required', 'data' => $error];
        }else{
            self::$client_id = env('CLIENT_ID');
            self::$secret_key = env('SECRET_KEY');
            self::$mode = env('MODE');

            $obj = new Checkout(self::$client_id, self::$secret_key, self::$mode);
            return checkout::requestPayment($params);
        }
    }
    
}