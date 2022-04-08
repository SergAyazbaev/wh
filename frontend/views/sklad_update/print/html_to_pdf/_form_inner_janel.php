
<div class="all_wind">
    <div class="top_center" >
        <H3><b>Накладная на внутреннее перемещение</b>
        </H3>
    </div>


    <div class="print_row">
        <div class="top_left" >
            № <?=$model->id ?>
        </div>
        <div class="top_right" >
            <?=date('Дата: d.m.Y время: H:i:s', strtotime($model->dt_create ));?>
        </div>
    </div>



    <div class="print_row">
        <table cellpadding="0" cellspacing="0" style="width:100%">
            <thead>
            <tr>
                <td colspan="2" style="text-align:center">Отправитель</td>
                <td style="text-align:center">Получатель</td>
                <td colspan="2" style="text-align:center">Автопарк,Борт номер, Гос номер</td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2" style="text-align:center">
                    <?=$wh_debet_name; ?>,<br>
                    <?=$wh_debet_element_name; ?>
                </td>

                <td style="text-align:center">
                    <?=$model6['top']['name']; ?>,<br>
                    <?=$model6['child']['name']; ?>
                </td>
                <td colspan="2" style="text-align:center">
                    <?=$model7['top']['name']; ?>,<br>
                    <?=$model7['child']['name']; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>




    <?php
    $x=0;

    if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort']))
    {
    ?>


    <!--    ////////////-->
    <div class="print_row">

        <table cellpadding="0" cellspacing="0" style="width:100%">
            <thead>
            <tr>
                <td rowspan="2" style="text-align:center">№</td>
                <td rowspan="2" style="text-align:center">Наименование расходных</td>
                <td colspan="2" style="text-align:center">МОНТАЖ</td>
            </tr>
            <tr>
                <td style="text-align:center">Кол-во</td>
                <td style="text-align:center">ID номер</td>
            </tr>
            </thead>


            <tbody>

            <?php
            $x=0;

            if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort']))
            foreach ($model['array_tk_amort'] as $rows){
                $x++;
                echo  '<tr>';

                echo  '<td>';
                echo $x;
                echo  '</td>';
                echo  '<td>';
                if (isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element']))
                    echo $model2[$rows['wh_tk_element']];  /// --- 4  -----
                echo  '</td>';

                echo  '<td>';
                if (isset($rows['ed_izmer_num']))
                    echo $rows['ed_izmer_num'];
                echo  '</td>';
                echo  '<td>';
                if (isset($rows['bar_code']))
                    if (!empty($rows['bar_code']))
                        echo $rows['bar_code'];
                    else
                        echo "Б/Н";
                echo  '</td>';
                echo  '</tr>';
            }
            ?>

            </tbody>
        </table>

    </div>
    <?php } ?>



    <?php
    if (isset($model['array_tk']) && !empty($model['array_tk']))
    {
    ?>


    <div class="print_row">

    <?php
    $x=0;
    //        dd($model['array_tk_amort']);
    //        dd($model5);


    $max_str_num = count($model['array_tk'])/2;
    //$max_str_num = 9/2;
    $max_str_num = round($max_str_num, 0, PHP_ROUND_HALF_UP);   // 10

    //        dd($max_str_num);


    $tttt='<table cellpadding="0" cellspacing="0" style="width:100%">
        <thead>
        <tr>
            <td style="text-align:center">Наименование расходных</td>
            <td style="text-align:center">Ед.изм</td>
            <td style="text-align:center">Кол-во</td>
        </tr>
        </thead>
        <tbody>';


    if (isset($model['array_tk']) && !empty($model['array_tk'])) {
        $array_tk=$model['array_tk'];
        foreach ($model['array_tk'] as $rows) {

            $tttt .= '<tr>';
            $tttt .= '<td>';
            if (isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element']))
                $tttt .= $model4[$rows['wh_tk_element']];  /// --- 4  -----
            $tttt .= '</td>';
            $tttt .= '<td>';
            if (isset($rows['ed_izmer']))
                $tttt .= $model5[$rows['ed_izmer']];
            $tttt .= '</td>';
            $tttt .= '<td>';
            if (isset($rows['ed_izmer_num']))
                $tttt .= $rows['ed_izmer_num'];
            $tttt .= '</td>';
            $tttt .= '</tr>';

            $fruit = array_shift($array_tk);
            unset($fruit);
            $max_str_num--;
            if ($max_str_num <= 0) break;
        }

    $tttt.='<tbody></table>';

    //dd($array_1);

    ?>


    <?
    $tttt2='<table cellpadding="0" cellspacing="0" style="width:100%">
        <thead>
        <tr>
            <td style="text-align:center">Наименование расходных</td>
            <td style="text-align:center">Ед.изм</td>
            <td style="text-align:center">Кол-во</td>
        </tr>
        </thead>
        <tbody>';
    if (isset($array_tk) && !empty($array_tk))
    foreach ( $array_tk as $rows){
        $tttt2.=  '<tr>';
        $tttt2.=  '<td>';
        if (isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element']))
               $tttt2.= $model4[$rows['wh_tk_element']];  /// --- 4  -----
        $tttt2.=  '</td>';
        $tttt2.=  '<td>';
        $tttt2.= $model5[$rows['ed_izmer']];
        $tttt2.=  '</td>';
        $tttt2.=  '<td>';
        $tttt2.= $rows['ed_izmer_num'];
        $tttt2.=  '</td>';
        $tttt2.=  '</tr>';
    }
    $tttt2.='</tbody>
    </table>';

    //echo $tttt;
    ?>

    <table border="0" style="border: none" valign="baseline">
        <tr>
            <td colspan="3">
                <?=$tttt?>
            </td>
            <td colspan="3">
                <?=$tttt2?>
            </td>
        </tr>
    </table>
    <?php } ?>

</div>
    <?php } ?>



    <?php
    if (isset($model['array_casual']) && !empty($model['array_casual']))
    {
        ?>

        <table cellpadding="0" cellspacing="0">

            <thead>
            <tr>
                <td>р/с</td>
                <td style="width:520px"> Наименование </td>
                <td>ед.<br>изм</td>
                <td>Кол-<br>во</td>
                <td style="width:100px">Примечание</td>
            </tr>

            </thead>


            <tbody>

            <?

            foreach ( $model['array_casual'] as $rows){

                //            [wh_tk] => 1
                //            [wh_tk_element] =>
                //            [ed_izmer] => 1
                //            [ed_izmer_num] => 1

                $x++;
                echo  '<tr>';
                echo  '<td>';
                echo $x;
                echo  '</td>';
                echo  '<td>';
                if (isset($rows['wh_tk_element']) && $rows['wh_tk_element']>0)
                    echo $model4[$rows['wh_tk_element']]; /// --- 4  -----
                echo  '</td>';
                echo  '<td>';
                if (isset($rows['ed_izmer']))
                    echo $model5[$rows['ed_izmer']];
                echo  '</td>';
                echo  '<td>';
                if (isset($rows['ed_izmer_num']))
                    echo $rows['ed_izmer_num'];
                echo  '</td>';
                echo  '<td>';
                //echo 'нет';
                echo  '</td>';
                echo  '</tr>';
            }
            ?>

            </tbody>
        </table>
    <?php } ?>


    <br>
<br>

<!---->
<!--<div class="print_row" style="text-align: justify">-->
<!--    <div class="footer_left" >-->
<!--        <div style="text-align:center" >Отпустил</div>-->
<!---->
<!--    </div>-->
<!--    <div class="footer_right" >-->
<!--        <div style="text-align:center" >Получил</div>-->
<!---->
<!--    </div>-->
<!---->
<!---->
<!--</div>-->

</div>