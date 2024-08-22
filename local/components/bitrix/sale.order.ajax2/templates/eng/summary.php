<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
?>
		<div class="bx_section">
			<h4><?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?></h4>
			<textarea name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION" style="max-width:70%;" class="input min"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
			<input type="hidden" name="" value="">
			<div style="clear: both;"></div>
		</div>

