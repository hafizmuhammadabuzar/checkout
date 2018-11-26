<?php

namespace Quickcard\Checkout;

use Quickcard\Checkout\Checkout as checkout;

class Payment{

    protected $client_id;
    protected $secret_key;
    private $checkout;

    public function __construct($client_id, $secret_key){

        $this->client_id = $client_id;
        $this->secret_key = $secret_key;

        $this->checkout = new Checkout();
    }

    public function getToken(){

        $this->checkout->requestUser($this->client_id, $this->secret_key);
    }
    
}