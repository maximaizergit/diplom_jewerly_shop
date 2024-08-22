<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog\ProductTable;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $elementEdit
 * @var string $elementDelete
 * @var string $elementDeleteParams
 * @var string $componentPath
 */

global $APPLICATION;

$positionClassMap = array(
	'left' => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right' => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top' => 'product-item-label-top'
);


$arParams['~MESS_BTN_BUY'] = $arParams['~MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCT_TPL_MESS_BTN_BUY');
$arParams['~MESS_BTN_DETAIL'] = $arParams['~MESS_BTN_DETAIL'] ?: Loc::getMessage('CT_BCT_TPL_MESS_BTN_DETAIL');
$arParams['~MESS_BTN_COMPARE'] = $arParams['~MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCT_TPL_MESS_BTN_COMPARE');
$arParams['~MESS_BTN_SUBSCRIBE'] = $arParams['~MESS_BTN_SUBSCRIBE'] ?: Loc::getMessage('CT_BCT_TPL_MESS_BTN_SUBSCRIBE');
$arParams['~MESS_BTN_ADD_TO_BASKET'] = $arParams['~MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCT_TPL_MESS_BTN_ADD_TO_BASKET');
$arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCT_TPL_MESS_PRODUCT_NOT_AVAILABLE');
$arParams['~MESS_NOT_AVAILABLE_SERVICE'] = ($arParams['~MESS_NOT_AVAILABLE_SERVICE'] ?? '')
	?: Loc::getMessage('CP_BCT_TPL_MESS_PRODUCT_NOT_AVAILABLE_SERVICE')
;
$arParams['~MESS_SHOW_MAX_QUANTITY'] = $arParams['~MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCT_CATALOG_SHOW_MAX_QUANTITY');
$arParams['~MESS_RELATIVE_QUANTITY_MANY'] = $arParams['~MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCT_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['~MESS_RELATIVE_QUANTITY_FEW'] = $arParams['~MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCT_CATALOG_RELATIVE_QUANTITY_FEW');

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($this->randString()));
$containerName = 'catalog-top-container';
?>


	<?
	if (!empty($arResult['ITEMS']) && !empty($arResult['ITEM_ROWS']))
	{
		$generalParams = [
			'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
			'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
			'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
			'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
			'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
			'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
			'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
			'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
			'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'ADD_PROPERTIES_TO_BASKET' => $arParams['ADD_PROPERTIES_TO_BASKET'],
			'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'],
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'COMPARE_PATH' => $arParams['COMPARE_PATH'],
			'COMPARE_NAME' => $arParams['COMPARE_NAME'],
			'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
			'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
			'LABEL_POSITION_CLASS' => $labelPositionClass,
			'DISCOUNT_POSITION_CLASS' => $discountPositionClass,
			'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
			'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],
			'~BASKET_URL' => $arParams['~BASKET_URL'],
			'~ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'~BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
			'~COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
			'~COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
			'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
			'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],
			'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
			'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
			'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],
			'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
			'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
		];

		$areaIds = [];
		$itemParameters = [];


		?>

		<!-- items-container -->
		<?



        ?>

<div class="tab-content">

            <?$prevCode = ''; // Переменная для хранения предыдущего значения 'CODE'

        foreach ($fetchResult['ITEMS'] as $item) {


            if ($item['SECTION_CODE'] !== $prevCode) {
                echo '</div>';
                if (!empty($item['SECTION_NAME'])) {
                    echo '<div class="tab-pane fade active in" id="'.$item['SECTION_NAME'].'">' .'</div>';
                }
                else{
                    echo '<div class="catalog-items-sector__title cnt title">' . 'Название' . '</div>';

                }
                echo '<div class="catalog-items-block">';
            }

            $APPLICATION->IncludeComponent(
                'bitrix:catalog.item',
                'maincatalogitem',
                array('RESULT' => array(
                    'ITEM' => $item,
                    'AREA_ID' => $areaIds[$item['ID']],
                    'TYPE' => $arResult['ITEM_ROWS'][0]['TYPE'],
                    'BIG_LABEL' => 'N',
                    'BIG_DISCOUNT_PERCENT' => 'N',
                    'BIG_BUTTONS' => 'Y',
                    'SCALABLE' => 'N'
                ),
                    'PARAMS' => $generalParams + $itemParameters[$item['ID']],
                ),
                $component,
                array('HIDE_ICONS' => 'Y')
            );


            $prevCode = $item['SECTION_CODE'];
        }

        echo '</div>';

        ?>

            <?php

		unset($rowItems);

		unset($itemParameters);
		unset($areaIds);

		unset($generalParams);

	}
	else
	{
//		// load css for bigData/deferred load
//		$APPLICATION->IncludeComponent(
//			'bitrix:catalog.item',
//			'',
//			array(),
//			$component,
//			array('HIDE_ICONS' => 'Y')
//		);
	}

	?>
</div>
<script>
	BX.message({
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>'
	});
	var <?=$obName?> = new JCCatalogTopComponent({
		siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
		componentPath: '<?=CUtil::JSEscape($componentPath)?>',
		deferredLoad: false, // enable it for deferred load
		initiallyShowHeader: '<?=!empty($arResult['ITEM_ROWS'])?>',
		bigData: <?=CUtil::PhpToJSObject($arResult['BIG_DATA'])?>,
		template: '<?=CUtil::JSEscape($signedTemplate)?>',
		ajaxId: '<?=CUtil::JSEscape($arParams['AJAX_ID'] ?? '')?>',
		parameters: '<?=CUtil::JSEscape($signedParams)?>',
		container: '<?=$containerName?>'
	});
</script>
