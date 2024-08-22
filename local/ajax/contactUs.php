<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';


use Bitrix\Main\Context;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Loader;

Loader::includeModule('iblock');

$request = Context::getCurrent()->getRequest();
    try {
        $name = $request->get('name');
        $email = $request->get('email');
        $subject = $request->get('subject');
        $msg = $request->get('Message');
        $el = new CIBlockElement;
        $props = [
            'NAME' => $name,
            'EMAIL' => $email,
            'SUBJECT' => $subject
        ];

        $fields = [
            'IBLOCK_ID' => 12, // ID инфоблока
            'NAME' => $name,
            'ACTIVE' => 'Y',
            'PROPERTY_VALUES' => $props,
            'DATE_CREATE' => new DateTime(),
            'DATE_ACTIVE_FROM' => new DateTime(),
            'DETAIL_TEXT' => $msg
        ];
        $el->Add($fields);


        echo json_encode(['success' => true, 'errors' => [], 'data' =>['q'=>$name]]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => [$e->getMessage()], 'data' => []]);
}


// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';