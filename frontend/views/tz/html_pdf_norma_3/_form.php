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
                <td style="text-align:center">Номера ПЕ</td>

            </tr>
            </thead>
            <tbody>

            <tbody>

            <?php

            if (isset($model['array_bus']) && !empty($model['array_bus'])) {
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
                <td style="text-align:center">№1</td>
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
                        echo $rows['ed_izmer_num'] * $model['multi_tz'];
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
                <td style="text-align:center">№2</td>
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

            if (isset($model['array_tk']) && !empty($model['array_tk']))
                foreach ($model['array_tk'] as $rows) {

                    $x++;
                    echo '<tr>';
                    echo '<td>';
                    echo $x;
                    echo '</td>';
                    echo '<td>';
                    if (isset($model4[$rows['wh_tk_element']]) && !empty($model4[$rows['wh_tk_element']]))
                        echo $model4[$rows['wh_tk_element']];  /// --- 4  -----
                    echo '</td>';
                    echo '<td>';
                    echo 'шт.';

                    echo '</td>';
                    echo '<td style="text-align: center">';
                    if (isset($rows['ed_izmer_num']) && !empty($rows['ed_izmer_num']))
                        echo $rows['ed_izmer_num'];
                    echo '</td>';
                    echo '<td style="text-align: center">';
                    if (isset($rows['ed_izmer_num']) && !empty($rows['ed_izmer_num']))
                        echo $rows['ed_izmer_num'] * $model['multi_tz'];
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
                <td style="text-align:center">№3</td>
                <td style="text-align:center">Наименование</td>
                <td style="text-align:center">Ед.Изм</td>

                <td style="text-align:center">Общее к-во</td>

            </tr>
            </thead>

            <tbody>

            <?php

            $x = 0;

            if (isset($model['array_casual']) && !empty($model['array_casual']))
                foreach ($model['array_casual'] as $rows) {

                    $x++;
                    echo '<tr>';
                    echo '<td>';
                    echo $x;
                    echo '</td>';
                    echo '<td>';
                    if (isset($model4[$rows['wh_tk_element']]) && !empty($model4[$rows['wh_tk_element']]))
                        echo $model4[$rows['wh_tk_element']];  /// --- 4  -----
                    echo '</td>';
                    echo '<td>';
                    echo 'шт.';

                    echo '</td>';
                    echo '<td style="text-align: center">';
                    if (isset($rows['ed_izmer_num']) && !empty($rows['ed_izmer_num']))
                        echo $rows['ed_izmer_num'];
                    echo '</td>';
                    echo '</tr>';
                }
            ?>

            </tbody>
        </table>


    </div>

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

