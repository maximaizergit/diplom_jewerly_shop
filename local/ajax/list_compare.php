<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->IncludeComponent(
    "bitrix:catalog.compare.list",
    "",
    array(
        "IBLOCK_TYPE" => "1c_catalog", //Сюда ваш тип инфоблока каталога
        "IBLOCK_ID" => "1", //Сюда ваш ID инфоблока каталога
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "DETAIL_URL" => "#SECTION_CODE#",
        "COMPARE_URL" => "/catalog/compare.php",
        "NAME" => "CATALOG_COMPARE_LIST",
        "AJAX_OPTION_ADDITIONAL" => ""
    ),
    false
);