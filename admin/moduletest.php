<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/csv_data.php");

$APPLICATION->SetTitle("admin");
CModule::IncludeModule("shiglov.iblockinterface");
use \Shiglov\IBlock\D7IBlockInterface;
echo '<pre>';

var_dump(D7IBlockInterface::createElement(6, ['NAME'=>'TEST'], 500, 100));
echo '</pre>';
?>

<h1>hello world</h1>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>