<?php


use \yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Журнал ремонтов ';
?>
<style>
    .wrap {
        width: 90%;
        margin-left: 5%;
        margin-top: 5%;
    }

    .table_all {
        background-color: #032B3F22;
        display: grid;
        position: relative;
        margin-top: 10px;
        padding: 15px 45px;
    }

    .table_decision {
        background-color: #a8151b22;
        position: inherit;
        display: table-caption;
        float: right;
        margin: 10px;
        padding: 10px;
        float: left;
    }

    .table_1 {
        background-color: #012B3F22;
        position: inherit;
        display: table-caption;
        float: right;
        margin: 10px;
        padding: 10px;
    }

    .wrap > .container22 {
        margin-top: 0px;
    }


    @media (max-width: 500px) {
        .wrap {
            width: auto;
            margin-left: 0;
            margin-top: 5%;
        }

    }


    @media (max-width: 700px) {
        .ap_pe {
            top: 110px;
            left: 4px;
        }


        p > a {
            height: 50px;
        }

        .btn {
            margin: 3px 10px;
            font-size: 22px;
        }
    }

    .alert_str {

    }

    .btn-xs, .btn-group-xs > .btn {
        padding: 1px 5px;
        font-size: 16px;
        line-height: 1.5;
        border-radius: 3px;
    }

    .grid-view .filters input, .grid-view .filters select {
        min-width: 50px;
        font-size: 24px;
        padding: 3px;
    }

</style>


<div class="sprtype-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        $url = Url::to(['/rem_sklad/create?sklad_id=' . $sklad_id]);

        echo ' ' . Html::a(
                'Новая запись в журнал из накладной ' . $sklad_id, $url, [
                    'class' => 'btn btn-success btn-xs',
                    'data-pjax' => 0,
                ]
            );

        ?>

        <!--        --><? //= Html::a('Новая запись в журнал', ['rem_sklad/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    //    'array_tk_amort' => $array_tk_amort,
    //            'comment' => $comment,

    //    ddd($array_tk_amort);
    //    'wh_tk_amort' => '7'
    //        'wh_tk_element' => '1'
    //        'ed_izmer' => '1'
    //        'ed_izmer_num' => '1'
    //        'take_it' => '0'
    //        'bar_code' => '040238'
    //        'name' => 'Помощник водителя GJ-DA04'

    echo '<div class="table_all">';

    foreach ($array_tk_amort as $item) {
        echo '<div class="table_1">';

        echo ' ' . $item['name'];
        echo ' ' . $item['bar_code'];
        if (isset($array_by_array[(string)$item['bar_code']]) && !empty($array_by_array[(string)$item['bar_code']])) {
            echo ' в Истории ремонтов => ';

            $url = Url::to(['/rem_sklad/index_tabl?id=' . $item['bar_code']]);

            echo ' ' . Html::a(
                    $item['bar_code'], $url, [
                        'class' => 'btn btn-success btn-xs',
                        'data-pjax' => 0,
                    ]
                );
        }


        echo '</div>';
    }

    echo '</div>';

    //    ddd($comment);
    //    ddd($array_tk_amort);

    ?>
</div>


<div class="_futer">
    <div class="past_futer">

        <?php
        echo Html::a(
            '<< Выход',
            ['/mobile_inventory/init_table'],
            [
                'onclick' => 'window.opener.location.reload();window.close();',
                'class' => ' back_step btn btn-warning',
            ]);
        ?>
    </div>
    <!--            <div class="next_futer">-->
    <!---->
    <!--                --><? //= Html::a('Далее >>', ['mobile_inventory/init_table'], ['class' => ' next_step btn btn-success']) ?>
    <!---->
    <!--            </div>-->
</div>




