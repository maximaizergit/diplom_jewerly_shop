<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';


use Bitrix\Main\Context;
//use Bitrix\Main\Localization\Loc;
//use Bitrix\Catalog\ProductTable;
use Bitrix\Sale;
use Bitrix\Sale\Basket;

CModule::IncludeModule('subscribe');

$request = Context::getCurrent()->getRequest();
    try {
        $email = $request->get('email');


        $strWarning = '';
        $arFields = Array(
            "USER_ID" => false,
            "FORMAT" => "text",
            "EMAIL" => $email,
            "ACTIVE" => "Y",
            "RUB_ID" => 1
        );
        $subscr = new CSubscription;
        //can add without authorization
        $ID = $subscr->Add($arFields);
        if($ID>0)
            CSubscription::Authorize($ID);
        else
            $strWarning .= "Error adding subscription: ".$subscr->LAST_ERROR;
        echo json_encode(['success' => true, 'errors' => [$strWarning], 'data' =>['q'=>'success']]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => [$e->getMessage()], 'data' => []]);
    }


// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';