<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
IncludeTemplateLangFile(__FILE__); ?>
<div class="search-form col-sm-3">
<form action="<?=$arResult["FORM_ACTION"]?>">
    <div style="display: flex">
        <div class="search_box pull-right">

            <input type="text" name="q" value="" size="15" maxlength="50" placeholder="<?=GetMessage("SEARCH")?>"/>

        </div>
        <input name="s" type="submit" value="<?=GetMessage("SEARCH")?>" />


    </div>

</form>
</div>

