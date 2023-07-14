<?php
namespace App\Helpers;

class NotificationHelper{

    public function sendSMS($phone_number = null, $message = ''){
        return AWSHelper::sendSMS($phone_number, $message);
    }
}