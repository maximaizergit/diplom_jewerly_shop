<?php
// /api/v1/echo/index.php

// Подключение Битрикса
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// Получение JSON-данных из тела запроса
$jsonData = file_get_contents('php://input');

// Раскодирование JSON-данных
$data = json_decode($jsonData, true);

// Возвращение данных в формате JSON
header('Content-Type: application/json');
echo json_encode($data);
?>