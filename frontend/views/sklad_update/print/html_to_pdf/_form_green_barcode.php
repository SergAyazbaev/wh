<div class="all_wind">

    <div class="print_row">

        <div class="print_block" >
            <div class="print_head">
                Отправитель:
            </div>
            <div class="print_block_label " >
                <?=$model->wh_debet_name ?>
                <?=' - ' ?>
                <?=$model->wh_debet_element_name ?>
            </div>
        </div>

        <div class="print_block" >
            <div class="print_head">
                Получатель:
            </div>

            <div class="print_block_label" >
                <?=$model->wh_destination_name ?>
                <?=' - ' ?>
                <?=$model->wh_destination_element_name ?>
            </div>
        </div>


    </div>



    <div class="print_row">


        <div class="print_block_nakl" >
            НАКЛАДНАЯ № <?=$model->id ?>
        </div>

        <div class="print_block_date_right" >
            <div class="date_long " >

                <div class="date_kun2" >
                    <?=date('d.m.Y ', strtotime($model->dt_create ));?>
                </div>

            </div>
        </div>


    </div>


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
                <td style="width:100px">ШтрихКод</td>
                <td style="width:100px">Примечание</td>
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
                    if(isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element']))
                        echo $model2[$rows['wh_tk_element']] ; /// --- 4  -----
                    echo  '</td>';
                    echo  '<td>';
                    if (isset($rows['ed_izmer']))
                        echo $model5[$rows['ed_izmer']];
                    echo  '</td>';
                    echo  '<td style="text-align:center">';
                    if (isset($rows['ed_izmer_num']))
                        echo $rows['ed_izmer_num'];
                    echo  '</td>';
                    echo  '<td style="text-align:right">';


                    if (isset($rows['bar_code']))
                        if (!empty($rows['bar_code']))
                            echo $rows['bar_code'];
                        else
                            echo "Б/Н";

                    echo  '</td>';
                    echo  '<td>';
                    //echo 'нет';
                    echo  '</td>';
                    echo  '</tr>';
                }

            //       dd($model['array_tk']);


            //        echo  '<tr>';
            //        echo  '<td>';
            //        echo  '</td>';
            //        echo  '<td>';
            //        echo  '</td>';
            //        echo  '<td>';
            //        echo  '</td>';
            //        echo  '<td>';
            //        echo  '</td>';
            //        echo  '<td>';
            //        echo  '</td>';
            //        echo  '<td>';
            //        echo  '</td>';
            //        echo  '</tr>';

            ?>

            </tbody>
        </table>
    <?php } ?>


        <br>

	    <?php if(isset( $model[ 'array_tk' ] ) && !empty( $model[ 'array_tk' ] ))
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

                foreach ( $model['array_tk'] as $rows){

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
                    echo  '<td style="text-align:right">';
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

</div>

<!---->
<!--<div class="print_row">-->
<!---->
<!--    <div class="footer_left" >-->
<!--        <div class="man_sign">Отправитель</div>-->
<!--    </div>-->
<!---->
<!--    <div class="footer_right" >-->
<!--        <div class="man_sign">Получатель</div>-->
<!--    </div>-->
<!---->
<!--</div>-->


