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

// Проверяем, был ли загружен файл
if (!empty($_FILES['file']['tmp_name'])) {
    $fileArray = [
        'name' => $_FILES['file']['name'],
        'size' => $_FILES['file']['size'],
        'tmp_name' => $_FILES['file']['tmp_name'],
        'type' => $_FILES['file']['type'],
        'MODULE_ID' => 'iblock',
    ];

    $fileId = CFile::SaveFile($fileArray, 'my_files');
}
$props = [
    'MATERIAL' => $formData['material'],
    'SIZE' => $formData['size'],
    'STONE' => $formData['stone'],
    'FILE' => $fileId,
    'NAME' => $formData['name'],
    'PHONE' => $formData['phone'],
    'EMAIL' => $formData['email'],
    'COMMENT' => $formData['comment'],
];

$fields = [
    'IBLOCK_ID' => 9, // ID инфоблока
    'NAME' => $formData['name'],
    'ACTIVE' => 'Y',
    'DETAIL_TEXT' => $formData['comment'],
    'PREVIEW_TEXT' => $formData['comment'],
    'PROPERTY_VALUES' => $props,
    'DATE_CREATE' => new DateTime(),
    'DATE_ACTIVE_FROM' => new DateTime(),
];
$el->Add($fields);

header('Content-Type: application/json');
echo json_encode(['success' => true]);

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';
