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

// Загружаем модуль инфоблоков
Loader::includeModule('iblock');

// Получаем данные формы
$context = Context::getCurrent();
$request = $context->getRequest();
$formData = $request->getPostList()->toArray();

// Создаем новый элемент инфоблока
$el = new CIBlockElement;



$props = [
    'ITEM_ID' => $formData['itemId'],
    'USER_NAME' => $formData['userName'],
];

$fields = [
    'IBLOCK_ID' => 17, // ID инфоблока
    'NAME' => $formData['userName'],
    'ACTIVE' => 'Y',
    'DETAIL_TEXT' => $formData['comment'],
    'PROPERTY_VALUES' => $props,
    'DATE_CREATE' => new DateTime(),
    'DATE_ACTIVE_FROM' => new DateTime(),

];
$el->Add($fields);

header('Content-Type: application/json');
echo json_encode(['success' => true, 'props'=>$props]);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
