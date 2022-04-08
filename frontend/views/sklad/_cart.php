<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


$this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Учетная единица', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pv-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?//= Html::a('Сохранить', ['update', 'id' => (string)$model->_id],      ['class' => 'btn btn-primary']) ?>

        <?//= Html::a('Удалить', ['delete', 'id' => (string)$model->_id], [
//            'class' => 'btn btn-danger',
//            'data' => [
//                'confirm' => 'Вы хотите УДАЛИТЬ запись?',
//                'method' => 'post',
//            ],
//        ]) ?>


        <?= Html::button('Описание / Характеристики',
            ['class' => 'btn btn-success ','id' => 'body' ])
        ?>



    </p>

<!--<div class="cart_top_base" >-->
    <div class="cart_top_box" >

        <div class="cart_box_small">


                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'type_pv_name',
                        'group_pv_name',
                        'bar_code_pv',
                        '_id',
                        'dt_create_pv',
                    ],
                    ]) ?>

        </div>


    </div>





    <div class="cart_top_history" >

        <?php   echo $this->render('_cart_body',
            [
                'model_action' => $model_action,

                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]); ?>

    </div>

</div>


    <?php /*  echo $this->render('_cart_body', ['model' => $model]); */?>


<?

$YourID ='123wqerq';
$imgID ='34513254wqer'
?>
<?//= \Johnson\JayWebcam::widget(['id' => $YourID,'imgID' => $imgID]); ?>

<!--<input  type="text" id="<?/*= $YourID */?>">
<img src="data:image/png;base64,ASF9D8ASDF89ASDFHA8S9DFH98ADS..." id="<?/* $imgID */?>" class="thumbnail">-->


<style>
    .heigh_screen{
        height: 580px;
    }
</style>

<?php
$script = <<<JS
    
           
    $('#body').click(function () {
        $('.cart_top_box').slideToggle( "slow",  function() {
            
             $('.cart_top_history').toggleClass('heigh_screen',1000);
                
     
     
         });
        
            // height: 580px;
            //.cart_top_history
    });


JS;
$this->registerJs($script);
?>


