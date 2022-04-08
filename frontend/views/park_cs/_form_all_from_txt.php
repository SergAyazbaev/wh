<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
//use yii\jui\AutoComplete;
use yii\widgets\ActiveForm;
use frontend\models\postsprwhtop;


if (empty($model->parent_id)) $model->parent_id=0;
?>
<style>
    .sprtype-form{
        width: 80%;
        max-width: 400px;
    }
    div.horisont{
        background-color: #ffe4c438;
        width: 100%;
        display: block;
        float: left;
        margin-bottom: 5px;
        padding: 11px;
    }
</style>


<div class="sprtype-form">

    <div class="horisont">

        <?php         $form = ActiveForm::begin([

        //'action' => ['index'],
        //'action' => ['/sprwhelement/excel?sort=-parent_id'],

        'action' => ['excel?sort='.$para['sort']],

//        'method' => 'get',
        'method' => 'post',
        ]);
        ?>




        <?= $form->field($model, 'id')
            ->textInput(['placeholder' => $model->getAttributeLabel('id'),
                'style' => 'width: 77px; margin-right: 5px;',
                'readonly' => 'readonly'])
            ->label(false);
        ?>

        <?= $form->field($model, 'parent_id')
            ->dropdownList(
                ArrayHelper::map( postsprwhtop::find()->all(), 'id','name'),
            [
                'prompt' => 'Выбор группы... '
            ]
        )
            ->label(false);
        ?>
        <div class="horisont">
<pre>
Пример:
BATU HOLDING ТОО	063CH02
BATU HOLDING ТОО	079CH02
BATU HOLDING ТОО	091CH02
</pre>
        </div>
    </div>


    <div class="horisont">

        <?= $form->field($model, 'all_from_txt')
            ->textarea(['placeholder' => $model->getAttributeLabel('name'),
                        'style' => 'width: 370px; margin-right: 5px;'])
            ->label(false);
        ?>


        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
