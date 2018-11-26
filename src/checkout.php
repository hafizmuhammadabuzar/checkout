<?php

namespace Quickcard\Checkout;

class Checkout{

    static private $END_POINT_URL = 'https://quickcard.herokuapp.com/';

    public static function requestToken($client_id, $secret_key){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$END_POINT_URL.'oauth/token/retrieve',
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

    public static function requestData($client_id, $client_secret, $code) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$END_POINT_URL.'oauth/token',
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

    public static function requestCheckUser($auth_token, $params) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$END_POINT_URL.'api/registrations/user_details',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'auth_token' => $auth_token,
                'phone_number' => (isset($params['phone_number'])) ? $params['phone_number'] : '',
                'email' => (isset($params['email'])) ? $params['email'] : ''
            ),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $resp = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($resp, true);
        return $json;
    }

    public static function requestNewUser($auth_token, $params) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$END_POINT_URL.'api/registrations/virtual_new_signup',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'auth_token' => $auth_token,
                'wallet_id' => (isset($params['phone_number'])) ? $params['phone_number'] : '',
                'phone_number' => (isset($params['phone_number'])) ? $params['phone_number'] : '',
                'exp_date' => (isset($params['exp_date'])) ? $params['exp_date'] : '',
                'card_number' => (isset($params['card_number'])) ? $params['card_number'] : '',
                'card_cvv' => (isset($params['card_cvv'])) ? $params['card_cvv'] : '',
                'amount' => (isset($params['amount'])) ? $params['amount'] : '',
                'email' => (isset($params['email'])) ? $params['email'] : '',
                'name' => (isset($params['first_name'])) ? $params['first_name'] : '',
                'last_name' => (isset($params['last_name'])) ? $params['last_name'] : '',
                'first_name' => (isset($params['first_name'])) ? $params['first_name'] : ''
            ),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $resp = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($resp, true);
        return $json;
    }

    public static function requestExistingUser($auth_token, $params) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$END_POINT_URL.'api/registrations/virtual_existing',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'auth_token' => $auth_token,
                'wallet_id' => (isset($params['phone_number'])) ? $params['phone_number'] : '',
                'phone_number' => (isset($params['phone_number'])) ? $params['phone_number'] : '',
                'exp_date' => (isset($params['exp_date'])) ? $params['exp_date'] : '',
                'card_number' => (isset($params['card_number'])) ? $params['card_number'] : '',
                'card_cvv' => (isset($params['card_cvv'])) ? $params['card_cvv'] : '',
                'amount' => (isset($params['amount'])) ? $params['amount'] : ''
            ),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $resp = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($resp, true);
        return $json;
    }

    public static function requestPayment($client_id, $secret_key, $params){

        $access_token = self::requestToken($client_id, $secret_key);

        if(!empty($access_token)){
            $res = self::requestData($client_id, $secret_key, $access_token);
            $params['wallet_id'] = $res['wallet_id'];

            if(!isset($res->error)){
                $check_user_res = self::requestCheckUser($res['access_token'], $params);
                if(!isset($check_user_res['success']) && !empty($check_user_res['success'])){
                    return self::requestNewUser($res['access_token'], $params);
                }else{
                    return self::requestExistingUser($res['access_token'], $params);
                }
                print_r($user_res); die;
            }else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    
}