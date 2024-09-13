<?php

namespace backend\controllers;

use backend\resource\Comment;
use Yii;
use yii\data\ActiveDataProvider;

class CommentController extends MyActiveController
{
    public $modelClass = Comment::class;

    public function actions()
    {
        $actions = parent::actions();
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        return new ActiveDataProvider([
            'query' => Comment::find()->andWhere(['post_id' => Yii::$app->request->get('postId')])
        ]);
    }
}
