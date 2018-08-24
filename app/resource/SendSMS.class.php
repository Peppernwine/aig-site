<?php
/**
 * Created by PhpStorm.
 * User: arthirajeev
 * Date: 7/17/2018
 * Time: 11:11 PM
 */

use Twilio\Rest\Client;

class SendSMS
{
    public function send($fromNumber, $toNumber, $message) {

        $client = new Client();

        $client->messages->create($toNumber,array('from' => $fromNumber,
                                                   'body' => $message
                                                  )
        );
    }

    public function sendTo($toNumber, $message) {
        $this->send(SMS_FROM_PHONE,$toNumber,$message);
    }

    public function sendToAdmin($message) {
        foreach (explode(',',SMS_TO_ADMIN_PHONES) as $toPhone) {
            $this->sendTo($toPhone, $message);
        }
    }

    public function sendToRestaurant($message) {
        foreach (explode(',',SMS_TO_FRONT_OFFICE_PHONES) as $toPhone) {
            $this->sendTo($toPhone, $message);
        }
    }
}