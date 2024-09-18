<?php

namespace backend\controllers;

use backend\resource\Post;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class PostController extends MyActiveController
{
    public $modelClass = Post::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

        return $actions;
    }

    public function actionIndex()
    {
        $cache = Yii::$app->cache;
        $cacheKey = 'post_index';

        $data = $cache->get($cacheKey);
        if ($data === false) {
            $data = Post::find()->asArray()->all();

            $cache->set($cacheKey, $data, 3600);
        }


        return $data;
    }

    public function actionView($id)
    {
        $cache = Yii::$app->cache;
        $cacheKey = 'post-' . $id;

        $callback = function () use ($id) {
            return Post::findOne($id);
        };
        return $cache->getOrSet($cacheKey, $callback, 3600);
    }

    public function actionCreate()
    {
        $model = new Post();
        $model->load(Yii::$app->request->post(), '');

        if ($model->validate()) {
            if ($model->save()) {
                Yii::$app->cache->delete('post-index');
                Yii::$app->response->statusCode = 201;
                return $model;
            }

            throw new ServerErrorHttpException('Failed to create the post for unknown reasons.');
        }

        return ['errors' => $model->errors];
    }

    public function actionUpdate($id)
    {
        $model = Post::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException("Post with ID $id not found");
        }
        $model->load(Yii::$app->request->post(), '');

        if ($model->validate()) {
            if ($model->save()) {
                Yii::$app->cache->delete('post-'.$id);
                Yii::$app->cache->delete('post-index');
                return $model;
            }
            throw new ServerErrorHttpException('Failed to update the Post for unknown reasons');
        }
        return ['errors' => $model->errors];
    }

    public function actionDelete($id)
    {
        $model = Post::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException("Post with ID $id not found");
        }

        if ($model->delete() !== false) {
            Yii::$app->cache->delete('post-'.$id);
            Yii::$app->cache->delete('post-index');

            Yii::$app->response->statusCode = 204;
            return ;
        }

        throw new ServerErrorHttpException('Failed to delete the Post for unknown reasons');
    }
}
