<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (!\Bitrix\Main\Application::getInstance()->getContext()->getRequest()->isAjaxRequest()) {
    die();
}

$url = $_POST['url'];
$tags = $_POST['tags'];


$response = array(
    'success' => false,
    'message' => 'Данные успешно получены и обработаны.',
    'data' => $this->sqr(2),
    'test' => 'test'
);

header('Content-Type: application/json');
echo json_encode($response);
