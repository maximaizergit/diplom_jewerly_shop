<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main;

use Bitrix\Main\Context;
CModule::IncludeModule("shiglov.iblockinterface");
use \Shiglov\IBlock\IBlockInterface;
\CModule::IncludeModule("iblock");
class CItemsTabs extends \CBitrixComponent
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

        $sections = CIBlockSection::GetList(
            array(),
            array('IBLOCK_ID' => $this->arParams['IBLOCK_ID']),
            false,
            array('ID', 'CODE', 'NAME')
        );
        $fetchResult = [];
        $sectionsArr = [];
        while ($section = $sections->GetNext()) {

            $this->arResult["SECTIONS"][] = $section;
            $items = CIBlockElement::GetList(
                array('SORT' => 'ASC'),
                array('IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'SECTION_ID' => $section['ID']),
                false,
                array('nTopCount' => 4),
                array('ID', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'NAME')
            );


            while ($item = $items->GetNext()) {
                $elementId = $item['ID']; // ID элемента, для которого нужно получить код раздела
                $arSelect = array('ID', 'IBLOCK_SECTION_ID');
                $arFilter = array('ID' => $elementId);
                $sectionId = $item['IBLOCK_SECTION_ID'];
                $previewPictureSrc = CFile::GetPath($item['PREVIEW_PICTURE']);
                $item['PREVIEW_PICTURE'] = [];
                $item['PREVIEW_PICTURE']['SRC'] = $previewPictureSrc;
                $item['SECTION_CODE'] = $section['CODE'];
                $item['SECTION_NAME'] = $section['NAME'];

                $arPrices = array(); // Массив для сохранения цен
                CCatalogProduct::setUsedCurrency('BYN');
                $optimalPrice = CCatalogProduct::getOptimalPrice(
                    $elementId, 1, array(), 'N', array(), SITE_ID);
                if ($optimalPrice) {
                    // Сохраняем данные об оптимальной цене в массив
                    $arPrices[$optimalPrice['PRICE']['CATALOG_GROUP_ID'] - 1] = array(
                        'PRICE' => $optimalPrice['RESULT_PRICE']['BASE_PRICE'],
                        'CURRENCY' => $optimalPrice['RESULT_PRICE']['CURRENCY']
                    );
                }
                $item['ITEM_PRICES'] = $arPrices;
                $this->arResult['ITEMS'][] = $item;

            }

        }
    }

}
