<?php


//use yii\bootstrap\Modal;
//use yii\helpers\ArrayHelper;
    use kartik\select2\Select2;
//    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;


Pjax::begin([    'id' => 'pjax-container', ]);
    echo yii::$app->request->get('page');
Pjax::end();

?>
<style>
    /*.navbar-inverse {*/
    /*    height: 0px;*/
    /*}*/
    .glyphicon-plus{
        color: #fff;
        background-color: #5cb85c;
        border-color: #4cae4c;

        /*padding: 7px;*/
        /*margin: -8px;*/
        font-size: medium;
        font-weight: bold;
        border-radius: 25px;
    }
    .glyphicon-plus:hover{
        color: rgba(255, 255, 255, 0.53);
        background-color: #5cb85c;
        border-color: #4cae4c;
        box-shadow: -1px 3px 7px 0px rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.1);

    }

    /*.help-block {*/

    /*}*/
    .help-block-error{
        display: none;
    }

    thead th{
        height: 22px;
        padding: 10px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }

    table tr,table td,{
        border: 0px;
        margin: 0px;
        width: 100%;
    }
    thead tr,thead td{
        background-color: rgba(11, 147, 213, 0.12);
        padding: 5px 10px;
        margin: 0px;
        padding: 10px;
    }
    tbody tr,tbody td{
        padding: 1px 3px;
        border: 1px solid rgba(89, 237, 51, 0.34);
    }
    td div select{
        /*background-color: rgba(65, 255, 61, 0.24);*/
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        width: 100%;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }
    td div input{
        background-color: rgba(35, 216, 57, 0.21);
        padding: 0px 3px;
        height: 22px;
        border: 0;
        margin: 0px;
        /*box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.11);*/
    }
</style>





<div id="tz1">
    <?php $form = ActiveForm::begin([
        'id'=>'project-form',
        'action' => ['from_cs'],
        'options' => [
            'data-pjax' => 1,
            'autocomplete' => 'off',
            'method' => 'PUT',
        ],
    ]);
    ?>

    <div class="pv_motion_create_right_center">

        <div class="tree_land2">

	        <?= "<br>склад " . $model_sklad[ 'id' ]; ?>

            <?="<br><h2> Целевой склад (ЦС) ".$model->id ."</h2>"; ?>

        </div>

        <div class="tree_land">
            <?php

                echo $form->field($model_sklad, 'id')->hiddenInput()->label(false); // тоже надо!

                echo $form->field($model_top, 'id')->dropDownList( $list_group );

                ///////////////////

            ?>
            <?php
                //echo $form->field($model_element, 'id')->dropDownList( $list_element );

                echo $form->field($model_element, 'id')->widget(Select2::className()
                    , [
                        'name' => 'st',
                        'data' => $list_element,

                        //                        'data' =>   ArrayHelper::map(
                        //                            Sprwhelement::find()
                        //                                ->where(['parent_id'=>(integer)$model_top->wh_destination])
                        //                                ->orderBy('name')
                        //                                ->all()
                        //                            ,'id','name'),
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'size' => Select2::SMALL, //LARGE,
                    ]);

            ?>
        </div>




    </div>







    <div class="pv_motion_create_ok_button">

        <?= Html::Button('&#8593;',
            [
                'class' => 'btn btn-warning',
                'onclick'=>"window.history.back();"
            ]);
        ?>
        <?= Html::submitButton('Запросить ОСТАТКИ',
            [
                'class' => 'btn btn-success',
            ]);
        ?>



        <?php
        echo Html::a('Отменить накладную ',
            ['/sklad/transfer_dont/?id=' . $model->_id],
            [
                'class' => 'btn btn-danger'
            ]);
        ?>





    </div>

</div>



<?php ActiveForm::end(); ?>






