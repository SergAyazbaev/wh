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

    <div class="print_row" >
        <div class="print_head">
            Через кого::
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
                <td>ед.<br>изм</td>
                <td>Кол-<br>во</td>
                <td style="width:100px">Бағасы<br> Цена</td>
                <td style="width:100px">Жиынтығы<br> Сумма</td>
            </tr>

            </thead>


            <tbody>



        <?php
        $x=0;

      if (isset($model['array_tk_amort']) && !empty($model['array_tk_amort'])) {

          foreach ($model['array_tk_amort'] as $rows) {

              $x++;
              echo '<tr>';
              echo '<td>';
              echo $x;
              echo '</td>';
              echo '<td>';
              if(isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element']))
                  echo $model2[$rows['wh_tk_element']];  /// --- 4  -----
              echo '</td>';
              echo '<td>';
              echo $model5[$rows['ed_izmer']];
              echo '</td>';
              echo '<td>';
              echo $rows['ed_izmer_num'];
              echo '</td>';
              echo '<td>';
              //echo 'нет';
              echo '</td>';
              echo '<td>';
              //echo 'нет';
              echo '</td>';
              echo '</tr>';
          }
      }
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





