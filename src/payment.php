<?php

namespace Hafiz\Checkout;

use Hafiz\Checkout\Checkout as checkout;

class Payment{

    public static function getToken(){

        checkout::requestUser();
        die('custom class');
    }
    
}