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

        $result = Sale\Internals\BasketTable::getList(array(
            'filter' => array(
                'FUSER_ID' => Sale\Fuser::getId(),
                'ORDER_ID' => null,
                'LID' => SITE_ID,
                'CAN_BUY' => 'Y',
            ),
            'select' => array('BASKET_COUNT', 'BASKET_SUM'),
            'runtime' => array(
                new \Bitrix\Main\Entity\ExpressionField('BASKET_COUNT', 'QUANTITY'),
                new \Bitrix\Main\Entity\ExpressionField('BASKET_SUM', 'SUM(PRICE*QUANTITY)'),
            )
        ))->fetch();
      if ( $request->get('currency')) {
                $conversionResult = round(CCurrencyRates::ConvertCurrency( $result['BASKET_SUM'], 'BYN', $request->get('currency')), 2);
                $result['BASKET_SUM'] = $conversionResult;
      }


        echo json_encode(['success' => true, 'errors' => [], 'data' =>['result'=>$result, 'test'=>$request->get('currency')]]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => [$e->getMessage()], 'data' => []]);
    }


// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';