<?php

$arrData = [['UF_NAME'=>'testname','UF_DESCRIPTION'=>'testdesc']];

$arResult['DATA'] = $arrData;
$arrHeaders = [
    ['name' => 'Название'],
    ['name' => 'Описание'],
];
$arResult['HEADERS'] = $arrHeaders;
$this->IncludeComponentTemplate();