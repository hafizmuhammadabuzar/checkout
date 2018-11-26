<?php

namespace Quickcard\Checkout;

class Checkout{

    protected $END_POINT_URL='https://api.quickcard.me/';
    protected $SANDBOX_END_POINT_URL='https://quickcard.herokuapp.com/';

    public function requestToken($client_id, $secret_key){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->SANDBOX_END_POINT_URL.'oauth/token/retrieve',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'client_id' => $client_id,
                'client_secret' => $secret_key
            ),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $resp = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($resp, true);
        return $json["access_token"];
    }

    public function retrieveData($client_id, $client_secret, $code) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $this->SANDBOX_END_POINT_URL.'oauth/token',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'code' => $code
            ),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $resp = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($resp, true);
        return $json;
    }

    public function requestUser($client_id, $secret_key){

        $access_token = $this->requestToken($client_id, $secret_key);

        if(!empty($access_token)){
            $res = $this->retrieveData($client_id, $secret_key, $access_token);

            print_r($res); die;
        }
        else{
            die('Invalid Request');
        }
    }
    
}