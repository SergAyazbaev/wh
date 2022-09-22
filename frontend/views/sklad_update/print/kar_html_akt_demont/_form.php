<div class="all_wind">
    <div class="print_row">
        <small><b>
                АКТ МОНТАЖА/ДЕМОНТАЖА ОБОРУДОВАНИЯ и СЕРВИСНО-ТЕХНИЧЕСКОГО ОБСЛУЖИВАНИЯ № <?= $model->id ?>
            </b>
        </small>
    </div>

    <div class="print_row">
        <div class="print_block_date">
            г.Караганда, <?= date('Дата: d.m.Y время: H:i:s', strtotime($model->dt_create)); ?>
        </div>
    </div>

    <div class="print_row">
        <div class="print_block">
            <div class="print_head">
                Гос.№ сервисной машины
            </div>
            <div class="print_head">
                Наименование превозчика
            </div>
            <div class="print_head">
                Борт.№ ТС Перевозчика
            </div>
            <div class="print_head">
                Основание (№ Заявки)
            </div>
        </div>
        <div class="print_block">
            <div class="print_head">
                ________________________________________________
            </div>
            <div class="print_head">
                <!--                --><?php //=$model['wh_destination_name']; ?>
                <?= $model['wh_debet_name']; ?>
            </div>
            <div class="print_head">
                <!--                --><?php //=$model['wh_destination_element_name'];  /// Автобус превозчика?>
                <?= $model['wh_debet_element_name']; ?>
            </div>
            <div class="print_head">
                ________________________________________________
            </div>
        </div>
    </div>


    <!--    ////////////-->
    <div class="print_row">

        <table cellpadding="0" cellspacing="0" style="width:100%">
            <thead>
            <tr>
                <td rowspan="2" style="text-align:center">№</td>
                <td rowspan="2" style="text-align:center">Наименование расходных</td>
                <td colspan="2" style="text-align:center">ДЕМОНТАЖ</td>
                <td colspan="2" style="text-align:center">МОНТАЖ</td>
            </tr>
            <tr>
                <td style="text-align:center">ID номер</td>
                <td style="text-align:center">Кол-во</td>
                <td style="text-align:center">ID номер</td>
                <td style="text-align:center">Кол-во</td>
            </tr>
            </thead>
            <tbody>

            <tbody>

            <?php
            $x = 0;
            //        dd($model['array_tk_amort']);
            //        dd($model5);


            $array_tk_amort = yii\helpers\ArrayHelper::toArray($model['array_tk_amort']);

            foreach ($array_tk_amort as $rows) {
                //            [wh_tk_amort] => 1
                //            [wh_tk_element] => 14
                //            [intelligent] => 1
                //            [ed_izmer] => 1
                //            [ed_izmer_num] => 1
                //            [bar_code] =>

                //            dd($rows['ed_izmer']);

                $x++;
                echo '<tr>';
                echo '<td>';
                echo $x;
                echo '</td>';
                echo '<td>';
                if (isset($rows['wh_tk_element']) && $rows['wh_tk_element'] > 0) {
                    echo $model2[$rows['wh_tk_element']];
                }  /// --- 4  -----
                echo '</td>';
                echo '<td>';
                if (isset($rows['bar_code']) && !empty($rows['bar_code'])) {
                    echo $rows['bar_code'];
                }
                echo '</td>';
                echo '<td>';
                if (isset($rows['ed_izmer_num'])) {
                    echo $rows['ed_izmer_num'];
                }
                echo '</td>';
                echo '<td>';
                //echo 'нет';
                echo '</td>';
                echo '<td>';
                //echo 'нет';
                echo '</td>';
                echo '</tr>';
            }
            ?>

            </tbody>
        </table>

    </div>

</div>


<div class="print_row">
    Виды работ, проведенные при сервисном обслуживании ______________________________________________
    Расходные материалы, использованные при сервисном обслуживании:
</div>



<div class="print_row">

    <?php
    $x = 0;
    //        dd($model['array_tk_amort']);
    //        dd($model5);


    if (isset($model['array_tk']) && !empty($model['array_tk'])) {
        $max_str_num = count($model['array_tk']) / 2;
    } else {
        $max_str_num = 0;
    }

    //$max_str_num = 9/2;
    $max_str_num = round($max_str_num, 0, PHP_ROUND_HALF_UP);   // 10

    //        dd($max_str_num);


    $tttt = '<table cellpadding="0" cellspacing="0" style="width:100%">
        <thead>
        <tr>
            <td style="text-align:center">Наименование расходных</td>
            <td style="text-align:center">Ед.изм</td>
            <td style="text-align:center">Кол-во</td>
        </tr>
        </thead>
        <tbody>';


    $array_tk = yii\helpers\ArrayHelper::toArray($model['array_tk']);

    if (isset($model['array_tk']) && !empty($model['array_tk'])) {

        foreach ($array_tk as $rows) {

            $tttt .= '<tr>';
            $tttt .= '<td>';
            if (isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element'])) {
                $tttt .= $model4[$rows['wh_tk_element']];
            }  /// --- 4  -----
            ///
            $tttt .= '</td>';
            $tttt .= '<td>';
            if (isset($rows['ed_izmer'])) {
                $tttt .= $model5[$rows['ed_izmer']];
            }
            $tttt .= '</td>';
            $tttt .= '<td>';
            if (isset($rows['ed_izmer_num'])) {
                $tttt .= $rows['ed_izmer_num'];
            }
            $tttt .= '</td>';
            $tttt .= '</tr>';

            $fruit = array_shift($array_tk);
            $max_str_num--;
            if ($max_str_num <= 0) {
                break;
            }
        }
        $tttt .= '<tbody></table>';

        //dd($array_1);

        ?>


        <?
        $tttt2 = '<table cellpadding="0" cellspacing="0" style="width:100%">
        <thead>
        <tr>
            <td style="text-align:center">Наименование расходных</td>
            <td style="text-align:center">Ед.изм</td>
            <td style="text-align:center">Кол-во</td>
        </tr>
        </thead>
        <tbody>';
        foreach ($array_tk as $rows) {
            $tttt2 .= '<tr>';
            $tttt2 .= '<td>';

            if (isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element'])) {
                $tttt2 .= $model4[$rows['wh_tk_element']];
            }  /// --- 4  -----
            ///
            $tttt2 .= '</td>';
            $tttt2 .= '<td>';
            $tttt2 .= $model5[$rows['ed_izmer']];
            $tttt2 .= '</td>';
            $tttt2 .= '<td>';
            $tttt2 .= $rows['ed_izmer_num'];
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
    <?php } ?>
</div>



<div class="print_row">
Детали, оборудование транспортного средства, которые были повреждены или деформированы до осуществления Оператором монтажа/демонтажа оборудования:
_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _

С местом установки и схемой подключения проводки и оборудования согласен и претензий не имею.
</div>
<div class="print_row" style="text-align: justify">
    <div class="footer_left">
        <div style="text-align:center">Представитель/работник Перевозчика: </div>
        <div style="text-align:center">Ф.И.О., должность, подпись</div>

    </div>

    <div class="footer_right">
        <div style="text-align:center">Представитель/работник филиала ТОО Транспортный холдинг города Алматы по Карагандинской области:</div>
        <div style="text-align:center">Ф.И.О., должность, подпись</div>

    </div>

    <br>Настоящий  акт монтажа/демонтажа оборудования и сервисно технического обслуживания в случае монтажа является документом, подтверждающим фактическую установку проводки и/или оборудования и правовым основанием для возникновения материальной ответственности Перевозчика перед Оператором в рамках соответствующего договора о полной материальной ответственности.</b>

</div>
