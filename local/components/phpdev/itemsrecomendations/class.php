<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main;

use Bitrix\Main\Context;
CModule::IncludeModule("shiglov.iblockinterface");
use \Shiglov\IBlock\IBlockInterface;
\CModule::IncludeModule("iblock");
class CItemsRecomendations extends \CBitrixComponent
{

    public function executeComponent()
    {
        $this->prepareResult();
        if (!isset($this->arParams['SKIP_TEMPLATE']) || !$this->arParams['SKIP_TEMPLATE'])
        {
            $this->includeComponentTemplate();
        }

        return $this->arResult;
    }

    private function prepareResult()
    {
        $this->arResult['ITEMS'] = $this::getRandomElements();
    }
    function getRandomElements(){

        $items = CIBlockElement::GetList(
            array('RAND' => 'ASC'),
            array('IBLOCK_ID' =>$this->arParams['IBLOCK_ID']),
            false,
            array('nTopCount' => 12),
            array('ID', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'NAME')
        );

        $recomendedItems = array();
        while ($item = $items->GetNext()) {
            $elementId = $item['ID'];
            $previewPictureSrc = CFile::GetPath($item['PREVIEW_PICTURE']);
            $item['PREVIEW_PICTURE'] = [];
            $item['PREVIEW_PICTURE']['SRC'] = $previewPictureSrc;

            $arPrices = array();
            CCatalogProduct::setUsedCurrency('BYN');
            $optimalPrice = CCatalogProduct::getOptimalPrice(
                $elementId, 1, array(), 'N', array(), SITE_ID
            );
            if ($optimalPrice) {
                $arPrices[$optimalPrice['PRICE']['CATALOG_GROUP_ID'] - 1] = array(
                    'PRICE' => $optimalPrice['RESULT_PRICE']['BASE_PRICE'],
                    'CURRENCY' => $optimalPrice['RESULT_PRICE']['CURRENCY']
                );
            }
            $item['ITEM_PRICES'] = $arPrices;
            $recomendedItems[] = $item;
        }
        return $recomendedItems;
    }

}
