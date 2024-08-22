<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
{
	die();
}
/** @var array $arCurrentValues */

use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock'))
{
	return;
}

$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

$arTypesEx = CIBlockParameters::GetIBlockTypes();

$arIBlocks = [];
$iblockFilter = [
    'ACTIVE' => 'Y',
];
if (!empty($arCurrentValues['IBLOCK_TYPE']))
{
    $iblockFilter['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}
if (isset($_REQUEST['site']))
{
    $iblockFilter['SITE_ID'] = $_REQUEST['site'];
}
$db_iblock = CIBlock::GetList(["SORT"=>"ASC"], $iblockFilter);
while($arRes = $db_iblock->Fetch())
{
    $arIBlocks[$arRes["ID"]] = "[" . $arRes["ID"] . "] " . $arRes["NAME"];
}

$arSections = [];

    $sectionFilter = [
        'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
        'ACTIVE' => 'Y',
    ];
    $dbSections = CIBlockSection::GetList(["SORT" => "ASC"], $sectionFilter);
    while ($arSection = $dbSections->Fetch()) {
        $arSections[$arSection["CODE"]] = "[" . $arSection["ID"] . "] ".$arSection["CODE"] ;
    }




$arComponentParameters = [
    'GROUPS' => [

    ],
    'PARAMETERS' => [
        'PRODUCT_IDS' => [
            'PARENT' => 'BASE',
            'NAME' => 'ID продуктов в корзине',
            'TYPE' => 'ARRAY',
            'REFRESH' => 'Y',
            'DEFAULT' => []
        ],

    ],
];

