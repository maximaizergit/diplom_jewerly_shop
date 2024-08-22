<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';


use Bitrix\Main\Context;
CModule::IncludeModule("sale");

$request = Context::getCurrent()->getRequest();
    try {
        $arPrices = $request->get('arPrices');
        $currency='null';
        if (isset($_SESSION['CURRENCY_ID'])){
            $currency =$_SESSION['CURRENCY_ID'];
        }elseif(isset($_GET['currency'])){
            $_SESSION['CURRENCY_ID'] =$_GET['currency'];
            $currency =$_GET['currency'];
        }else{
            $currency = 'BYN';
        }


        $convertedPrices=[];

            foreach ($arPrices as $key=>$value) {

                $conversionResult = round(CCurrencyRates::ConvertCurrency($value[0], 'BYN', $currency), 2);
                $value[0] = $conversionResult;
                $value[1] = $currency;
                $convertedPrices[$key] = $value;
            }


        $APPLICATION->RestartBuffer();
        echo json_encode(['success' => true, 'errors' => [], 'data' =>[$convertedPrices, 'cur'=>$currency]]);
        die();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => [$e->getMessage()], 'data' => []]);
    }


// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';