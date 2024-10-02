<?php

namespace frontend\controllers;

use Exception;
use Yii;
use yii\rest\Controller;

class FirebaseController extends Controller
{
    public function actionSaveToken()
    {
        try {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $token = Yii::$app->request->post('token');

            if ($token && !Yii::$app->user->isGuest) {
                $user = Yii::$app->user->identity;
                $user->firebase_token = $token;
                if ($user->save()) {
                    return ['success' => true, 'message' => 'Token saved successfully'];
                } else {
                    return ['success' => false, 'message' => 'Failed to save token'];
                }
            }

            return ['success' => false, 'message' => 'Invalid request'];
        } catch (Exception $e) {
            return ['globalError' => $e];
        }
    }
}