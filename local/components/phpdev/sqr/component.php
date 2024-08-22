<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if($this->startResultCache())//startResultCache используется не для кеширования html, а для кеширования arResult
{
    //$this - экземпляр CDemoSqr
    $arResult["Y"] = $this->sqr($arParams["X"]);
}
$this->includeComponentTemplate();
?>