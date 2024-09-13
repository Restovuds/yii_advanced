<?php

namespace backend\resource;

use backend\resource\Comment;
use backend\resource\User;

class Post extends \common\models\Post
{
    public function fields()
    {
        return ['id', 'body', 'created_at', 'updated_at', 'createdBy'];
    }

    public function extraFields()
    {
        return ['comments'];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CommentQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }


    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
}
