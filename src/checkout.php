<?php

namespace Quickcard\Checkout;

class Checkout{

    static protected $client_id;
    static protected $secret_key;
    static protected $mode;
    static protected $auth_token;
    static private $END_POINT_URL;

    public function __construct($client_id, $secret_key, $mode){

        self::$client_id = $client_id;
        self::$secret_key = $secret_key;
        self::$mode = $mode;
        self::$END_POINT_URL = ($mode == 'Sandbox') ? 'https://quickcard.herokuapp.com/' : 'https://api.quickcard.me/';
    }

    public static function requestToken(){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$END_POINT_URL.'oauth/token/retrieve',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'client_id' => static::$client_id,
                'client_secret' => static::$secret_key
            ),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $resp = curl_exec($curl);
        curl_close($curl);
        $json = json_decode($resp, true);
        return $json["access_token"];
    }

    public static function requestData($code) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$END_POINT_URL.'oauth/token',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'client_id' => static::$client_id,
                'client_secret' => static::$secret_key,
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

    public static function requestCheckUser($params) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$END_POINT_URL.'api/registrations/user_details',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'auth_token' => self::$auth_token,
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

    public static function requestNewUser($params) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$END_POINT_URL.'api/registrations/virtual_new_signup',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'auth_token' => static::$auth_token,
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

    public static function requestExistingUser($params) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => self::$END_POINT_URL.'api/registrations/virtual_existing',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'auth_token' => static::$auth_token,
                'wallet_id' => (isset($params['wallet_id'])) ? $params['wallet_id'] : '',
                'phone_number' => (isset($params['phone_number'])) ? $params['phone_number'] : '',
                'exp_date' => (isset($params['expiry_date'])) ? $params['expiry_date'] : '',
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

    public static function requestPayment($params){

        $access_token = self::requestToken();

        if(!empty($access_token)){
            $res = self::requestData($access_token);

            if(!isset($res['error'])){

                self::$auth_token = $res['access_token'];
                $params['wallet_id'] = $res['wallet_id'];

                $check_user_res = self::requestCheckUser($params);
                if(!isset($check_user_res['success']) && !empty($check_user_res['success'])){
                    return self::requestNewUser($params);
                }else{
                    return self::requestExistingUser($params);
                }
            }else{
                return ['success' => false, 'message' => 'Invalid Request'];
            }
        }
        else{
            return ['success' => false, 'message' => 'Unauthorized'];
        }
    }
    
}