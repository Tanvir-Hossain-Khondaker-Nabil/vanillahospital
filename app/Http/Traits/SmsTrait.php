<?php


namespace App\Http\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

trait SmsTrait
{

    private $SMS_SENDER = "8809612770444";
    private $RESPONSE_TYPE = 'json';
    private $SMS_USERNAME = 'hisebi';
    private $SMS_PASSWORD = '123456';
    private $API_KEY = '07325c89dd70eb6f';
    private $ADMIN_NUMBER = '8801816306190';

    // private $ADMIN_NUMBER = '8801816518102';
    private $secretkey = '97bde49a';
    private $callerID = 'Hisebi';


    public function sendSMS($phone_number, $message){


        // $url = "http://sms.ajuratech.com/api/mt/SendSMS?APIKey=$this->API_KEY&senderid=$this->SMS_SENDER&channel=Normal&flashsms=0&DCS=0&number=$phone_number&text=".urlencode($message);
        $url = "http://smpp.ajuratech.com:7788/sendtext?apikey=$this->API_KEY&secretkey=$this->secretkey&callerID=$this->callerID&toUser=$phone_number&messageContent=".urlencode($message);
        
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        // grab URL and pass it to the browser
        $response = curl_exec($ch);
        $err = curl_error($ch);

        // close cURL resource, and free up system resources
        if ($err) {
            echo "cURL Error #:" . $err;
        }

        curl_close($ch);
    }

    public function sendAdminSMS($name, $number){


        $message = "New User Created in Hisebi. User:".$name." - phone:".$number;

        // $url = "http://sms.ajuratech.com/api/mt/SendSMS?APIKey=$this->API_KEY&senderid=$this->SMS_SENDER&channel=Normal&flashsms=0&DCS=0&number=$this->ADMIN_NUMBER&text=".urlencode($message);
        $url = "http://smpp.ajuratech.com:7788/sendtext?apikey=$this->API_KEY&secretkey=$this->secretkey&callerID=$this->callerID&toUser=$this->ADMIN_NUMBER&messageContent=".urlencode($message);

        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        // grab URL and pass it to the browser
        $response = curl_exec($ch);
        $err = curl_error($ch);

        // close cURL resource, and free up system resources
        if ($err) {
            echo "cURL Error #:" . $err;
        }

        curl_close($ch);
    }

}
