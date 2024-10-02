<?php

namespace console\controllers;

use yii\console\Controller;
use Google_Client;

class PushNotificationController extends Controller
{
    private $fcmUrl = 'https://fcm.googleapis.com/v1/projects/push-notification-40b22/messages:send';
    private $serviceAccountPath = __DIR__ . '/client_account.json'; // Путь относительно текущего файла

    public function actionSend()
    {
        $token = "cyLgWyAQZH7-5Z0yyr9GdL:APA91bENcPtjhnEbm9ofrZouC_SvtMH3skwsmMje3oGai_ypz4Aj_uOm7t1QGpoQeE6VwcP0uzEqqPwEGUOFUnA2CC4mslOEc5Vl5psDc-PCjXyVszWCldYT59palX1gaWfgBOQ11Nga";
        // Инициализация Google клиента
        $client = new Google_Client();
        $client->setAuthConfig($this->serviceAccountPath); // Указываем путь к JSON с использованием __DIR__
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        // Получение OAuth токена
        $accessToken = $client->fetchAccessTokenWithAssertion()['access_token'];

        // Данные для отправки
        $payload = [
            "message" => [
                "token" => $token,
                "notification" => [
                    "title" => "Тестове повідомлення ",
                    "body" => "Привіт з міста Суми!",
                ],
                // Дополнительно можно добавить данные

            ]
        ];

        $post_data = json_encode($payload);
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        // Инициализация cURL
        $ch = curl_init($this->fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        // Выполнение запроса
        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Ошибка cURL: ' . curl_error($ch) . "\n";
        } else {
            echo "Ответ от FCM: " . $response . "\n";
        }

        // Закрытие cURL
        curl_close($ch);
    }
}
