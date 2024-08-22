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
        'IBLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Тип инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arTypesEx,
            'REFRESH' => 'Y',
            'DEFAULT' => 'null'
        ],
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'REFRESH' => 'Y',
            'DEFAULT' => 'null'
        ],
        'SECTION_CODE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Раздел инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arSections,
            'REFRESH' => 'Y',
            'DEFAULT' => 'null'
        ],
        'URL_PARAMETER' => [
            'PARENT' => 'BASE',
            'NAME' => "URL",
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ],
        'CLASS_PARAMETER' => [
            'PARENT' => 'BASE',
            'NAME' => "Класс каталога",
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ],
        'EXCLUSION_PARAMETER' => [
            'PARENT' => 'BASE',
            'NAME' => "Ссылки исключения",
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ],
        'QUERY_PARAMETER' => [
            'PARENT' => 'BASE',
            'NAME' => "Запрос",
            'TYPE' => 'TEXTAREA',
            'ROWS' => 10,
            'DEFAULT' => '',
        ],
        'CODES_PARAMETER' => [
            'PARENT' => 'BASE',
            'NAME' => "Коды параметров",
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ],
    ],
];

