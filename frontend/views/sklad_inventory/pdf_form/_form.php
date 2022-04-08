<div class="all_wind">


    <div style="text-align:center">
                <H2>Инвентаризационная опись оборудования и ТМЦ</H2>
    </div>
<pre>
 Инвентаризация начата    "____"______201__ года.         Время _______
 Инвентаризация окончена  "____"______201__ года.         Время _______
</pre>

    <!--    ////////////-->
    <div class="print_row">

    <?php
    $x=0;

    if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort']))
    {
    ?>

        <table cellpadding="0" cellspacing="0">

            <thead>

            <tr>
                <td>р/с</td>
                <td style="width:520px"> Наименование </td>
                <td>ед.<br>изм</td>
                <td>Кол-<br>во</td>
                <td style="width:100px">Штр.Код</td>
            </tr>

            </thead>


            <tbody>



        <?php

        if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort']))
            foreach ($model['array_tk_amort'] as $rows){

            $x++;
            echo  '<tr>';
                echo  '<td>';
                    echo $x;
                echo  '</td>';
                echo  '<td>';
                if(isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element']))
                    echo $model2[$rows['wh_tk_element']] ; /// --- 4  -----
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
<br>
    <?php } ?>



        <?php
        if (isset($model['array_tk']) && !empty($model['array_tk'])) {
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
            foreach ($model['array_tk'] as $rows) {

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
<br>
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


    </div>




    <div class="print_row">
        <div class="butt">
<pre>
Председатель комиссии:
Зам.директора по финансам        ____________ Жанарбаев Р.Б.
</pre>
        </div >

        <div class="butt">
<pre>
Главный инженер по эксплуатации    ____________ Карбаев Н.К.
</pre>
        </div >
        <div class="butt">
<pre>
Руководитель Сервисно-технического
    отдела                        _____________ Нуспаев Д.Б.
</pre>
</div >

<div class="butt">
<pre>
_____________________________    _____________ (__________)

_____________________________    _____________ (__________)

_____________________________    _____________ (__________)

</pre>
</div >

<div class="butt">
<pre>
Материально-ответственное лицо:
должность, подпись, расшифровка подписи
</pre>
</div >

    </div>



</div>





