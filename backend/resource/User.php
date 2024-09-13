<?php

namespace backend\resource;

class User extends \common\models\User
{
    public function fields()
    {
        return ['id', 'username'];
    }

    public function extraFields()
    {
        return ['email'];
    }



}