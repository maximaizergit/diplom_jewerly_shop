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

$availableRatings = [
    23 => '1',
    24 => '2',
    25 => '3',
    26 => '4',
    27 => '5',

];
$ratingValue = $formData['rating'];
    $ratingId = array_search($ratingValue, $availableRatings); // Получаем ID значения рейтинга
$props = [
    'COMMENT' => $formData['comment'],
    'ITEM_ID' => $formData['itemId'],
    'RATING' => $ratingId,
];

$fields = [
    'IBLOCK_ID' => 14, // ID инфоблока
    'NAME' => $formData['name'],
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
