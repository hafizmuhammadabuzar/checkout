<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
// use App\Libs\Payment;
use Hafiz\Checkout\Payment as payment;

class HomeController extends Controller
{
    public function index(){

        payment::getToken();
    }
    
}
