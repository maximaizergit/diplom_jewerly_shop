<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main;

use Bitrix\Main\Context;
CModule::IncludeModule("shiglov.iblockinterface");
use \Shiglov\IBlock\IBlockInterface;
\CModule::IncludeModule("iblock");
class CItem extends \CBitrixComponent
{

    public function executeComponent()
    {
        $this->prepareResult();
        if (!isset($this->arParams['SKIP_TEMPLATE']) || !$this->arParams['SKIP_TEMPLATE']) {
            $this->includeComponentTemplate();
        }

        return $this->arResult;
    }

    private function prepareResult()
    {

        $elementID = $this->arParams['ELEMENT_ID'];
        CModule::IncludeModule('catalog');
        //$arPrices[$item['ID']] = array($price["PRICE"], $price['CURRENCY']);
        $this->arResult=IBlockInterface::getElementAllData($elementID);


    }
}


