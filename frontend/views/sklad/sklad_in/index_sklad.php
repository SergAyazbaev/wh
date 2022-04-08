<?php

//use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


$this->title = 'Склад № ' . $sklad;
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    .photo {
        display: block; /* Строчно-блочный элемент */
        position: initial;

    }

    .photo:hover::after {
        content: attr(data-title); /* Выводим текст */
        z-index: 991; /* Отображаем подсказку поверх других элементов */
        /*background: rgba(0,42,167,0.6); !* Полупрозрачный цвет фона *!*/
        background-color: rgba(137, 144, 164, 0.89);
        color: #fff; /* Цвет текста */
        text-align: center; /* Выравнивание текста по центру */
        font-family: Arial, sans-serif; /* Гарнитура шрифта */
        font-size: 18px; /* Размер текста подсказки */
        padding: 20px 40px; /* Поля */
        border: 1px solid #333; /* Параметры рамки */
        border-radius: 10px;

        display: block;
        position: absolute;
        right: 20px;
        top: 100px;
        min-width: 200px;
    }
</style>


<div class="table_with">

    <?= Html::a(
        'Создать новую накладную', ['create_new'], [
            'class' => 'btn btn-success',
            'target' => "_blank",
            'name' => 'contact-button',
            'value' => 'add_button',
        ]
    );
    ?>

    <?= Html::a(
        'Принять остатки из ЦС', ['sklad_cs/create_from_cs'], [
            'class' => 'btn btn-success',
            'target' => "_blank",
        ]
    );
    ?>


    <?php
    //    if (
    //        Yii::$app->user->identity->group_id == 40 || Yii::$app->user->identity->group_id ==
    //        100
    //    ) {
    //        echo Html::a(
    //            'Поиск-Крыж', ['sklad_check/find_check'], [
    //                'class' => 'btn btn-success',
    //                'target' => "_blank",
    //            ]
    //        );
    //    }
    ?>


    <?php
    if (
        Yii::$app->user->identity->group_id == 100 || Yii::$app->user->identity->group_id ==
        40 || Yii::$app->user->identity->group_id == 65 || Yii::$app->user->identity->group_id ==
        71
    ) {

        echo " ";

        echo Html::a(
            'Остатки ЦС по Штрихкоду ', ['sklad_cs/from_cs_one_barcode'], [
                'class' => 'btn btn-success',
                'target' => "_blank",
            ]
        );
    }

    ///
    if (
        Yii::$app->user->identity->group_id == 100 || // Админ
        Yii::$app->user->identity->group_id == 65 || // Наташа, Назира, Айдана
        Yii::$app->user->identity->group_id == 71       // Талгат
    ) {

        echo " ";




        $form = ActiveForm::begin(
            [
//                'id' => 'project-form',

                'action' => ['copypast'],

                'method' => 'POST',
                'options' => [
//                    'data-pjax' => 'pjax-sklad',
                    'autocomplete' => 'off'
                ],
//                'enableClientValidation' => false,
//                'validateOnChange' => true,
//                'validateOnSubmit' => true,
            ]
        );


        //////////////////////////////////////
        ///
        /// МОДАЛЬНОЕ ОКНО. ИМПУТ под КОПИПАСТ - ПАРК
        ///

        Modal::begin(
            [
                'header' => '<h2>Залить Остатки ЦС от Наташи из Эксела</h2>',
                'toggleButton' => [
                    'label' => 'Копипаст.Остатки ЦС от Наташи из Эксела',
                    'tag' => 'button',
                    'class' => 'btn btn-primary',
                ],//'footer' => 'Низ окна',
            ]
        );

        ?>


        <?=

        $form
            ->field($model, 'add_copypast')
            ->textarea(
                ['autofocus' => true,
                    'style' => 'height: 400px;width: 799px;font-size: 12px;',
                    'placeholder' => "Cтолбцы:\n" .
                        "\n1.Дата \n2.№ Акт\n3.Название док\n4.АП\n5.ГосНомер\n6.Борт\n7.Наим.оборуд.\n8.Штрих-код\n9.Кол-во \n10.Цена"]
            )
            ->label(false);

        ?>


        <div class="form-group">
            <?=

            Html::submitButton(
                'Добавить ( залить ) копипаст ',
                [
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'value' => 'add_copypast'
                ]
            )

            ?>

        </div>


        <?php Modal::end(); ///////////////////////////////////

        ?>


        <?php
        ActiveForm::end();

        ?>

        <?php

    }

    ?>


    <?php
    Pjax::begin([
        'id' => 'pjax-sklad',
    ]);

    ///MULTY SORT in ONE PAGE
    $dataProvider_sklad->pagination->pageParam = 'sklad-page';
    $dataProvider_sklad->sort->sortParam = 'sklad-sort';

    ?>

    <?= GridView::widget(
        [
            'dataProvider' => $dataProvider_sklad,
            'filterModel' => $searchModel_sklad,
            'columns' => [
                [
                    'header' => '',
                    'format' => 'raw',
                    'contentOptions' => ['style' => ' width: 50px;'],
                    'content' => function ($model) {
                        $text_ret = '';

                        // SKLAD (Viktor)
                        $url = Url::to(['/sklad_update/update?id=' . $model->_id . '&sklad=' . $model->wh_home_number]);

                        $text_ret .= ' ' . Html::a(
                                'Вн', $url, [
                                    'class' => 'btn btn-success btn-xs',
                                  //  'data-pjax' => 0,
                                    'target' => "_blank",
                                    'data-id' => $model->_id,
                                ]
                            );


                        // SKLAD - ERDOS, SASHA
//                        if (Yii::$app->user->identity->group_id == 10) {
//                            $url = Url::to(['/rem_sklad/index?id=' . $model->id]);
//
//                            $text_ret .= ' ' . Html::a(
//                                    'REM', $url, [
//                                        'class' => 'btn btn-success btn-xs',
//                                        'data-pjax' => 0,
//                                        'target' => "_blank",
//                                        'data-id' => $model->_id,
//                                    ]
//                                ) . ' ';
//                        }

                        // SKLAD - PROSHIVKA (Asemtai)
                        if (Yii::$app->user->identity->group_id == 41) {
                            $url = Url::to(['/sklad/rewrite_update?id=' . $model->_id . '&sklad=' . $model->wh_home_number]);

                            $text_ret .= ' ' . Html::a(
                                    'Asemtai', $url, [
                                        'class' => 'btn btn-success btn-xs',
                                   //     'data-pjax' => 0,
                                        'target' => "_blank",
                                        'data-id' => $model->_id,
                                    ]
                                ) . ' ';
                        }
                        // SKLAD - PROSHIVKA - THA (Slavik - THA Agent)
                        if (Yii::$app->user->identity->group_id == 42) {
                            $url = Url::to(['/sklad/mail_to_tha?id=' . $model->_id . '&sklad=' . $model->wh_home_number]);

                            $text_ret .= ' ' . Html::a(
                                    'ТХА', $url, [
                                        'class' => 'btn btn-success btn-xs',
                                    //    'data-pjax' => 0,
                                        'target' => "_blank",
                                        'data-id' => $model->_id,
                                    ]
                                );
                        }


//						// SKLAD= Снятие(Замена)/Установка(Замена)
//						if(
//							Yii::$app->user->identity->group_id == 100 ||
//							Yii::$app->user->identity->group_id == 71
//						)
//						{
//							$url = Url::to( [ '/sklad_change_pe/update?id=' . $model->_id . '&sklad=' . $model->wh_home_number ] );
//
//							$text_ret .= ' ' . Html::a(
//									'Замена', $url, [
//									'class'     => 'btn btn-success btn-xs',
//									'data-pjax' => 0,
//									'target'    => "_blank",
//									'data-id'   => $model->_id,
//
//								] );
//						}


                        return $text_ret;
                    },
                ],

                [
                    'attribute' => 'sklad_vid_oper',
                    'value' => 'sklad_vid_oper',
                    'filter' => [
                        //						'1' => "Инвентаризация",
                        '2' => "Приход",
                        '3' => "Расход",
                        //						'4' => 'Снятие(замена)',
                        //						'5' => 'Установка(замена)',
                    ],
                    'content' => function ($model) {
                        return ArrayHelper::getValue(
                            [
                                '' => ".",
                                //								'1' => "Инвентаризация",
                                '2' => "Приход",
                                '3' => "Расход",
                                //								'4' => 'Снятие(замена)',
                                //								'5' => 'Установка(замена)',
                            ], (string)$model['sklad_vid_oper']
                        );
                    },
                    'contentOptions' => ['style' => 'width:60px;overflow:hidden ;'],
                ],

                [
                    'attribute' => 'id',
                    'contentOptions' => ['style' => 'width:70px;overflow:hidden;'],
                    'content' => function ($model) {

                        /// ЕСЛИ это - РАСХОДНАЯ НАКЛАДНАЯ
                        if ((int)$model->sklad_vid_oper == 3 && empty($model->dt_transfered_date)) {
                            if (!isset($model->wh_cs_number) || empty($model->wh_cs_number) ||
                                $model->wh_cs_number == 0) {
                                $url = Url::to(['/sklad/copy-to-transfer?id=' . $model->_id]);

                                $text_ret = Html::a(
                                    $model->id, $url, [
                                        'data-confirm' => Yii::t('yii', 'Отправить накладную.<br><b>Вы точно хотите это сделать? '),
                                        'class' => 'btn btn-success btn-xs',
                                     //   'data-pjax' => 0,
                                        'data-id' => $model->_id,
                                    ]
                                );


                                return $text_ret;
                            }
                        }


                        //FOR ASEMTAI
                        if (Yii::$app->user->identity->group_id == 41) {
                            /// ЕСЛИ это - РАСХОДНАЯ НАКЛАДНАЯ
                            if ($model->sklad_vid_oper == 3 && empty($model->dt_transfered_date)) {
                                $url = Url::to(['/sklad/copy-to-transfer?id=' . $model->_id]);

                                $text_ret = Html::a(
                                    $model->id, $url, [
                                        'data-confirm' => Yii::t('yii', 'Отправить накладную.<br><b>Вы точно хотите это сделать? '),
                                        'class' => 'btn btn-success btn-xs',
                                    //    'data-pjax' => 0,
                                        'data-id' => $model->_id,
                                    ]
                                );

                                return $text_ret;
                            }
                        }


                        return $model->id;
                    },
                ],
                [
                    'attribute' => 'tz_id',
                    'contentOptions' => ['style' => 'width:50px;'],
                ],
                [
                    'attribute' => 'array_count_all',
                    'contentOptions' => ['style' => 'width:35px;overflow:hidden ;'],
                ],
                [
                    'attribute' => 'array_bus',
                    'contentOptions' => ['style' => ' width: 12px;'],
                    'content' => function ($model) {
                        //ddd($model);
                        if (isset($model->array_bus)) {
                            return count($model->array_bus);
                        } else {
                            return 0;
                        }
                    },
                ],
                [
                    //'header'      => 'Один день',
                    //'attribute'      => 'dt_start',
                    'attribute' => 'dt_one_day',
                    'value' => 'dt_create_timestamp',
                    'contentOptions' => ['style' => ' width: 120px;'],
                    'format' => [
                        'datetime',
                        'php:d.m.Y H:i:s',
                    ],
                    'filter' => DatePicker::widget(
                        [
                            'type' => DatePicker::TYPE_INPUT,
                            'attribute' => 'dt_start',
                            'language' => 'ru',
                            'name' => 'dt_start',
                            'model' => $searchModel_sklad,
                            'pluginOptions' => [
                                //'format' => 'd.m.Y',
                                'todayHighlight' => true,
                                'autoclose' => true,
                            ],
                            'options' => [
                                'placeholder' => 'Один день',
                                'autocomplete' => "off",
                            ],
                            'convertFormat' => false,
                        ]
                    ),
                ],

                [
                    'attribute' => 'wh_debet_name',
                    'contentOptions' => ['style' => 'min-width:60px;max-width:90px;overflow:hidden ;'],
                ],

                [
                    'attribute' => 'wh_debet_element_name',
                    'contentOptions' => ['style' => 'width:110px;overflow:hidden;'],
                    'content' => function ($model) {
                        //ddd($model); // wh_destination_element_name
                        return Html::a($model['wh_debet_element_name'], '#',
                            [
                                'class' => "photo",
                                'data-title' => $model['wh_debet_name'] . " - " . $model['wh_debet_element_name'],
                                'disabled' => "disabled"
                            ]);

                    },
                ],

                [
                    'attribute' => 'wh_destination_name',
                    'contentOptions' => ['style' => 'min-width:60px;max-width:90px;overflow:hidden ;'],
                ],

                [
                    'attribute' => 'wh_destination_element_name',
                    'contentOptions' => ['style' => 'width:110px;overflow:hidden ;'],
                    'content' => function ($model) {
                        //ddd($model); // wh_destination_element_name
                        return Html::a($model['wh_destination_element_name'], '#',
                            [
                                'class' => "photo",
                                'data-title' => $model['wh_destination_name'] . " - " . $model['wh_destination_element_name'],
                                'disabled' => "disabled"
                            ]);

                    },
                ],
                [
                    'attribute' => 'tx',
                    'contentOptions' => ['style' => 'max-width:90px;overflow: hidden;'],

                    'content' => function ($model) {
                        //ddd($model); // wh_destination_element_name
                        return Html::a($model['tx'], '#',
                            [
                                'class' => "photo",
                                'data-title' => $model['tx'],
                                'disabled' => "disabled"
                            ]);
                    },

                ],
                [
                    'header' => 'Цель.Парк-ЦС',
                    'attribute' => 'wh_dalee',
                    'contentOptions' => ['style' => ' max-width:90px;overflow:hidden;'],

                    'content' => function ($model) {
                        //ddd($nex1->grid->columns['11']['value']);
                        //ddd($model);

                        if (!isset($model->wh_dalee_name)) {
                            return '';
                        }

                        return $model->wh_dalee_name;
                    },
                ],
                [
                    'value' => 'sprwhelement_dalee_element.name',
                    'header' => ' ПЕ-ЦС',
                    'contentOptions' => ['style' => ' width: 70px;overflow:hidden ;'],
                    'attribute' => 'wh_dalee_element',
                    //					'attribute'      => 'sprwhelement_dalee_element.name',
                    'content' => function ($model) {
                        //ddd($model);
                        if (!isset($model->wh_dalee_element_name)) {
                            return '';
                        }

                        return $model->wh_dalee_element_name;
                    },
                ],


                [
                    'attribute' => 'user_name',
                    'contentOptions' => ['style' => 'width: 60px;'],
                    'content' => function ($model) {
                        if (!isset($model->user_name)) {
                            return '';
                        }
                        return $model->user_name;
                    },
                ],

//                [
//                    'attribute' => 'dt_create_timestamp',
//                    'contentOptions' => ['style' => 'width: 60px;'],
//                    'format' => [
//                        'datetime',
//                        'php:d.m H:i',
//                    ],
//                ],

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{move_to_delete} ',
                    'contentOptions' => [
                        'class' => 'action-column',
                        'style' => ' width: 1px;',
                    ],
                    'buttons' => [
                        'move_to_delete' => function ($url) {

                            //Для Талгата
                            if (Yii::$app->getUser()->identity->group_id >= 100 ||
                                Yii::$app->getUser()->identity->group_id >=
                                71) {

                                $options = [
                                    'title' => 'delete',
                                    'aria-label' => 'delete',
                                 //   'data-pjax' => 'w0',
                                    'data-confirm' => Yii::t('yii', 'Точно? Удаляем НАКЛАДНУЮ и переносим ее в Архив Удаленных'),
                                    'data-method' => 'POST',
                                ];

                                return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, $options);
                            }

                            return '';
                        },
                    ],
                ],


//                [
//                    'attribute' => 'update_user_name',
//                    'contentOptions' => ['style' => 'width: 60px;'],
//                    'content' => function ($model) {
//                        if (!isset($model->update_user_name)) {
//                            return '';
//                        }
//                        return $model->update_user_name;
//                    },
//                ],

                [
                    'attribute' => 'update_user_name',
                    'value' => 'update_user_name',
                    'contentOptions' => ['style' => 'width: 60px;'],
                    'format' => [
                        'datetime',
                        'php:d.m H:i',
                    ],

                    'content' => function ($model) {

                        if (isset($model->update_points)) {
                            if (is_array($model->update_points)) {
                                $count = count($model->update_points);
                                $model_arr = $model->update_points[--$count];

                                if (isset($model_arr['user_name'])) {
                                    return $model_arr['user_name'];
                                }
                            }
                        }


                        if (!isset($model->dt_update)) {
                            return '';
                        }
                        return $model->dt_update;
                    },
                ],
                [
                    'attribute' => 'dt_update',
                    'value' => 'dt_update',
                    'contentOptions' => ['style' => 'width: 60px;'],
                    'format' => [
                        'datetime',
                        'php:d.m H:i',
                    ],

                    'content' => function ($model) {

                        if (isset($model->update_points)) {
                            if (is_array($model->update_points)) {
                                $count = count($model->update_points);
                                $model_arr = $model->update_points[--$count];

                                if (isset($model_arr['dt_update'])) {
                                    return $model_arr['dt_update'];
                                }
                            }
                        }


                        if (!isset($model->dt_update)) {
                            return '';
                        }
                        return $model->dt_update;
                    },
                ],


            ],
        ]
    );
    ?>
        <?php Pjax::end(); ?>


</div>


