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

$arComponentParameters = [
    'GROUPS' => [
        'SETTINGS' => [
            'NAME' => 'Доп. параметры',
            'SORT' => 200,
        ],
    ],
    'PARAMETERS' => [
        'IBLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => "Тип инфоблока",
            'TYPE' => 'LIST',
            'VALUES' => $arTypesEx,
            'REFRESH' => 'Y',
        ],
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => "ID инфоблока",
            'TYPE' => 'LIST',
            'VALUES' => $arIBlocks,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y',
        ],
        'URL_PARAMETER' => [
            'PARENT' => 'SETTINGS',
            'NAME' => "URL",
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ],
        'CLASS_PARAMETER' => [
            'PARENT' => 'SETTINGS',
            'NAME' => "Класс каталога",
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ],
        'EXCLUSION_PARAMETER' => [
            'PARENT' => 'SETTINGS',
            'NAME' => "Ссылки исключения",
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ],

    ],
];
