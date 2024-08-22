<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Корзина");
?>
<?$APPLICATION->IncludeComponent(
    "bitrix:sale.basket.basket",
    "",
    Array(
        "ACTION_VARIABLE" => "action",
        "AUTO_CALCULATION" => "Y",
        "TEMPLATE_THEME" => "blue",
        "COLUMNS_LIST" => array("NAME","DELETE","PRICE","QUANTITY","METAL","STONE","MODEL"),
        "OFFERS_PROPS" => array("METAL","STONE","MODEL"),
        "COMPONENT_TEMPLATE" => "phptemplate",
        "HIDE_COUPON" => "Y",
        "PATH_TO_ORDER" => "/checkout/",
        "PRICE_VAT_SHOW_VALUE" => "N",
        "QUANTITY_FLOAT" => "N",
        "SHOW_FILTER" => "N",
        "SET_TITLE" => "Y",
        "USE_PREPAYMENT" => "N",
        "ALLOW_AUTO_REGISTER" =>"Y"
    )
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>