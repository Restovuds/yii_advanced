<?php

namespace backend\controllers;

use backend\resource\Post;

class PostController extends MyActiveController
{
    public $modelClass = Post::class;
}
