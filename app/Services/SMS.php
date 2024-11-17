<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use SoapClient;

class SMS
{

    public $username = "arsha-74390";
    public $password = "XuKAZDHX0i2USZKA";
    public $domain = "magfa";

    public function send($mobile, $message)
    {
        $url = "https://sms.magfa.com/api/soap/sms/v2/server?wsdl";

        $options = [
            'login' => "$this->username/$this->domain", 'password' => $this->password, // -Credientials
            'cache_wsdl' => WSDL_CACHE_NONE, // -No WSDL Cache
            'compression' => (SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | 5), // -Compression *
            'trace' => false, // -Optional (debug)
            'stream_context' => stream_context_create(
                [
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    ]
                ]
            )
        ];

        $client = new SoapClient($url, $options);

        // send
        $response = $result['send'] = $client->send(
            [$message], // messages
            ["+98300074390"], // short numbers can be 1 or same count as recipients (mobiles)
            [$mobile], // recipients
            [], // client-side unique IDs.
            [], // Encodings are optional, The system will guess it, itself ;)
            [], // UDHs, Please read Magfa UDH Documnet
            [] // Message priorities (unused).
        );

        //$status = $response->status;
        //$parts = $response->parts;
        //$tariff = $response->tariff;
        //$id = $response->id;
    }

    public function credit()
    {
        // url
        $url = "https://sms.magfa.com/api/soap/sms/v2/server?wsdl";

        // soap options
        $options = [
            'login' => "$this->username/$this->domain",'password' => $this->password, // -Credientials
            'cache_wsdl' => WSDL_CACHE_NONE, // -No WSDL Cache
            'compression' => (SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | 5), // -Compression *
            'trace' => false // -Optional (debug)
        ];
        // * Accept response compression and compress requests using gzip with compression level 5

        // soap client
        $client = new SoapClient( $url, $options);

        // balance
        return $client->balance();
    }

    function smsLog($message = null, $senderNumber = null, $receiverNumber, $sendDateTime, $res_data = null, $getDeliver, $model, $details = null, $user_id = null)
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;

            $smsData = SmsLog::create([
                'user_id' => $user_id,
                'message' => $message,
                'sender_number' => $senderNumber,
                'receiver_number' => $receiverNumber,
                'send_datetime' => $sendDateTime,
                'status' => $getDeliver,
                'res_data' => $res_data,
                'details' => $details,
                'model' => $model
            ]);

            return $smsData;
        } else {
            $smsData = SmsLog::create([
                'user_id' => $user_id,
                'message' => $message,
                'sender_number' => $senderNumber,
                'receiver_number' => $receiverNumber,
                'send_datetime' => $sendDateTime,
                'status' => $getDeliver,
                'res_data' => $res_data,
                'details' => $details,
                'model' => $model
            ]);

            return $smsData;
        }
    }

}
