<?
//dd($model);

?>

<div class="all_wind">


    <!--    ////////////-->
    <div class="name_tech">
        Техзадание № <?= $model->id ?>
    </div>

    <!--    ////////////-->
    <div class="print_row">
        <div class="print_block">
            <div class="print_head">
                Контрагент: <?= $model7['name'];   /// Наименование получателя  ?>
                <br>
                Вид работы: <?= $model6[$model['street_map']]; ?>

            </div>
        </div>
        <div class="print_block">
            <div class="print_head">
                <small>
                    Дата начала: <?= date('d.m.Y время: H:i:s', strtotime($model->dt_create)); ?>
                    <br>
                    Дата окончания: <?= date('d.m.Y время: H:i:s', strtotime($model->dt_create)); ?>
                </small>
                <br>
                Комплектов: <?= $model['multi_tz'];  ///  ?>

            </div>
        </div>
    </div>


    <!--    ////////////-->
    <div class="print_row">

        <table cellpadding="0" cellspacing="0" style="width:100%">
            <thead>
            <tr>
                  <td style="text-align:center">Номера ПЕ </td>

            </tr>
            </thead>
            <tbody>

            <tbody>

            <?php

            if ( isset($model['array_bus']) && !empty($model['array_bus'])) {
                $arr_xx = [];

                foreach ($model['array_bus'] as $item) {
                    $arr_xx[] = $model8[$item];
                }

                sort($arr_xx, 1);

                echo '<tr>';
                    echo '<td>';
                        foreach ($arr_xx as $item) {
                            echo $item . "; ";
                        }
                    echo '</td>';
                echo '</tr>';
            }
            ?>

            </tbody>
        </table>

    </div>


    <!--    ////////////-->
    <div class="print_row">

        <table cellpadding="0" cellspacing="0" style="width:100%">
            <thead>
            <tr>
                <td style="text-align:center">№</td>
                <td style="text-align:center">Наименование</td>
                <td style="text-align:center">Ед.Изм</td>
                <td style="text-align:center">Норма расхода</td>
                <td style="text-align:center">Общее к-во</td>

            </tr>
            </thead>
            <tbody>

            <tbody>

            <?php

            $x = 0;

            if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort']))
                foreach ($model['array_tk_amort'] as $rows) {

                $x++;
                echo '<tr>';
                echo '<td>';
                echo $x;
                echo '</td>';
                echo '<td>';
                if (isset($rows['wh_tk_element']))
                    echo $model2[$rows['wh_tk_element']];  /// --- 4  -----
                echo '</td>';
                echo '<td>';
                echo 'шт.';

                echo '</td>';
                echo '<td style="text-align: center">';
                if (isset($rows['ed_izmer_num']))
                    echo $rows['ed_izmer_num'];
                echo '</td>';
                echo '<td style="text-align: center">';
                if (isset($rows['ed_izmer_num']))
                    echo $rows['ed_izmer_num']* $model['multi_tz'];
                echo '</td>';
                echo '</tr>';
            }
            ?>

            </tbody>
        </table>

    </div>

</div>




<div class="print_row">

    <?php
    $x = 0;
    //        dd($model['array_tk_amort']);
    //        dd($model5);


    $max_str_num = count($model['array_tk']) / 2;
    //$max_str_num = 9/2;
    $max_str_num = round($max_str_num, 0, PHP_ROUND_HALF_UP);   // 10

    //        dd($max_str_num);


    $tttt = '<table cellpadding="0" cellspacing="0" style="width:100%">
        <thead>
        <tr>
            <td style="text-align:center">Наименование ТМЦ</td>
            <td style="text-align:center">Ед.изм</td>
                <td style="text-align:center">Норма расхода</td>
                <td style="text-align:center">Общее к-во</td>
        </tr>
        </thead>
        <tbody>';


    $array_tk = yii\helpers\ArrayHelper::toArray($model['array_tk']);

    foreach ($array_tk as $rows) {

        $tttt .= '<tr>';
        $tttt .= '<td>';
        if (isset($rows['wh_tk_element']))
            $tttt .= $model4[$rows['wh_tk_element']];  /// --- 4  -----
        $tttt .= '</td>';
        $tttt .= '<td style="text-align: center">';
        if (isset($rows['ed_izmer_num']))
            $tttt .= $rows['ed_izmer_num'];
        $tttt .= '</td>';
        $tttt .= '<td style="text-align: center">';
        $tttt .= 'шт.';
        $tttt .= '</td>';
        $tttt .= '<td style="text-align: center">';
        $tttt .= $rows['ed_izmer_num']* $model['multi_tz'];
        $tttt .= '</td>';

        $tttt .= '</tr>';

        $fruit = array_shift($array_tk);
        $max_str_num--;
        if ($max_str_num <= 0) break;
    }
    $tttt .= '<tbody></table>';

    //dd($tttt);

    ?>


    <?
    $tttt2 = '<table cellpadding="0" cellspacing="0" style="width:100%">
        <thead>
        <tr>
            <td style="text-align:center">Наименование ТМЦ</td>
            <td style="text-align:center">Ед.изм</td>
                <td style="text-align:center">Норма расхода</td>
                <td style="text-align:center">Общее к-во</td>
        </tr>
        </thead>
        <tbody>';
    foreach ($array_tk as $rows) {

        $tttt2 .= '<tr>';
        $tttt2 .= '<td>';
        if (isset($rows['wh_tk_element']) && $rows['wh_tk_element'] > 0)
            $tttt2 .= $model4[$rows['wh_tk_element']];  /// --- 4  -----
        $tttt2 .= '</td>';
        $tttt2 .= '<td style="text-align: center">';
        $tttt2 .= $rows['ed_izmer_num'];
        $tttt2 .= '</td>';
        $tttt2 .= '<td style="text-align: center">';
        $tttt2 .= "шт.";
        $tttt2 .= '</td>';
        $tttt2 .= '<td style="text-align: center">';
        $tttt2 .= $rows['ed_izmer_num']* $model['multi_tz'];
        $tttt2 .= '</td>';

        $tttt2 .= '</tr>';
    }
    $tttt2 .= '</tbody>
    </table>';

    //echo $tttt;
    ?>

    <table border="0" style="border: none" valign="baseline">
        <tr>
            <td colspan="3">
                <?= $tttt ?>
            </td>
            <td colspan="3">
                <?= $tttt2 ?>
            </td>
        </tr>
    </table>
</div>


<br>

<div class="print_row" style="text-align: justify">
    <div class="footer_left">
        <div style="text-align:center">Тех.Задание выдал</div>
    </div>
    <div class="footer_right">
        <div style="text-align:center">Тех.Задание получил</div>
    </div>
</div>

