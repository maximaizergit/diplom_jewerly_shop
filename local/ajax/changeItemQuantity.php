<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';


use Bitrix\Main\Context;
//use Bitrix\Main\Localization\Loc;
//use Bitrix\Catalog\ProductTable;
use Bitrix\Sale;
use Bitrix\Sale\Basket;
CModule::IncludeModule("sale");

Bitrix\Main\Loader::includeModule("catalog");
$basket = Basket::loadItemsForFUser(Sale\Fuser::getId(), Context::getCurrent()->getSite());
$request = Context::getCurrent()->getRequest();
    try {
        $productId = (int)$request->get('pid');
        $action = (string)$request->get('action');
        $item = $basket->getExistsItem('catalog',  $productId);
        $debug = $action;
        if ($item) {
            if ($action == 'decrement'){
                $item->setField('QUANTITY', $item->getQuantity() - 1);
                $debug = 'if err dec';
            }elseif($action == 'increment'){
                $item->setField('QUANTITY', $item->getQuantity() + 1);
                $debug = $item->getQuantity();
            }
            elseif ($action = 'setValue'){
                $item->setField('QUANTITY', (int)$request->get('value'));
            }
            $resultQuantity = $item->getQuantity();
            $basket->save();
        }

        echo json_encode(['success' => true, 'errors' => [], 'data' =>['quantity'=>$productId]]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => [$e->getMessage()], 'data' => []]);
    }


// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';