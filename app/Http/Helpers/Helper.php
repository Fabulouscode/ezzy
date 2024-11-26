<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Log;
use App\Repositories\CategoryRepository;
use App\Models\Category;
use App\Models\UserTracking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\RawMessage;

class Helper
{
    private $category_repo;

    public function __construct(CategoryRepository $category_repo)
    {
        $this->category_repo = $category_repo;
    }

    public static function getCategoryName($id)
    {
        $category_name = '';
        $categories = Category::get();
        foreach ($categories as $key => $value) {
            if ($value->id == $id) {
                $category_name = $value->name;
                break;
            }
        }
        return $category_name;
    }

    /**
     * get timestamp formate date and time
     */
    public static function addUserTracking($user_type, $admin_id, $user_id, $field_name, $field_value)
    {
        $data = [
            'user_type' => $user_type,
            'admin_id' => !empty($admin_id) ? $admin_id : null,
            'user_id' => $user_id,
            'field_name' => $field_name,
            'field_value' => $field_value
        ];

        return UserTracking::create($data);
    }

    /**
     * get timestamp formate date and time
     */
    public static function deleteUserTracking($user_id)
    {
        return UserTracking::where('user_id', $user_id)->delete();
    }

    /**
     * get timestamp formate date and time
     */
    public static function currncyNumberFormat($amount)
    {
        $amount = number_format($amount, 2, ".", ",");
        return '₦ ' . $amount;
    }

    /**
     * get timestamp formate date and time
     */
    public static function getDateTimeLocalFormate($date_time, $timezone)
    {
        $date_time_formate = new Carbon($date_time);
        (!empty($timezone)) ? $date_time_formate->setTimezone($timezone) : '';
        return $date_time_formate->format('d M, Y H:i:s');
    }

