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
    28 => '1',
    29 => '2',
    30 => '3',
    31 => '4',
    32 => '5',
];
$ratingValue = $formData['rating'];
$ratingId = array_search($ratingValue, $availableRatings); // Получаем ID значения рейтинга

// Проверяем, существует ли уже оценка для этого itemId от пользователя с id userId
$filter = [
    'IBLOCK_ID' => 16, // ID инфоблока
    'PROPERTY_ITEM_ID' => $formData['itemId'],
    'PROPERTY_USER_ID' => $formData['userId'],
];
$select = ['ID'];
$result = CIBlockElement::GetList([], $filter, false, false, $select);
if ($existingRating = $result->Fetch()) {
    $elementId = $existingRating['ID'];
    CIBlockElement::SetPropertyValuesEx($elementId, false, ['RATING' => $ratingId]);
} else {
    // Оценки не существует, добавляем новую запись
    $props = [
        'ITEM_ID' => $formData['itemId'],
        'USER_ID' => $formData['userId'],
        'RATING' => $ratingId,
    ];

    $fields = [
        'IBLOCK_ID' => 16, // ID инфоблока
        'NAME' => bin2hex(random_bytes(8)),
        'ACTIVE' => 'Y',
        'PROPERTY_VALUES' => $props,
        'DATE_CREATE' => new DateTime(),
        'DATE_ACTIVE_FROM' => new DateTime(),
    ];
    $el->Add($fields);
}

header('Content-Type: application/json');
echo json_encode(['success' => true, 'props'=>$el]);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
