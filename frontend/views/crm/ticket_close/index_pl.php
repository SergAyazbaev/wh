<?php

use yii\bootstrap\ActiveForm;

?>

<?php $form = ActiveForm::begin(
    [
        'id' => 'ComposeTicket',
        'method' => 'POST',
        'class' => 'form-inline',
        'action' => ['/crm/ticket_close'],

        'options' => [
            //'data-pjax' => 1,
            'autocomplete' => 'off',
        ],

    ]);

///multipart/form-data
?>

<style>
    input {
        max-width: 250px;
    }

    .form-group {
        max-width: 350px;
        background-color: rgba(125, 125, 125, 0.29);
        float: left;
    }

    .razdel {
        width: 100%;
        float: left;
    }
</style>

<div class="razdel">
    <?php
    echo $form->field($model, 'Action')->hiddenInput(['value' => 'AgentTicketCompose'])->label(false);
    echo $form->field($model, 'Subaction')->hiddenInput(['value' => 'SendEmail'])->label(false);
    echo $form->field($model, 'TicketID')->hiddenInput(['value' => '83472'])->label(false);
    ?>
</div>

<div class="razdel">
    <?php

    echo $form->field($model, 'Email')->textInput(['value' => '']);
    ?>
</div>

<div class="razdel">
    <?php
    echo $form->field($model, 'InReplyTo')->textInput(['value' => '']);
    ?>
</div>

<div class="razdel">
    <?php
    echo $form->field($model, 'References')->textInput(['value' => '']);

    ?>
</div>

