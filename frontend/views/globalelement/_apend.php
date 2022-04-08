<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = ' 1С. Копипаст новых номеров';

?>




<?php $form = ActiveForm::begin(
    [
        'id' => 'project-form',
        'action' => ['/globalelement/add_pull'],
        'method' => 'post',

        'options' => [
            //'data-pjax' => 1,
            'autocomplete' => 'off',

        ],

        'enableAjaxValidation' => false,

        'enableClientValidation' => true,
        'validateOnChange' => true,
        'validateOnSubmit' => true,
        'validateOnBlur' => true,


    ]);
?>

<div class="pv_motion_create_right_center" style="overflow: auto;">


    <?php
    echo Html::a(
        'Выход',
        ['/sklad/in?otbor=' . $sklad],
        [
            'onclick' => 'self.close();document.location.reload(true);',
            'class' => 'btn btn-warning',
        ]);
    ?>


    <?php
    echo Html::submitButton(
        'Сохранить изменения ',
        [
            'name' => 'contact-button-save',
            'value' => 'save_button',

            'class' => 'btn btn-success',
            'data-confirm' => Yii::t(
                'yii',
                'СОХРАНЯЕМ НАКЛАДНУЮ ?'),
        ]
    );
    ?>






    <?php
    $user = Yii::$app->user->getIdentity();

    //ddd($user);

    if (isset($user['group_id'])) {
        if (
            (int)$user['group_id'] == 100 ||
            (int)$user['group_id'] == 70 ||
            (int)$user['group_id'] == 71
        ) {

            //////////////////////////////////////
            ///
            /// МОДАЛЬНОЕ ОКНО. Добавляем в ПУЛ ШТРИХКОДОВ для АСУОП
            ///

            Modal::begin(
                [
                    'header' => '<h2>Добавляем в ПУЛ ШТРИХКОДОВ для АСУОП</h2>',
                    'toggleButton' => [
                        'label' => 'Копипаст ПУЛ Штрихкодов',
                        'tag' => 'button',
                        'class' => 'btn btn-primary',
                    ],
                    //'footer' => 'Низ окна',
                ]);

            ?>

            <div class="name_ss">

                <?= $form->field($model, 'new_pull')
                    ->textarea(
                        [
                            'placeholder' => '  Два столбца 
                                00000002014    Бокорезы TU-7В
                                00000002014    Бокорезы TU-7В
                                00000002014    Бокорезы TU-7В
                                ',
                            'autofocus' => true,
                            'style' => 'width:100%;width:616px;height:384px;margin:0px;font-size:11px;',
                        ])->label(false); ?>
            </div>


            <div class="form-group">
                <?= Html::submitButton(
                    'Залить копипаст НОВЫЕ ПОЗИЦИИ ',
                    [
                        'class' => 'btn btn-primary',
                        'name' => 'contact-button',
                        'value' => 'add_new_pool',
                    ]) ?>

            </div>


            <?php Modal::end();
        }
    }
    ///////////////////////////////////?>


</div>

<?php ActiveForm::end(); ?>


