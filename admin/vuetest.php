<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");


$APPLICATION->IncludeComponent(
    "shiglovvue:test",
    'testtemp'
);


require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>