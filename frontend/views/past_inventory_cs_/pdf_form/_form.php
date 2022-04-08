<div class="all_wind">


    <div style="text-align:center">
        <H2>Ежедневный расчет остатков (автомат) </H2>
    </div>
    <pre>

</pre>

    <!--    ////////////-->
    <div class="print_row">

        <?php
        $x = 0;

        //    ddd($model);
        //    'prihod_num' => 108
        //    'rashod_num' => 34
        //    'itog' => 764

        if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort'])) {
            ?>

            <table cellpadding="0" cellspacing="0">

                <thead>

                <tr>
                    <td>р/с</td>
                    <td style="width:520px"> Наименование</td>
                    <td>ед.<br>изм</td>
                    <td>Кол-<br>во</td>

                    <td>Приход</td>
                    <td>Расход</td>
                    <td>Итог</td>

                </tr>

                </thead>


                <tbody>


                <?php

                if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort']))
                    foreach ($model['array_tk_amort'] as $rows) {

                        $x++;
                        echo '<tr>';
                        echo '<td>';
                        echo $x;
                        echo '</td>';
                        echo '<td>';
                        if (isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element']))
                            echo $model2[$rows['wh_tk_element']]; /// --- 4  -----
                        echo '</td>';
                        echo '<td>';
                        if (isset($rows['ed_izmer']))
                            echo $model5[$rows['ed_izmer']];
                        echo '</td>';
                        echo '<td>';
                        if (isset($rows['ed_izmer_num']))
                            echo $rows['ed_izmer_num'];
                        echo '</td>';

                        echo '<td>';
                        if (isset($rows['prihod_num']))
                            echo $rows['prihod_num'];
                        echo '</td>';
                        echo '<td>';
                        if (isset($rows['rashod_num']))
                            echo $rows['rashod_num'];
                        echo '</td>';
                        echo '<td>';
                        if (isset($rows['itog']))
                            echo $rows['itog'];
                        echo '</td>';
                        echo '</tr>';
                    }

                ?>

                </tbody>
            </table>
            <br>
        <?php } ?>





        <?php
        if (isset($model['array_casual']) && !empty($model['array_casual'])) {
            ?>

            <table cellpadding="0" cellspacing="0">

                <thead>
                <tr>
                    <td>р/с</td>
                    <td style="width:520px"> Наименование</td>
                    <td>ед.<br>изм</td>
                    <td>Кол-<br>во</td>
                    <td style="width:100px">Примечание</td>
                </tr>

                </thead>


                <tbody>

                <?

                foreach ($model['array_casual'] as $rows) {

                    //            [wh_tk] => 1
                    //            [wh_tk_element] =>
                    //            [ed_izmer] => 1
                    //            [ed_izmer_num] => 1

                    $x++;
                    echo '<tr>';
                    echo '<td>';
                    echo $x;
                    echo '</td>';
                    echo '<td>';
                    if (isset($rows['wh_tk_element']) && $rows['wh_tk_element'] > 0)
                        echo $model4[$rows['wh_tk_element']]; /// --- 4  -----
                    echo '</td>';
                    echo '<td>';
                    if (isset($rows['ed_izmer']))
                        echo $model5[$rows['ed_izmer']];
                    echo '</td>';
                    echo '<td>';
                    if (isset($rows['ed_izmer_num']))
                        echo $rows['ed_izmer_num'];
                    echo '</td>';
                    echo '<td>';
                    //echo 'нет';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>

                </tbody>
            </table>
        <?php } ?>


    </div>


    <div class="print_row">
        <div class="butt">
            Расчет остатков произведен ежедневной процедурой (автомат) по крайним данным инвентаризации для данного
            склада

        </div>
    </div>


</div>





