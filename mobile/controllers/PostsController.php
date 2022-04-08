<?php

namespace mobile\controllers;


//use Yii;
use yii\rest\Controller;


class PostsController extends Controller
{
    public function actionIndex()
    {
        return [
            'text_command' => 'POSTS ',
            'str_model' => 'test',
        ];
    }
    
}
