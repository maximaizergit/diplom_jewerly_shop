<?php
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"] . "/log.txt");
require($_SERVER["DOCUMENT_ROOT"] . '/globals.php');

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Catalog\ProductTable;
use Shiglov\IBlock\HighloadBlockInterface;

CModule::IncludeModule("shiglov.iblockinterface");

require 'curl.class.php';

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php")) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");
}

Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderSaved',
    'myFunctionName'
);

function myFunctionName(Main\Event $event)
{
    $order = $event->getParameter("ENTITY");

    $propertyCollection = $order->getPropertyCollection();
    $propsData = [];

    $locationItem = null;
    $addressItem = null;
    foreach ($propertyCollection as $propertyItem) {
        if ($propertyItem->getField("CODE") == 'LOCATION') {
            $locationItem = $propertyItem;

        } else if ($propertyItem->getField("CODE") == 'ADDRESS') {
            $addressItem = $propertyItem;
        }
    }
    if ($addressItem && $locationItem) {
        $address = $addressItem->getValue();

        // Отправляем GET-запрос на API Яндекс.Карт для получения координат адреса
        $url = 'https://geocode-maps.yandex.ru/1.x/?apikey=e8a8cc1b-7d22-4c5e-b0fd-bb7f7cb48965&format=json&geocode=' . urlencode($address);
        $response = file_get_contents($url);
        $json = json_decode($response, true);

        // Получаем координаты из ответа API
        $coordinates = $json['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];

        // Разбиваем строку с координатами на массив
        $coordinates = explode(' ', $coordinates);

        // Получаем широту и долготу
        $latitude = $coordinates[1];
        $longitude = $coordinates[0];

        $locationItem->setValue($latitude . ' ' . $longitude);

        $locationItem->save();
        AddMessage2Log($propsData, "", false);
        echo '<h1> ' . $_SERVER["DOCUMENT_ROOT"] . "/log.txt" . '</h1>';
    }
}

function checkItemsQuantity()
{

    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

    Loader::includeModule('catalog');

    $result = ProductTable::getList([
        'select' => ['ID', 'CODE' => 'IBLOCK_ELEMENT.CODE']
    ]);

    $resAr = [];

    while ($product = $result->fetch()) {

        if ($product['QUANTITY'] == 0) {
            $resAr[] = $product;
        }
    }

    AddMessage2Log('Закночились товары: 
    ' . json_encode($resAr), "", false);
    return 'checkItemsQuantity();';
}

function updatePrices()
{
    AddMessage2Log('test', "", false);
    return 'updatePrices();';
}

function sendNotifications()
{
    $elements = HighloadBlockInterface::getElements(5);
    $itemEmailMap = [];

// Перебор всех элементов
    foreach ($elements as $element) {
        $email = $element["UF_EMAIL"];
        $itemIds = $element["UF_ITEM_ID"];

        // Перебор всех товаров, выбранных пользователем
        foreach ($itemIds as $itemId) {
            // Если товар уже есть в массиве, добавляем почту к списку почт
            if (isset($itemEmailMap[$itemId])) {
                $itemEmailMap[$itemId][] = $email;
            } else {
                // Если товара еще нет в массиве, создаем новый элемент
                $itemEmailMap[$itemId] = [$email];
            }
        }
    }

    $inStockArray = [];
    $res = [];
    foreach ($itemEmailMap as $key => $value) {
        $data = \Shiglov\IBlock\IBlockInterface::getElementPrice($key);
        if($data['CATALOG_QUANTITY']>0){
         $inStockArray[$key]=$value;
        }

    }

    $mailsCounter = 0;
    $subject = "Обновление товара!";
    $headers = "From: sender@example.com";
    foreach ($inStockArray as $itemID=>$emails){
        foreach ($emails as $email){
            $message = "Товар с ID:".$itemID." теперь снова в наличии, успейте оформить заказ!";
            $mailSent = mail($email, $subject, $message, $headers);
            $mailsCounter++;
            if ($mailSent) {
                echo "Письмо успешно отправлено";
            } else {
                echo "Ошибка при отправке письма";
            }
        }

    }
// Отправка электронной почты

    //return $inStockArray;
    AddMessage2Log('Отправлено сообщение '.$mailsCounter." пользователям", "", false);
    return 'updatePrices();';
}

function PR($o, $show = false, $die = false, $user_id = [1, 1912, 1580, 242])
{
    global $USER, $APPLICATION;

    if (isset($_REQUEST['DEBUG-Y']) and $_REQUEST['DEBUG-Y'] == 'Y') {
        $show = true;
    }

    if ($die) {
        $APPLICATION->RestartBuffer();
    }

    if ((is_object($USER) and $USER->isAdmin() and in_array($USER->GetID(), $user_id)) || $show) {


        $bt = debug_backtrace();
        $bt = $bt[0];
        $dRoot = $_SERVER["DOCUMENT_ROOT"];
        $dRoot = str_replace("/", "\\", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        $dRoot = str_replace("\\", "/", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        ?>
        <div style='font-size: 12px;font-family: monospace;width: 100%;color: #181819;background: #EDEEF8;border: 1px solid #006AC5;'>
            <div style='padding: 5px 10px;font-size: 10px;font-family: monospace;background: #006AC5;font-weight:bold;color: #fff;'>
                File: <?= $bt["file"] ?> [<?= $bt["line"] ?>]
            </div>
            <pre style='padding:10px;text-align: left'><? print_r($o) ?></pre>
        </div>
        <?
    } else {
        return false;
    }
    if ($die) {
        die();
    }
}