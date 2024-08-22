<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';




use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Bitrix\Iblock\ElementTable;

// Проверяем, что запрос был отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit('Bad Request');
}

$name = $_POST['form'][0];
$phone = $_POST['form'][1];

if (!Loader::includeModule('iblock')) {
    http_response_code(500);
    exit('Internal Server Error');
}

$element = new CIBlockElement;
$props = [
    'NAME' => $name,
    'PHONE' => $phone,
];
$fields = [
    'IBLOCK_ID' => 8, //   УБРАТЬ ID
    'NAME' => $name,
    'ACTIVE' => 'Y',
    'PROPERTY_VALUES' => $props,
    'DATE_CREATE' => new DateTime(),
    'DATE_ACTIVE_FROM' => new DateTime(),
];
if (!$elementId = $element->Add($fields)) {
    http_response_code(500);
    exit('Internal Server Error');
}

header('Content-Type: application/json');
echo json_encode(['success' => true]);


require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';