    /**
     * get timestamp formate date and time
     */
    public static function getDateTimeFormate($date_time)
    {
        $date_time_formate = new Carbon($date_time);
        (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $date_time_formate->setTimezone(Auth::user()->timezone) : '';
        return $date_time_formate->format('d M, Y H:i:s');
    }

    /**
     * get timestamp formate date
     */
    public static function getDateFormate($date)
    {
        $date_formate = new Carbon($date);
        (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $date_formate->setTimezone(Auth::user()->timezone) : '';
        return $date_formate->format('d M, Y');
    }

    /**
     * get timestamp formate time
     */
    public static function getTimeFormate($time)
    {
        $time_formate = new Carbon($time);
        (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $time_formate->setTimezone(Auth::user()->timezone) : '';
        return $time_formate->format('H:i:s');
    }

    /**
     * get timestamp formate time
     */
    public static function getUserTimezoneConvertFormate($time, $timezone = 'UTC')
    {
        //$timezone
        $timezone = !empty($timezone) ? $timezone : 'UTC';
        $time_formate = Carbon::createFromFormat('H:i:s', $time, 'UTC')->setTimezone($timezone);
        return $time_formate->format('h:i a');
    }

    /**
     * sending firebase notification
     */
    public static function sendNotification($notification, $receiver, $sender = '', $unreadNotification = 0)
    {
        // $url = 'https://fcm.googleapis.com/fcm/send';
        // $serverApiKey = config('app.FCM_KEY');

        $notification_check = User::where('id', $receiver->id)->where('notification_status', '1')->first();
        if (!empty($notification_check)) {
            return true;
        }

        $parameter = json_decode($notification->parameter, true);
        $image = (isset($parameter['notification_image']) && $parameter['notification_image'] != '') ? $parameter['notification_image'] : '';
        $message = [
            'id' => $notification->id,
            'message' => $notification->message,
            'parameter' => json_decode($notification->parameter, true),
            'sender_id' => $notification->sender_id,
            'sender_name' => (!empty($sender)) ? $sender->user_name : '-',
            'receiver_id' => $notification->receiver_id,
            'type' => $notification->msg_type,
            'sender_avatar' => (!empty($sender)) ? $sender->profile_image : '',
            'attachment' => '',
            'notification_count' => $unreadNotification,
            'media_type' => "image",
        ];

        $dataTemp = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'screen' => $notification->msg_type,
            'object' => json_encode($message)
        ];


        if (!empty($notification->msg_type) && in_array($notification->msg_type, ['1', '2', '3'])) {
            $data = array(
                'token' => $receiver->device_token,
                'data' => $dataTemp,
                'notification' => array(
                    'title' => config('app.name'),
                    'body' => $notification->message,
                    'sound' => 'ezzycare_ringtone.wav',
                    'android_channel_id' => 'ezzycare_channel_1',
                )
            );
        } else if (!empty($notification->msg_type) && in_array($notification->msg_type, ['4', '5', '6'])) {
            $data = array(
                'token' => $receiver->device_token,
                'data' => $dataTemp,
                'notification' => array(
                    'title' => config('app.name'),
                    'body' => $notification->message,
                    'sound' => 'ezzycare_ringtone.wav',
                    'android_channel_id' => 'ezzycare_channel_2',
                )
            );
        } else {
            $data = array(
                'token' => $receiver->device_token,
                'data' => $dataTemp,
                'notification' => array(
                    'title' => config('app.name'),
                    'body' => $notification->message
                )
            );
        }


        // if (!empty($data)) {
        //     self::sendCurlRequest($url, $data);
        // }
        if (!empty($data) && !empty($receiver) && !empty($receiver->device_token)) {
            self::sendNotificationWithAdminSDK($data);
        }
        return true;
    }

    /**
     * sending firebase notification using topic
     */
    public static function sendNotificationTopicWise($notification, $topic_name = 'ezzycare')
    {
        // $url = 'https://fcm.googleapis.com/fcm/send';
        // $serverApiKey = config('app.FCM_KEY');

        $message = [
            'type' => $notification['type'],
        ];

        $dataTemp = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'object' => json_encode($message)
        ];

        // $data = [
        //     "to" => "/topics/" . $topic_name,
        //     'notification' => [
        //         'title' => $notification['title'],
        //         'body' => $notification['message'],
        //     ],
        //     'data' => $dataTemp,
        // ];

        $data = [
            "topic" => $topic_name,
            'notification' => [
                'title' => $notification['title'],
                'body' => $notification['message'],
            ],
            'data' => $dataTemp,
            "android" => [
                "notification" => [
                    "image" => (!empty($notification['imageUrl'])) ? $notification['imageUrl'] : ''
                ]
            ],
            "apns" => [
                "payload" => [
                    "aps" => [
                        "mutable-content" => 1
                    ]
                ],
                "fcm_options" => [
                    "image" => (!empty($notification['imageUrl'])) ? $notification['imageUrl'] : ''
                ]
            ],
        ];

        // self::sendCurlRequest($url, $data);
        if (!empty($data)) {
            self::sendNotificationWithAdminSDK($data);
        }
    }


    /**
     * Subscribe firebase topic
     */
    public static function subscribeNotificationTopic($notification_tokens, $topic_name = 'ezzycare')
    {
        // $url = 'https://iid.googleapis.com/iid/v1:batchAdd';

        // $data = [
        //     "to" => "/topics/" . $topic_name,
        //     "registration_tokens" => [$notification_tokens]
        // ];

        // self::sendCurlRequest($url, $data);

        $data = [
            "topic_name" => $topic_name,
            "registration_tokens" => [$notification_tokens]
        ];

        if (!empty($data)) {
            self::sendNotificationWithAdminSDK($data, 1);
        }
    }

    /**
     * Unsubscribe firebase topic
     */
    public static function unsubscribeNotificationTopic($notification_tokens, $topic_name = 'ezzycare')
    {
        // $url = 'https://iid.googleapis.com/iid/v1:batchRemove';

        // $data = [
        //     "to" => "/topics/" . $topic_name,
        //     "registration_tokens" => [$notification_tokens]
        // ];

        // self::sendCurlRequest($url, $data);

        $data = [
            "topic_name" => $topic_name,
            "registration_tokens" => [$notification_tokens]
        ];

        if (!empty($data)) {
            self::sendNotificationWithAdminSDK($data, 2);
        }
    }

    public static function unsubscribeAllNotificationTopic($notification_tokens)
    {
        // $url = 'https://iid.googleapis.com/iid/v1:batchRemove';

        // $data = [
        //     "to" => "/topics/" . $topic_name,
        //     "registration_tokens" => [$notification_tokens]
        // ];

        // self::sendCurlRequest($url, $data);

        $data = [
            "registration_tokens" => [$notification_tokens]
        ];

        if (!empty($data)) {
            self::sendNotificationWithAdminSDK($data, 3);
        }
    }

    /**
     * check notification
     */
    public static function sendOfflineChatNotification($notification, $receiver, $sender = '', $unreadNotification = 0)
    {
        // $url = 'https://fcm.googleapis.com/fcm/send';
        // $serverApiKey = config('app.FCM_KEY');

        $parameter = json_decode($notification['parameter'], true);
        $image = (isset($parameter['notification_image']) && $parameter['notification_image'] != '') ? $parameter['notification_image'] : '';

        $message = [
            'message' => $notification['message'],
            'parameter' => json_decode($notification['parameter'], true),
            'sender_id' => $notification['sender_id'],
            'sender_name' => (!empty($sender)) ? $sender->user_name : '-',
            'receiver_id' => $notification['receiver_id'],
            'type' => $notification['msg_type'],
            'sender_avatar' => (!empty($sender)) ? $sender->profile_image : asset('/admin/images/avatar.jpg'),
            'attachment' => '',
            'notification_count' => $unreadNotification,
            'media_type' => "image",
        ];

        $dataTemp = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'screen' => $notification['msg_type'],
            'object' => json_encode($message)
        ];

        if (!empty($notification['msg_type']) && in_array($notification['msg_type'], ['1', '2', '3'])) {
            $data = array(
                'token' => $receiver->device_token,
                'data' => $dataTemp,
                'notification' => array(
                    'title' => config('app.name'),
                    'body' => $notification['message'],
                    'sound' => 'ezzycare_ringtone.wav',
                    'android_channel_id' => 'ezzycare_channel_1',
                )
            );
        } else if (!empty($notification['msg_type']) && in_array($notification['msg_type'], ['4', '5', '6'])) {
            $data = array(
                'token' => $receiver->device_token,
                'data' => $dataTemp,
                'notification' => array(
                    'title' => config('app.name'),
                    'body' => $notification['message'],
                    'sound' => 'ezzycare_ringtone.wav',
                    'android_channel_id' => 'ezzycare_channel_2',
                )
            );
        } else {
            $data = array(
                'token' => $receiver->device_token,
                'data' => $dataTemp,
                'notification' => array(
                    'title' => config('app.name'),
                    'body' => $notification['message'],
                )
            );
        }


        // Log::info('data'.json_encode($data));
        // if(!empty($data)){
        //      self::sendCurlRequest($url, $data);
        // }

        if (!empty($data) && !empty($receiver) && !empty($receiver->device_token)) {
            self::sendNotificationWithAdminSDK($data);
        }

        return true;
    }


