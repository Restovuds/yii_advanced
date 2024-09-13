<?php

namespace backend\controllers;

use backend\resource\Comment;
use backend\resource\Post;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class MyActiveController extends ActiveController
{

    /**
     * @return array|array[]
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['only'] = ['create', 'update', 'delete'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::class
        ];

        return $behaviors;
    }


    /**
     * @param string $action
     * @param Comment | Post $model
     * @param $params
     * @return void
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['create', 'update', 'delete']) && $model->created_by !== Yii::$app->user->id)
        {
            throw new ForbiddenHttpException("You do not have permissions to change this record");
        }
    }
}