<?php

namespace Quickcard\Checkout;

use Quickcard\Checkout\Checkout as checkout;

class Payment{

    protected $client_id;
    protected $secret_key;
    protected $mode;

    public function __construct($client_id, $secret_key, $mode){

        $this->client_id = $client_id;
        $this->secret_key = $secret_key;
        $this->mode = $mode;

        if(empty($this->client_id) && empty($this->secret_key)){
            return false;
        }
    }

    public function requiredParams(){

        return ['phone_number', 'expiry_date', 'card_number', 'card_cvv', 'amount', 'email', 'first_name', 'last_name'];
    }

    public function paymentSubmit($params){

        $required_fields = $this->requiredParams();

        $error = array();
        foreach($required_fields as $value){
            if(!isset($params["$value"]) || empty($params["$value"])){
                $error[] = "'$value' is required";
            }
        }

        if(count($error) > 0){
            return ['success' => false, 'message' => 'All fields required', 'data' => $error];
        }

        $obj = new Checkout($this->client_id, $this->secret_key, $this->mode);
        return checkout::requestPayment($params);
    }
    
}