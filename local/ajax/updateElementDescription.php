<?php

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';


use Bitrix\Main\Context;

Bitrix\Main\Loader::includeModule('iblock');

$request = Context::getCurrent()->getRequest();
$elementId = intval($request->getPost("elementId"));
$newDescription = $request->getPost("newDescription");

if ($elementId && $newDescription) {
    $element = new CIBlockElement;
    $elementFields = $element->GetByID($elementId)->Fetch();
    $elementFields["DETAIL_TEXT"] = $newDescription[0];
    $elementFields["PREVIEW_TEXT"] = $newDescription[1];
    $element->Update($elementId, $elementFields);
}

// Отправляем ответ клиенту
header('Content-Type: application/json');
echo json_encode(array('success' => true));

// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';