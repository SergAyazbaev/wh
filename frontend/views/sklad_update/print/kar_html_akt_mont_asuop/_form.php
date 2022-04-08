<div class="all_wind">
    <div class="print_row" style="margin-bottom: 10px">
        <small><b>
                АКТ МОНТАЖА/ДЕМОНТАЖА ОБОРУДОВАНИЯ и СЕРВИСНО-ТЕХНИЧЕСКОГО ОБСЛУЖИВАНИЯ № <?= $model->id ?>
            </b>

        </small>
    </div>


    <div class="print_row">
        <div class="top_left">
            г.Караганда
        </div>
        <div class="top_right">
            <?= date('Дата: d.m.Y время: H:i:s', strtotime($model->dt_create)); ?>
        </div>
    </div>

    <div class="print_row">
        <div class="top_left_center">
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
        <div class="top_right_center">
            <div class="print_head">
                Инженер
            </div>
            <div class="print_head">
                <?= $model['wh_destination_name'];  /// Наименование превозчика
                ?>
            </div>
            <div class="print_head">
                <?= $model['wh_destination_element_name'];  /// Автобус превозчика
                ?>
            </div>
            <div class="print_head">
                Монтаж АСУОП
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

            $array_tk_amort = yii\helpers\ArrayHelper::toArray($model['array_tk_amort']);

            foreach ($array_tk_amort as $rows) {

                $x++;
                echo '<tr>';

                echo '<td>';
                echo $x;
                echo '</td>';
                echo '<td>';
                if (isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element'])) {
                    echo $model2[$rows['wh_tk_element']];
                }  /// --- 4  -----
                echo '</td>';
                echo '<td>';
                //echo 'нет';
                echo '</td>';
                echo '<td>';
                //echo 'нет';
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
                echo '</tr>';
            }
            ?>

            </tbody>
        </table>

    </div>

</div>


<div class="print_row">
    Виды работ, проведенные при сервисном обслуживании ______________________________________________

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


    <?php
    $tttt2 =


        '<table cellpadding="0" cellspacing="0" style="width:100%">
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

    <?php
    if (isset($model['array_tk']) && !empty($model['array_tk'])) {
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
    С местом установки и схемой подключения проводки и оборудования согласен и претензий не имею
</div>
<div class="print_row" style="text-align: justify">
    <div class="footer_left">
        <div style="text-align:center">Представитель Превозчика</div>
        <div style="text-align:center">Ф.И.О., должность, подпись</div>

    </div>

    <div class="footer_right">
        <div style="text-align:center">Представитель ТОО "Guidjet TI" <br>(Гайджет ТиАй)</div>
        <div style="text-align:center">Ф.И.О., должность, подпись</div>

        <?php //echo "". Yii::$app->getUser()->identity->username?><!--</div>-->

        <div style="text-align:left"><?php echo "" . Yii::$app->getUser()->identity->username_for_signature ?></div>


    </div>

    <br><u> Данный Акт монтажа/демонтажа оборудования и сервисно-технического обслуживания является документом,
        подтверждающим фактическую установку проводки и оборудования и правовым основанием для возникновения
        материальной ответственности Перевозчика перед ТОО "Транспортный холдинг города Алматы" по Карагандинской области
        согласно Договору о полной материальной  ответственности.</u></b>

</div>
