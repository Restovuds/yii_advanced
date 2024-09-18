<?php

namespace backend\controllers;

use backend\resource\Comment;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class CommentController extends MyActiveController
{
    public $modelClass = Comment::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        unset($actions['view']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
//        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }


    public function prepareDataProvider()
    {
        return new ActiveDataProvider([
            'query' => Comment::find()->andWhere(['post_id' => Yii::$app->request->get('postId')])
        ]);
    }

    public function actionIndex()
    {
        $cache = Yii::$app->cache;
        $cacheKey = 'post-' . Yii::$app->request->get('postId') . '-comments';

        $dataProvider = $cache->get($cacheKey);
        if ($dataProvider === false) {
            $dataProvider = $this->prepareDataProvider();
            $cache->set($cacheKey, $dataProvider, 3600);
        }
        return $dataProvider;
    }

    public function actionView($id)
    {
        $cache = Yii::$app->cache;
        $cacheKey = 'comment-' . $id;

        $callback = function () use ($id) {
            return Comment::findOne($id);
        };
        return $cache->getOrSet($cacheKey, $callback, 3600);
    }

    public function actionCreate()
    {
        $model = new Comment();
        $model->load(Yii::$app->request->post(), '');

        if ($model->validate()) {
            if ($model->save()) {
                Yii::$app->cache->delete('post-'.$model->post_id.'-comments');
                Yii::$app->response->statusCode = 201;
                return $model;
            }

            throw new ServerErrorHttpException('Failed to create the post for unknown reasons.');
        }

        return ['errors' => $model->errors];
    }

    public function actionUpdate($id)
    {
        $model = Comment::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException("Post with ID $id not found");
        }
        $model->load(Yii::$app->request->post(), '');

        if ($model->validate()) {
            if ($model->save()) {
                Yii::$app->cache->delete('post-'.$model->post_id.'-comments');
                Yii::$app->cache->delete('comment-' . $id);
                return $model;
            }
            throw new ServerErrorHttpException('Failed to update the Post for unknown reasons');
        }
        return ['errors' => $model->errors];
    }

    public function actionDelete($id)
    {
        $model = Comment::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException("Post with ID $id not found");
        }

        if ($model->delete() !== false) {
            Yii::$app->cache->delete('post-' . $model->post_id.'-comments');
            Yii::$app->cache->delete('comment-' . $id);

            Yii::$app->response->statusCode = 204;
            return ;
        }

        throw new ServerErrorHttpException('Failed to delete the Post for unknown reasons');
    }
}
