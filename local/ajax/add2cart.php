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
        $productId = (int)$request->get('id');

        if ($item = $basket->getExistsItem('catalog',  $productId)) {
            $item->setField('QUANTITY', $item->getQuantity() + 1);
        }
        else {
            $item = $basket->createItem('catalog', $productId);
            $item->setFields([
                'QUANTITY' => 1,
                'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID' => 's1',
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
            ]);

        }
        $basket->save();
        echo json_encode(['success' => true, 'errors' => [], 'data' =>['q'=>$productId]]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => [$e->getMessage()], 'data' => []]);
    }


// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';