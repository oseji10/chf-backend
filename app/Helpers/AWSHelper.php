<?php

namespace App\Helpers;

class AWSHelper
{

    protected static $key;
    protected static $secret;
    protected static $region;
    protected static $version;

    public function __construct()
    {
        $this->key = config('services.ses.key');
        $this->secret = config('services.ses.secret');
        $this->region = config('services.ses.region');
    }

    protected static function prepare()
    {
        self::$key = config('services.ses.key');
        self::$secret = config('services.ses.secret');
        self::$region = config('services.ses.region');
    }

    public static function getCredentials()
    {
        return [
            'key' => self::$key,
            'secret' => self::$secret,
            'region' => self::$region
        ];
    }

    public static function getConfig()
    {
        self::prepare();
        return array(
            'credentials' => array(
                'key' => self::$key,
                'secret' => self::$secret,
            ),
            'region' => self::$region, // < your aws from SNS Topic region
            'version' => 'latest'
        );
    }

    public static function parsePhoneNumber($phone_number)
    {
        if (substr((string)$phone_number, 0, 1) !== '+') {
            return "+234" . substr((string)$phone_number, 1);
        }
        return $phone_number;
    }

    public static function uploadFileToS3($file, $extension, $path = 'uploads/')
    {
        // return [
        //     'bucket' => \config('services.ses.default-s3-bucket'),
        //     'key' => $path. random_int(10000009,99911111) .time() .'.' . $extension,
        //     'sourceFile' => $file,
        // ];
        try {
            $client = new \Aws\S3\S3Client([
                'region' => 'eu-west-2',
                'version' => 'latest',
                'credentials' => [
                    'key' => \config('services.ses.key'),
                    'secret' => \config('services.ses.secret'),

                ]
            ]);

            $uploaded_file = $client->putObject([
                'Bucket' => \config('services.ses.default-s3-bucket'),
                'Key' => $path . random_int(10000009, 99911111) . time() . '.' . $extension,
                'SourceFile' => $file,
            ]);

            return $uploaded_file->toArray()['ObjectURL'];
        } catch (\Exception $ex) {
            return dd($ex);
        }
    }

    public static function sendSMS($phone_number = null, $message = '')
    {
        $params = self::getConfig();

        $sns = new \Aws\Sns\SnsClient($params);

        $args = array(
            "MessageAttributes" => [
                'AWS.SNS.SMS.SenderID' => [
                    'DataType' => 'String',
                    'StringValue' => 'emgechf'
                ],
                'AWS.SNS.SMS.SMSType' => [
                    'DataType' => 'String',
                    'StringValue' => 'Transactional'
                ]
            ],
            "Message" => $message,
            "PhoneNumber" => self::parsePhoneNumber($phone_number)
        );

        try {
            $result = $sns->publish($args);
            return true;
        } catch (\Exception $ex) {
            \Log::info("could not send SMS ");
            \Log::info($ex);
            return false;
        }
    }
}
