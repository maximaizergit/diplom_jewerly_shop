<?php
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';


use Bitrix\Main\Context,
    Bitrix\Currency\CurrencyManager,
    Bitrix\Sale\Order,
    Bitrix\Sale\Basket,
    Bitrix\Sale\Delivery,
    Bitrix\Sale\PaySystem,
    Bitrix\Sale;

CModule::IncludeModule("sale");
Bitrix\Main\Loader::includeModule("catalog");

global $USER;
    try {
        $request = Context::getCurrent()->getRequest();
        $phone = $request["PHONE"];
        $name = $request["NAME"];
        $email = $request["EMAIL"];
        $comment = $request["COMMENT"];
        $deliveryid = $request["DELIVERY"];
        $paymentid = $request["PAYMENT"];

        $siteId = Context::getCurrent()->getSite();
        $currencyCode = CurrencyManager::getBaseCurrency();

        $order = Order::create($siteId, $USER->isAuthorized() ? $USER->GetID() : 539);
        $order->setPersonTypeId(1);
        $order->setField('CURRENCY', $currencyCode);
        if ($comment) {
            $order->setField('USER_DESCRIPTION', $comment); // Устанавливаем поля комментария покупателя
        }

        $basket = Basket::loadItemsForFUser(Sale\Fuser::getId(), Context::getCurrent()->getSite());
        $order->setBasket($basket);

        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $service = Delivery\Services\Manager::getById($deliveryid);
        $shipment->setFields(array(
            'DELIVERY_ID' => $service['ID'],
            'DELIVERY_NAME' => $service['NAME'],
        ));

        $paymentCollection = $order->getPaymentCollection();
        $payment = $paymentCollection->createItem();
        $paySystemService = PaySystem\Manager::getObjectById(intval($paymentid));
        $payment->setFields(array(
            'PAY_SYSTEM_ID' => $paySystemService->getField("PAY_SYSTEM_ID"),
            'PAY_SYSTEM_NAME' => $paySystemService->getField("NAME"),
        ));

        $propertyCollection = $order->getPropertyCollection();
        $phoneProp = $propertyCollection->getPhone();
        $phoneProp->setValue($phone);
        $nameProp = $propertyCollection->getPayerName();
        $nameProp->setValue($name);
        $nameProp = $propertyCollection->getUserEmail();
        $nameProp->setValue($email);

        $order->doFinalAction(true);
        $result = $order->save();
        $orderId = $order->getId();

        $url = CSalePaySystemAction::GetParamValue('URL');

        echo json_encode(['success' => true, 'errors' => [], 'data' =>['q'=>$paymentid, 'url'=>$url]]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'errors' => [$e->getMessage()], 'data' => []]);
    }


// Файл ниже подключать обязательно, там закрытие соединения с базой данных
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';