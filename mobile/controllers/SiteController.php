<?php

namespace mobile\controllers;


use mobile\models\Tz;
use Yii;
use yii\rest\Controller;


class SiteController extends Controller
{

    /**
     * ГЕЙТ начальной загрузки.
     * =
     * Проверка работоспособности API
     * -
     *
     */
    public function actionIndex()
    {
        return [
            'text_command' => 'Site',
            'str_model' => 'test',
        ];

    }


    /**
     * Главный тестовый ГЕЙТ.
     * =
     * Проверка работоспособности API
     * -
     *
     */
    public function actionIndex_id()
    {
        $para = Yii::$app->request->get();

        //        $start_list = [];

        if (isset($para['command']) && !empty($para['command'])) {

            // ALL
            if ($para['command'] == "gettz_all") {

                $start_list = Tz::find_list();

                return [
                    'text_command' => ' ALL ',
                    'start_list' => $start_list,
                ];


            }

            /// ONE
            if ($para['command'] == "gettz_one") {
                if (isset($para['id']) && !empty($para['id'])) {
                    $id = $para['id'];
                    $start_list = Tz::find_one($id);

                    return [
                        'text_command' => ' ONE ',
                        'start_list' => $start_list,
                    ];

                }
            }

        }


        // Слишком много
        //        $start_list = Tz::find_array_all_list();
        // OK

        return [
            'text_command' => ' Site ',
            'str_model' => 'test',
//            'para' => $para,
//            'start_list' => $start_list
        ];
    }


    public function actionAuth()
    {
        return 'Auth';
    }


    public function actionError()
    {
        return 'Error';
    }


    public function actionLogin()
    {
        $model = new LoginForm();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($token = $model->auth()) {
            return $token;
        } else {
            return $model;
        }
    }


    protected function verbs()
    {
        return [
            'login' => ['post'],
        ];
    }


    public function actionSel($id)
    {
        if (isset($id)) {
            return $id;
        }

        return 'api NOT ID';
    }


}
