<body>
<div class="title_akt" style="text-align: center;margin-bottom: 8px;font-weight:bold;font-size: 9px;">
    АКТ
    <br>
    МОНТАЖА ОБОРУДОВАНИЯ
    <br>
    и СЕРВИСНО-ТЕХНИЧЕСКОГО ОБСЛУЖИВАНИЯ

</div>

<div class="title_head" style="margin-bottom: 10px">
    № <?= $model->id ?>
    <br>
    г.Алматы, <?= date('d.m.Y время H:i', $model->dt_create_timestamp); ?>
</div>


<div class="simple">
    <div class="title_head_left">
        Гос № сервисной машины
    </div>
    <div class="title_head_right">
        _____
    </div>
</div>
<div class="simple">
    <div class="title_head_left">
        Наименвание перевозчика
    </div>
    <div class="title_head_right">
        <?= $model->wh_destination_name ?>
    </div>
</div>
<div class="simple">
    <div class="title_head_left">
        Гос № ТС Перевозчика
    </div>
    <div class="title_head_right">
        <?= $model->wh_destination_element_name ?>
    </div>
</div>

<div class="simple">
    <div class="title_head_left">
        Борт № ТС Перевозчика
    </div>
    <div class="title_head_right">
        <?= $model->wh_destination_element_name ?>
    </div>
</div>

<div class="simple">
    <div class="title_head_left">
        Маршрут
    </div>
    <div class="title_head_right">
        ______
    </div>
</div>

<div class="simple">
    Основание (№ заявки) ______________________________
</div>
<div class="simple">

</div>


<!--    ////////////-->
<div class="print_row">

    <table cellspacing="0" style="width:100%">
        <thead>
        <tr>
            <td rowspan="2" style="text-align:center">№</td>
            <td rowspan="2" style="text-align:center">Наименование АСУОП</td>
            <td colspan="2" style="text-align:center">МОНТАЖ</td>
        </tr>
        <tr>
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
            //ddd($rows);

            $x++;
            echo '<tr>';

            echo '<td>';
            echo $x;
            echo '</td>';
            echo '<td>';
            if (isset($rows['wh_tk_element']) && !empty($rows['wh_tk_element'])) {
                echo $spr_globam_element[$rows['wh_tk_element']];
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
            echo '</tr>';
        }
        ?>

        </tbody>
    </table>

</div>


<div class="print_row">
    Виды работ, проведенные при сервисном обслуживании ______________________________________________

</div>


<div class="print_row">
    С местом установки и схемой подключения проводки и оборудования согласен и претензий не имею
</div>

<div class="box_right">
    <div class="title_head_left">
        Представитель Превозчика
        <br>
        Ф.И.О., должность, подпись
    </div>
</div>
<br>

<div class="box_right">
    <div class="title_head_left">
        Представитель ТОО "Guidjet TI" <br>(Гайджет ТиАй)
        <br>
        Ф.И.О., должность, подпись
    </div>
</div>

<br> Данный Акт монтажа/демонтажа оборудования и сервисно-технического обслуживания является документом,
подтверждающим фактическую установку проводки и оборудования и правовым основанием для возникновения
материальной ответственности
Перевозчика перед ТОО "Транспортный холдинг города Алматы" согласно Договору о полной материальной
ответственности.

</body>



