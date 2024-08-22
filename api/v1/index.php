<?php
// /api/v1/index.php
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/api_log.txt");
// Подключение Битрикса
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
CModule::IncludeModule("phpdevorg.rest");



$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if ($USER->Login($data['authData']['login'], $data['authData']['password']) &&
    $USER->Login($data['authData']['login'], $data['authData']['password'])['TYPE']!='ERROR') {
    // Проверка принадлежности к группе администраторов
    if ($USER->IsAdmin()) {
        if ($selectedMethod === 'getCatalogItems') {
            // Логика для метода getCatalogItems
            $result = array('message' => 'Вы вызвали метод getCatalogItems');
        } elseif ($selectedMethod === 'addItemsToCatalog') {
            foreach ($data['data']['items'] as $item){
                $api = new addToCatalog();
                $api->addToCatalog($item);
            }
        } else {
            $result = array('message' => 'Метод не найден');
        }
        echo "Доступ разрешен";
    } else {
        // Пользователь не администратор, отказ в доступе
        echo "Доступ запрещен";
    }
} else {
    // Ошибка авторизации, отказ в доступе
    echo "Ошибка авторизации";
}






//echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>