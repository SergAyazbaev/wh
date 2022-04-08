<?php

//use \yii\grid\GridView;
use \yii\widgets\ListView;

$this->title = 'GuideJet';
?>


<div class="site-index">

    <div class="mobile-body-content">
        <div class="jumbotron">
            <br>
            <h1>ОСТАТКИ</h1>
        </div>
    </div>

    <?
    echo ListView::widget([
        'dataProvider' => $dataProvider,
//        'itemView' => '_post',
    ]);

    //    echo GridView::widget([
    //        'dataProvider' => $dataProvider,
    //        'columns' => [
    //            [
    //                'attribute' => 'id',
    //                'contentOptions' => ['style' => 'max-width: 90px;'],
    //            ],
    //        ]
    //    ]);

    ?>

</div>