<div class="razdel">
    <?php

    echo $form->field($model, 'FormID')->hiddenInput(['value' => '1597829959.9534768.09641151'])->label(false);
    echo $form->field($model, 'ResponseID')->hiddenInput(['value' => '1'])->label(false);
    echo $form->field($model, 'ReplyArticleID')->hiddenInput(['value' => '169357'])->label(false);

    echo $form->field($model, 'IsVisibleForCustomerPresent')->hiddenInput(['value' => '1'])->label(false);
    echo $form->field($model, 'FormDraftTitle')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'FormDraftID')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'FormDraftAction')->hiddenInput(['value' => 'FormDraftAction'])->label(false);

    echo $form->field($model, 'From')->textInput(['value' => 'Служба поддержки ТХА &lt;manager@tha.kz&gt;']);
    echo $form->field($model, 'ToCustomer')->textInput(['value' => '']);
    //autocomplete="off"

    echo $form->field($model, 'CustomerInitialValue')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'CustomerKey')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'CustomerQueue')->hiddenInput(['value' => ''])->label(false);

    echo $form->field($model, 'CustomerTicketText')->hiddenInput(['value' => '', 'readonly' => "readonly"])->label(false);

    echo $form->field($model, 'CustomerTicketCounterToCustomer')->hiddenInput(['value' => '0'])->label(false);
    echo $form->field($model, 'CcCustomer')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'CcCustomerInitialValue')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'CcCustomerKey')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'CcCustomerQueue')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'CustomerTicketText')->hiddenInput(['value' => '', 'readonly' => "readonly"])->label(false);
    echo $form->field($model, 'CustomerTicketCounterCcCustomer')->hiddenInput(['value' => '0'])->label(false);

    echo $form->field($model, 'BccCustomer')->hiddenInput(['value' => '', 'autocomplete' => "off"])->label(false); //Скрытая копия:

    echo $form->field($model, 'BccCustomerInitialValue')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'BccCustomerKey')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'BccCustomerQueue')->hiddenInput(['value' => ''])->label(false);
    echo $form->field($model, 'CustomerTicketText')->hiddenInput(['value' => '', 'readonly' => "readonly"])->label(false);
    echo $form->field($model, 'CustomerTicketCounterBccCustomer')->hiddenInput(['value' => '0'])->label(false);

    //Тема
    echo $form->field($model, 'Subject')->hiddenInput(['value' => 'Re: [Ticket#3583372] --'])->label(false);
    echo $form->field($model, 'SubjectError')->hiddenInput()->label(false);
    echo $form->field($model, 'SubjectServerError')->hiddenInput()->label(false);

    echo $form->field($model, 'RichText')->hiddenInput(['value' => 'Здравствуйте!&lt;br /&gt;&amp;nbsp;&lt;br/&gt;&lt;br/&gt;
&lt;br /&gt;С уважением, служба поддержки&lt;br /&gt;ТОО «Транспортный холдинг города Алматы»&lt;br /&gt;Республика Казахстан, 050043, г.Алматы, ул. Рыскулбекова, 33/1&lt;br /&gt;&lt;br /&gt;раб. тел.: +7 727 397 01 91&lt;br /&gt;факс: +7 727 397 01 90&lt;br/&gt;
&lt;br/&gt;
19.08.2020 08:32 (Asia/Almaty) - Manager TXA System написал(а):&lt;br/&gt;
&lt;div  type=&quot;cite&quot; style=&quot;border:none;border-left:solid blue 1.5pt;padding:0cm 0cm 0cm 4.0pt&quot;&gt;comment&lt;/div&gt;&lt;br/&gt;'])
        ->label(false);

    echo $form->field($model, 'RichTextError')->hiddenInput()->label(false);
    echo $form->field($model, 'RichTextServerError')->hiddenInput()->label(false);
    ?>
    <div class="razdel">
        <?php
        echo $form->field($model, 'StateID')->dropdownList([
            '' => 'Выбрать ...',
            "3" => 'Закрыта не успешно',
            "13" => 'Закрыта успешна с превышением времени исполнения 3 часа',
            "2" => 'Закрыта успешно',
            "15" => 'Заявка закрыта после успешной инкасации',
            "16" => 'Заявка закрыта после успешной перезагрузки ПВ',
            "10" => 'Не подтверждена',
            "12" => 'Неверные данные по ТС',
            "9" => 'объединена',
            "7" => 'ожидает автозакрытия',
            "8" => 'ожидает автозакрытия(-)',
            "6" => 'ожидает напоминания',
            "4" => 'selected="selected">открыта',
            "14" => 'Проводится инкасация',
            "17" => 'Проводится перезагрузка ПВ'
        ],
            [
                'options' => ['' => ['Selected' => true]],
                'style' => "width:300px"

            ]
        );

        ?>
    </div>

    <div class="razdel">

        <?php

        echo $form->field($model, 'Day')->dropdownList([
            '' => 'Выбрать ...',
            "1" => '01',
            "2" => '02',
            "3" => '03',
            "4" => '04',
            "5" => '05',
            "6" => '06',
            "7" => '07',
            "8" => '08',
            "9" => '09',
            "10" => '10',
            "11" => '11',
            "12" => '12',
            "13" => '13',
            "14" => '14',
            "15" => '15',
            "16" => '16',
            "17" => '17',
            "18" => '18',
            "19" => '19',
            "20" => '20',
            "21" => '21',
            "22" => '22',
            "23" => '23',
            "24" => '24',
            "25" => '25',
            "26" => '26',
            "27" => '27',
            "28" => '28',
            "29" => '29',
            "30" => '30',
            "31" => '31',

        ],
            [
                'options' => ['' => ['Selected' => true]],
                'style' => "width:300px"

            ]
        );

        ?>


        <?php
        echo $form->field($model, 'Month')->dropdownList([
            '' => 'Выбрать ...',
            "1" => '01',
            "2" => '02',
            "3" => '03',
            "4" => '04',
            "5" => '05',
            "6" => '06',
            "7" => '07',
            "8" => '08',
            "9" => '09',
            "10" => '10',
            "11" => '11',
            "12" => '12',
            "13" => '13',
            "14" => '14',
            "15" => '15',
            "16" => '16',
            "17" => '17',
            "18" => '18',
            "19" => '19',
            "20" => '20',
            "21" => '21',
            "22" => '22',
            "23" => '23',
            "24" => '24',
            "25" => '25',
            "26" => '26',
            "27" => '27',
            "28" => '28',
            "29" => '29',
            "30" => '30',
            "31" => '31'
        ],
            [
                'options' => ['' => ['Selected' => true]],
                'style' => "width:300px"
            ]
        );
        ?>


        <?php
        echo $form->field($model, 'Year')->dropdownList([
            '' => 'Выбрать ...',
            "2020" => '2020',
            "2021" => '2021',
            "2022" => '2022',
            "2023" => '2023',
            "2024" => '2024',
            "2025" => '2025',
        ],
            [
                'options' => ['' => ['Selected' => true]],
                'style' => "width:300px"
            ]
        );

        echo $form->field($model, 'Hour')->hiddenInput(['value' => '01'])->label(false);
        echo $form->field($model, 'Minute')->hiddenInput(['value' => '01'])->label(false);

        ?>
    </div>

    <div id="DayServerError" class="TooltipErrorMessage"><p>Неверная дата!</p></div>
    <div id="HourServerError" class="TooltipErrorMessage"><p>Неверная дата!</p></div>


    <div class="razdel">

        <?php
        ///Видно клиенту:
        echo $form->field($model, 'IsVisibleForCustomer')->hiddenInput(['value' => '1'])->label(false);

        ?>
    </div>
    <div class="razdel">
        <?php


        echo $form->field($model, 'DynamicField_nameTechnics')->hiddenInput(['value' => 'Жапарханов М . '])->label('Имя техника');

        ?>
    </div>
    <div class="razdel">
        <?php

        echo $form->field($model, 'DynamicField_problemIssue')->hiddenInput(['value' => 'Не работает МТТ'])->label('Классификация проблемы');


        ?>
    </div>
    <div class="razdel">
        <?php

        echo $form->field($model, 'DynamicField_statePE')->dropdownList([
            '' => 'Выбрать ...',
            "Белый экран" => 'Белый экран',
            "ДТП" => 'ДТП',
            "Заблокирован 1-й терминал" => 'Заблокирован 1 - й терминал',
            "Заблокирован 2-й терминал" => 'Заблокирован 2 - й терминал',
            "Заблокирован 3-й терминал" => 'Заблокирован 3 - й терминал',
            "Заблокирован 4-й терминал" => 'Заблокирован 4 - й терминал',
            "Заблокированы оба терминала" => 'Заблокированы оба терминала',
            "Исполнение в парке" => 'Исполнение в парке',
            "Исполнение в парке  Ремонт поручня " => 'Исполнение в парке  Ремонт  поручня',
            "На экране  заблокирована МСАМ карта " => 'На экране заблокирована МСАМ карта',
            "На экране  Смена окончена " => 'На экране  Смена окончена ',
            "На экране  требуется синхронизация " => 'На экране  требуется синхронизация ',
            "На экране  фатальная ошибка " => 'На экране фатальная ошибка',
            "Не принимает оплату" => 'Не принимает оплату',
            "Не проходит ОСИ" => 'Не проходит ОСИ',
            "Не работает  ПВ (озвучка)" => 'Не работает ПВ(озвучка)',
            "Не работает GPS" => 'Не работает GPS',
            "Не работает микрофон ПВ" => 'не работает микрофон ПВ',
            "Не работает МТТ" => 'Не работает МТТ',
            "Не работает ПВ (вектор)" => 'Не работает ПВ(вектор)',
            "Не работает ПВ (загрузка)" => 'Не работает ПВ(загрузка)',
            "Не работает ПВ (оплата)" => 'Не работает ПВ(оплата)',
            "Не работает ПВ (рейсы)" => 'Не работает ПВ(рейсы)',
            "Не работает ПВ (связь)" => 'Не работает ПВ(связь)',
            "Не работает ПВ (сенсор)" => 'Не работает ПВ(сенсор)',
            "Не работает ПВ (штекер)" => 'Не работает ПВ(штекер)',
            "Не читает MSAM карту" => 'Не читает MSAM карту',
            "Нет звука на ТТ при оплате" => 'Нет звука на ТТ при оплате',
            "Нет связи с терминалами" => 'Нет связи с терминалами',
            "Отключен 1-й терминал" => 'Отключен 1 - й терминал',
            "Отключен 2-й терминал" => 'Отключен 2 - й терминал',
            "Отключен 3-й терминал" => 'Отключен 3 - й терминал',
            "Отключен 4-й терминал" => 'Отключен 4 - й терминал',
            "Отключены оба терминала" => 'Отключены оба терминала',
            "ПВ часто отключается" => 'ПВ часто отключается',
            "Ремонт коммуникаций по салону" => 'Ремонт коммуникаций по салону',
            "Терминалы зависли" => 'Терминалы зависли',
            "Требуется ремонт поручня" => 'Требуется ремонт поручня',
            "Умышленная порча оборудования" => 'Умышленная порча оборудования',
            "Черный экран" => 'Черный экран',
        ],
            [
                'options' => ['' => ['Selected' => true]],
                'style' => "width:300px"

            ]
        );

        ?>
    </div>
    <div class="razdel">
        <?php

        echo $form->field($model, 'DynamicField_actions')->dropdownList([
            '' => 'Выбрать ...',
            "1029" => 'Водитель не дождался мастера',
            "1033" => 'Водитель не отвечает',
            "1030" => 'Водитель отказался от ремонта',
            "1042" => 'Восстановление питания',
            "1028" => 'Демонтаж АСУОП завершен',
            "1041" => 'Демонтаж МТТ',
            "1040" => 'Демонтаж ПВ',
            "1046" => 'Демонтаж стабилизатора МТТ',
            "1001" => 'Замена 1 - го терминала',
            "1002" => 'Замена 2 - го терминала',
            "1009" => 'Замена автомобильного стабилизатора от МТТ',
            "1007" => 'Замена антенны',
            "1047" => 'Замена колодки',
            "1012" => 'Замена крепления МТТ',
            "1010" => 'Замена крепления ПВ',
            "1011" => 'Замена крепления Терминала',
            "1014" => 'Замена МСАМ',
            "1005" => 'Замена МТТ',
            "1003" => 'Замена обоих терминалов',
            "1004" => 'Замена ПВ',
            "1016" => 'Замена поручня',
            "1015" => 'Замена предохранителя',
            "1044" => 'Замена разъёма',
            "1006" => 'Замена свитча',
            "1013" => 'Замена сим карты',
            "1008" => 'Замена стабилизатора VPS01',
            "1026" => 'Мастер проверил, всё оборудование работает',
            "1027" => 'Монтаж АСУОП завершен',
            "1045" => 'Нет доступа к ПЕ',
            "1019" => 'Обмен сервера инкассации',
            "1043" => 'ПЕ не отвечает требованиям к установке АСУОП',
            "1038" => 'Перезапуск МТТ',
            "1037" => 'Перезапуск ПВ',
            "1036" => 'Перезапуск терминалов',
            "1025" => 'Переобжим коннектора RJ45',
            "1022" => 'Переобжим коннектора молекс на ПВ',
            "1023" => 'Переобжим коннектора молекс на терминале',
            "1024" => 'Переобжим коннектора на стабилизаторе VPS01',
            "1035" => 'Поломка самого ТС',
            "1018" => 'Привязка ПВ',
            "1017" => 'Привязка служебной карты МТТ',
            "1021" => 'Ремонт коммуникации по салону, см примечание',
            "1039" => 'Со слов водителя МТТ работает',
            "1031" => 'Со слов водителя ПВ работает',
            "1032" => 'Со слов водителя терминалы работают',
            "1034" => 'Телефон отключен',
            "1020" => 'Укрепление поручня в парке',
        ],
            [
                'options' => ['' => ['Selected' => true]],
                'style' => "width:300px"

            ]
        );

        ?>
    </div>
    <div class="razdel">
        <?php

        echo $form->field($model, 'DynamicField_clarification')->hiddenInput(['value' => 'илолорлорол'])->label('Примечание');
        echo $form->field($model, 'DynamicField_msamMaster')->hiddenInput(['value' => ''])->label(false);
        echo $form->field($model, 'DynamicField_msamSlave')->hiddenInput(['value' => ''])->label('Т - номер Slave');

        echo $form->field($model, 'TimeUnits')->hiddenInput(['value' => ''])->label(false);

        ?>
    </div>

    <div class="razdel">
        <button class="CallForAction Primary" id="submitRichText" accesskey="g" title="Отправить письмо! (g)"
                type="submit" value="Отправить письмо!">
            <span><i class="fa fa-envelope-o"></i> Отправить письмо!</span>
        </button>
        &nbsp;или&nbsp;
        <button class="CallForAction" id="FormDraftSave" accesskey="s" title="Сохранить как новый черновик (s)">
            <span><i class="fa fa-pencil-square-o"></i> Сохранить как новый черновик</span>
        </button>
    </div>


    <?php ActiveForm::end(); ////////////////////////        ?>


