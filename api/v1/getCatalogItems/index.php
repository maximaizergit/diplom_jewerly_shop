<?php
// /api/v1/getCatalogItems/index.php

// Подключение Битрикса
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// Определение выбранного метода
$selectedMethod = basename(dirname(__FILE__));

// Передача $selectedMethod в подключаемый файл
include($_SERVER["DOCUMENT_ROOT"]."/api/v1/index.php");
//echo json_encode($result);
?>