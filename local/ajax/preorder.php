<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';


use Bitrix\Main\Context;
//use Bitrix\Main\Localization\Loc;
//use Bitrix\Catalog\ProductTable;
use Bitrix\Sale;
use Bitrix\Sale\Basket;
use Shiglov\IBlock\HighloadBlockInterface;
CModule::IncludeModule("shiglov.iblockinterface");
$request = Context::getCurrent()->getRequest();
try {
    $productId = (int)$request->get('id');
    $email = $request->get('email');

    $res = HighloadBlockInterface::getElements(5,["*"],array(),['UF_EMAIL'=>$email]);
    if(!empty($res)){

        $isExsist=false;
        foreach ($res[0]['UF_ITEM_ID'] as $item){
            if($item == $productId){
                $isExsist=true;
            }
        }

        if(!$isExsist){
            $data = $res[0]['UF_ITEM_ID'];
            $data[] = $productId;
            $test = HighloadBlockInterface::setProps(5,$res[0]['ID'],['UF_ITEM_ID'=>$data]);
        }
    }
    else{
        HighloadBlockInterface::createElement(5,['UF_EMAIL'=>$email, 'UF_ITEM_ID'=>[$productId]]);
    }


    echo json_encode(['success' => true, 'errors' => [], 'data' =>['res'=>$test]]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'errors' => [$e->getMessage()], 'data' => []]);
}


// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';