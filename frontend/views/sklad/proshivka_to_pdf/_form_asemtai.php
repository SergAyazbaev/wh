<div class="all_wind">

    <div class="print_row">

        <div class="print_block" >
            <div class="print_head">
                Отправитель:
            </div>
            <div class="print_block_label " >
                <?=$model->wh_debet_name ?>
                <?= ' - <br>' ?>
                <?=$model->wh_debet_element_name ?>
            </div>
        </div>

        <div class="print_block" >
            <div class="print_head">
                Получатель:
            </div>

            <div class="print_block_label" >
                <?=$model->wh_destination_name ?>
                <?= ' - <br>' ?>
                <?=$model->wh_destination_element_name ?>
            </div>
        </div>


    </div>

    <div class="print_row" >
        <div class="print_head">
            Через кого:
        </div>
    </div>


    <div class="print_row">


            <div class="print_block_nakl" >
                <!-- !Ә)ІҢҒ;:ҮҰҚ )!ӘІҢҒ;:ҮҰҚӨҺ -->
                № <?=$model->id ?>  ЖӨНЕЛТПЕ ҚҰЖАТ / НАКЛАДНАЯ № <?=$model->id ?>
            </div>

            <div class="print_block_date" >
                <div class="date_long " >
                    <!--("әіңғ,.үұқөһ-->
                    <div class="date_kun" >күні / от</div>

                    <div class="date_kun2" >
                        <?=date('d.m.Y ', strtotime($model->dt_create ));?>

                    </div>

                </div>
            </div>


    </div>


<!--    ////////////-->
    <div class="print_row">

        <table cellpadding="0" cellspacing="0">

            <thead>
            <tr>
                <td>р/с</td>
                <td style="width:320px">Атауы <br> Наименование </td>
                <td style="width:100px">Штрихкод</td>

                <td style="width:100px"> MSAM </td>
                <td style="width:50px"> Борт.№</td>
                <td style="width:50px"> Гос.№ </td>
                <td style="width:10px"> ТХА</td>
                <td style="width:10px">Прим. </td>
            </tr>

            </thead>


            <tbody>



        <?php
        $x=0;


        $array_tk_amort=yii\helpers\ArrayHelper::toArray($model['array_tk_amort']);
//dd($array_tk_amort);

        foreach ($array_tk_amort as $rows){

            $x++;
            echo  '<tr>';
                echo  '<td>';
                    echo $x;
                echo  '</td>';
                echo  '<td>';
                if (isset($model2[$rows['wh_tk_element']]) && !empty($model2[$rows['wh_tk_element']]))
                    echo $model2[$rows['wh_tk_element']] ; /// --- 4  -----
                echo  '</td>';
                echo  '<td>';
                     if (isset($rows['bar_code']))
                            echo $rows['bar_code'];
                echo  '</td>';
                echo  '<td>';
                        if (isset($rows['msam_code']))
                            echo $rows['msam_code'];
                echo  '</td>';
                echo  '<td>';
                        if (isset($rows['bus_number_bort']))
                            echo $rows['bus_number_bort'];
                echo  '</td>';
                echo  '<td>';
                        if (isset($rows['bus_number_gos']))
                            echo $rows['bus_number_gos'];
                echo  '</td>';
                echo  '<td>';
                        if (isset($rows['the_bird']) && (int)$rows['the_bird']>0)
                            echo "Да";
                echo  '</td>';
                echo  '<td>';
                        if (isset($rows['tx']))
                            echo $rows['tx'];
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

//dd($model4);

//        $array_tk=yii\helpers\ArrayHelper::toArray($model['array_tk']);
//
//        dd($array_tk);
//
//        foreach ($array_tk as $rows){
//
//            //            [wh_tk] => 1
//            //            [wh_tk_element] =>
//            //            [ed_izmer] => 1
//            //            [ed_izmer_num] => 1
//
//            $x++;
//            echo  '<tr>';
//                echo  '<td>';
//                    echo $x;
//                echo  '</td>';
//                echo  '<td>';
//                    if (isset($rows['ed_izmer_num']))
//                        echo $model4[$rows['wh_tk_element']]; /// --- 4  -----
//                echo  '</td>';
//                echo  '<td>';
//                    if (isset($rows['ed_izmer']))
//                        echo $model5[$rows['ed_izmer']];
//                echo  '</td>';
//                echo  '<td>';
//                    if (isset($rows['ed_izmer_num']))
//                        echo $rows['ed_izmer_num'];
//                echo  '</td>';
//                echo  '<td>';
        //                    //echo 'нет';
//                echo  '</td>';
//                echo  '<td>';
//                    //echo 'нет';
//                echo  '</td>';
//            echo  '</tr>';
//        }
        ?>

            </tbody>
        </table>

    </div>

</div>



<div class="print_row">

    <div class="footer_left" >
        <div class="man_sign">руководитель</div>
        <div class="man_sign">отпустил</div>
    </div>

    <div class="footer_right" >
        <div class="man_sign">бухгалтер</div>
        <div class="man_sign">получил</div>
    </div>



</div>