    /**
     * check notification
     */
    public static function checkNotification()
    {
        $notification_token = "cYTr7i8IO0xXpK4ONrZJC1:APA91bHIk9ebOq-Vme7mYLhQpxbeD4_TYLc4A5eaFSOoEigf-75jmsh6Pwen8ciLrKAulZSr-mzW4ycKv774lK0iPAsMkthF79vkZg5rjuIxQBoH0F72_QfmT7r2b_hEIFGGYa6PZxuK";
        $url = 'https://fcm.googleapis.com/fcm/send';

        $message = [
            'message' => 'This is test Notificationas',
            'parameter' => "",
            'sender_id' => "",
            'sender_name' => "",
            'receiver_id' => "",
            'type' => "99",
            'sender_avatar' => "",
            'attachment' => '',
            'notification_count' => "0",
            'media_type' => "image",
            'TTL' => "5"
        ];

        $dataTemp = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'screen' => '91',
            'object' => json_encode($message),
            'TTL' => "5"
        ];


        $data = array(
            'token' => $notification_token,
            'data' => $dataTemp,
            'notification' => array(
                'title' => config('app.name'),
                'body' => 'This is test Notificationas',
                'sound' => 'ezzycare_ringtone.wav',
                'android_channel_id' => 'ezzycare_channel_1',
                'TTL' => "5"
            )
        );
        // self::sendCurlRequest($url, $data);
        if (!empty($data)) {
            self::sendNotificationWithAdminSDK($data);
        }
    }

    public static function checkNotificationTopicWise($notification, $topic_name = 'ezzycare')
    {
        // $url = 'https://fcm.googleapis.com/fcm/send';
        // $serverApiKey = config('app.FCM_KEY');

        $message = [
            'type' => '0',
        ];

        $dataTemp = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'object' => json_encode($message)
        ];

        // $data = [
        //     "to" => "/topics/" . $topic_name,
        //     'notification' => [
        //         'title' => $notification['title'],
        //         'body' => $notification['message'],
        //     ],
        //     'data' => $dataTemp,
        // ];

        $data = [
            "topic" => $topic_name,
            'notification' => [
                'title' => 'This is test Notificationas',
                'body' => 'This is test Notificationas',
            ],
            'data' => $dataTemp,
            "android" => [
                "notification" => [
                    "image" => (!empty($notification['imageUrl'])) ? $notification['imageUrl'] : ''
                ]
            ],
            "apns" => [
                "payload" => [
                    "aps" => [
                        "mutable-content" => 1
                    ]
                ],
                "fcm_options" => [
                    "image" => (!empty($notification['imageUrl'])) ? $notification['imageUrl'] : ''
                ]
            ],
        ];

        // self::sendCurlRequest($url, $data);
        if (!empty($data)) {
            self::sendNotificationWithAdminSDK($data, 4);
        }
    }
    public static function getEmailVerification($email)
    {
        $url = config('app.EMAIL_VERIFICATION_URL') . "/v1/verify?email=" . $email . "&apikey=" . config('app.EMAIL_VERIFICATION_API_KEY');
        $response = self::sendCurlRequestPaystack($url, '', 'GET');
        Log::info('getEmailVerification');
        Log::info($url);
        Log::info($response);
        if (!empty($response) && !empty($response['result']) && $response['result'] != 'valid') {
            return ['status' => false, 'msg' => "This Email Id is not valid please try again."];
        } else if (!empty($response) && !empty($response['disposable']) && $response['disposable'] == 'true') {
            return ['status' => false, 'msg' => "This Email Id is not valid please try again."];
        } else if (!empty($response) && !empty($response['safe_to_send']) && $response['safe_to_send'] != 'true') {
            return ['status' => false, 'msg' => "This Email Id is not valid please try again."];
        } else if (!empty($response) && !empty($response['success']) && $response['success'] == 'false') {
            return ['status' => false, 'msg' => "This Email Id is not valid please try again."];
        }else{
            return ['status' => true, 'data' => $response];
        }
    }


    public static function sendNotificationWithAdminSDK($notificationData, $type = 0)
    {
        // type 0-notification send, 1-subscribe topic, 2-unsubscribe topic, 3-unsubscribe all topic,  3-topic wise notification
        try {

            $service_account = [
                "type" => "service_account",
                "project_id" => "ezzycare-623a3",
                "private_key_id" => "318c682fff849672497a77a5d9c350e010721639",
                "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC0B6yK57RbPEZy\n/lAh/8YNJ3ixhYRNd2hylmGoek00Ew9QX30ujBm70ugz7wuAciZY08VjMj86IzOy\nUxMYnxUVAR6sD8NtXvD65MW9iTp2qSoKOBGiCX985TWdvjf/wPar8tiiUQIdmZRl\nmBBd+6z5YVdiaKyA7C33uV+504R6C7Ll1UicrH7bdopUgIlkeqM/5PJX7nf7NoBt\nhMSZl/wcQ8ycmKzO81ZsMwBuBzv6/nmQw2Z6nxpKEw/S3mtUfVnZExotwIlGlP5u\nk6FExJBtpLR9CjVdJbEByrRXvZAkWX2fnwI2iGmmzU+WYsshak7rcJvbvpBqilML\nK2I/RL4hAgMBAAECggEADI60ea6WeqlbqKMV59PgIVlte8R6aVo444qbHpYD/evj\nwjwcOTuggo9q5E8k7ZKzcLgPfqR/Rd41ZEI92hZ5RqZ2Az5zkpTFblq4HZ5i1kAP\nz01V6DLivPgn+JbPb9F/40KPMH3suIUr6reOp1+UvdhiW1o9yMhbSc+ZCRc4mGKh\nIc0cS2ng9UF4o0F6vFK9RXDZlB9xZ9m8obdA/UeoVsQOY3OvCwVTUT8Z0Mgj1F7C\nM9l9iEnTxxfZKll+ZPWgGTBEvUoPcWrkuiMPFb9dbdGoLk+w/5VihEfEIOGJhXaf\nkZwat9gGOW2T5kvp9s2LxGV190xcqHBxmrYeKO+TGQKBgQDpRArsG101fpPzqSzs\nMhYG0jfR3m5ddEZYTwJul62o8JgvDXkkY2Qjff1MG0le9AK8aw7cxkyQbAS0WRQg\nMNWpkJrY6I8O2BHw0f89GoFIejaM1mpXVj59ulyDDQb5Td9NPeGv2kFVYAw60/O/\nGI02gF8GKAGfLZKDK/PXqN0YZwKBgQDFk2c74hHtAmaIhCgVPLB1l0J7Gs0c6zDd\n94npok3EWAnbVlV9hDdx4ZFDSDdG6mPLVmf2giTWgAhLcsWVyDkCJgbzMSy7rwGl\nIWOaEVX09y10cuyD+vjajfnft8jvYChLeSqUjoZubn0iIdU3Q8RZKKg+tVzFqWY0\n3uWBwUeANwKBgQCTTTV8fZcxlboGLzm/+azxJ8S6EbUt7KQTVelaPwwZ17yyUdbD\nBMSSqRfP/Jcrj/k+VHixL8Pfm2apIGtWHKCAEGHIQas9G3LQ2TtNsbQcOZjC/Q9w\nEXUq3glXdF2IBwXQ+BfRfYiuShXO/FM6xF1AInZfI9pKU3Pmw1WbRPZBVwKBgDgl\na3DZDb8Mr5ab98gRNxQzp/DT7PYK4Bg0AD0konTpj+OE5UaGDDuQnndATnUYpsNi\nCqlC0rUiLTlDpGKsQ4cYx2DU2KF9WjpTArsFsierFn/BKPVYnN9++UNaNv/Pk6Pf\nCvqshgdb3rOUacMKvwGXTDiF0ZdaTHE55n65LliBAoGAVkrJRtuxH7n5viNKq21c\nJKxPHSezVmEhz83Zm5BsJ2ih9f1LiiH7SpFctZPdk+QbIqBY1+du97/zZnP+vZSw\nIllEOw7q8Vyx86RVIfLfWjJzUa8n73Qkv4clDADenUWVvksgjm9wCU+9OMb7FlQx\nnCSyCzcffteI8noCpQmOVqM=\n-----END PRIVATE KEY-----\n",
                "client_email" => "firebase-adminsdk-n3mcl@ezzycare-623a3.iam.gserviceaccount.com",
                "client_id" => "108937265968054768002",
                "auth_uri" => "https=>//accounts.google.com/o/oauth2/auth",
                "token_uri" => "https=>//oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url" => "https=>//www.googleapis.com/oauth2/v1/certs",
                "client_x509_cert_url" => "https=>//www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-n3mcl%40ezzycare-623a3.iam.gserviceaccount.com",
                "universe_domain" => "googleapis.com"
            ];

            $factory = (new Factory)->withServiceAccount($service_account);
            $recipient = 'device_token_or_topic_name';
            if ($type == '1') {
                // subscribe topic
                $messaging = $factory->createMessaging();
                // Log::info(['notificationData', $notificationData]);
                $messaging->subscribeToTopic($notificationData['topic_name'], $notificationData['registration_tokens']);
                // Log::info('subscribeToTopic');
            } else if ($type == '2') {
                // unsubscribe topic
                $messaging = $factory->createMessaging();
                // Log::info(['notificationData', $notificationData]);
                $messaging->unsubscribeFromTopic($notificationData['topic_name'], $notificationData['registration_tokens']);
                // Log::info('unsubscribeFromTopic');
            } else if ($type == '3') {
                // unsubscribe all topic
                $messaging = $factory->createMessaging();
                // Log::info(['notificationData', $notificationData]);
                $messaging->unsubscribeFromAllTopics($notificationData['registration_tokens']);
                // Log::info('unsubscribeFromAllTopics');
            } else if ($type == '4') {
                // topic wise send notification
                $messaging = $factory->createMessaging();
                // Log::info(['notificationData', $notificationData]);
                $cloudMessage = CloudMessage::fromArray($notificationData);
                Log::info(['cloudMessage', json_encode($cloudMessage)]);
                $messaging->send($cloudMessage);
                // Log::info('topic notification sended');
            } else {
                $messaging = $factory->createMessaging();
                Log::info(['notificationData', $notificationData]);
                $cloudMessage = CloudMessage::fromArray($notificationData);
                Log::info(['cloudMessage', json_encode($cloudMessage)]);
                // $messaging->withNotificationToken('device_token');
                $messaging->send($cloudMessage);
                Log::info('notification sended');
            }

            return true;
        } catch (Throwable $th) {
            Log::info('$th');
            Log::info($th);
        }
    }


    /**
     * sending curl request
     */
    public static function sendCurlRequest($url, $data)
    {
        $serverApiKey = config('app.FCM_KEY');
        if (!empty($url)) {
            $headers = array('Content-Type:application/json', 'Authorization:key=' . $serverApiKey);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($headers)
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);
            $response_arr = json_decode($response, true);
            // Log::info('sendCurlRequest '.$response);
            $error_msg = '';
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            }
            // Log::info('sendCurlRequest start');
            // Log::info($url);
            // Log::info($data);
            // Log::info($response);
            // Log::info($error_msg);
            if (isset($response_arr['success']) && $response_arr['success'] == 0) {
                // Log::info($response);
                // Log::info('Push Notification Send Failed');
            }
        }
        return true;
    }

    /**
     * msg sending curl request
     */
    // public static function sendBULKSMSRequest($url, $headers, $data) 
    public static function sendBULKSMSRequest($url)
    {
        Log::info($url);
        $headers = array('Accept: application/json', 'Content-Type: application/json');
        if (!empty($url)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            // curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $response = curl_exec($ch);
            $response_arr = json_decode($response, true);
            Log::info($response_arr);
            if (!empty($response_arr['results']) && !empty($response_arr['results'][0]) && $response_arr['results'][0]['smscount'] == '0') {
                // if(!empty($response_arr['error'])) {
                Log::info($response);
                Log::info('SMS Send Failed');
                // return $response_arr['error'];
                return $response_arr['results'][0]['reason'];
            }
            return true;
        }
        $response_arr = 'SMS Send Failed';
        return $response_arr;
    }

    /**
     * sending curl request paystack
     */
    public static function sendCurlRequestPaystack($url, $headers, $method = 'GET', $data = '')
    {
        if (!empty($url) && !empty($headers)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($headers)
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if ($method == 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            } else {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $response_arr = json_decode($response, true);
            return $response_arr;
        }
        return true;
    }
    public static function countryCodeRestriction($countryCode)
    {
        $retrictCountryCode = ['+992', '992'];
        if (!empty($countryCode) && in_array($countryCode, $retrictCountryCode)) {
            return true;
        }
        return false;
    }

    public static function mobileNoVerify($number, $country_code)
    {
        if (!empty($number) && !empty($country_code)) {
            // Remove the plus sign and country code
            $countryCode = str_replace('+', '', $country_code);
            $access_key = config('app.MOBILE_VERIFICATION_API_KEY');
            $url = config('app.MOBILE_VERIFICATION_API_URL') . '/number_verification/validate?number=' . $countryCode . $number;
            $headers = array('apikey:' . $access_key);
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);
            $error_msg = '';
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);
            Log::info('mobileNoVerify');
            Log::info($url);
            Log::info($headers);
            Log::info($response);
            Log::info($error_msg);
            $validationResult = json_decode($response, true);
            Log::info($validationResult);
            return $validationResult;
        }
    }
}
