<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use Bitrix\Main\Context;

CModule::IncludeModule("sale");

if ($USER->IsAuthorized()) {
    $userID = $USER->GetID();

    $basketItems = \Bitrix\Sale\Basket::getList([
        'filter' => [
            'FUSER_ID' => \CSaleBasket::GetBasketUserID(),
            'LID' => 's1',
            'ORDER_ID' => null,
        ],
        'select' => ['PRODUCT_ID'],
    ]);

    $productIDs = [];
    while ($basketItem = $basketItems->fetch()) {
        $productIDs[] = $basketItem['PRODUCT_ID'];
    }


}
?>

    <div class="col-sm-9 padding-right">

        <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
            <?= $arResult["NAV_STRING"] ?><br/>
        <? endif;
        ?>
        <section id="items-section">

            <?php

            $arPrices = [];


            foreach ($arResult['ITEMS'] as $item) {
                $productID = $item['ID']; // Получение ID элемента инфоблока
                CModule::IncludeModule('catalog');
                $price = CPrice::GetBasePrice($productID);
                $arPrices[$item['ID']] = array($price["PRICE"], $price['CURRENCY']);

                $detailPageUrl = '';

                if (CModule::IncludeModule('iblock')) {
                    $arSelect = array("DETAIL_PAGE_URL");
                    $arFilter = array("ID" => $productID);

                    $res = CIBlockElement::GetList(
                        array(),
                        $arFilter,
                        false,
                        false,
                        $arSelect
                    );

                    if ($ob = $res->GetNextElement()) {
                        $arFields = $ob->GetFields();
                        $detailPageUrl = $arFields["DETAIL_PAGE_URL"];
                    }
                }
                ?>
                <div class="col-sm-4">
                    <div class="product-image-wrapper">
                        <div class="single-products">
                            <div class="productinfo text-center">
                                <? if (!empty($item['PREVIEW_PICTURE']['SRC']) && $item['PREVIEW_PICTURE']['SRC'] != ';') { ?>
                                    <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" alt=""/>
                                    <?
                                } else { ?>

                                    <img src="<?= SITE_TEMPLATE_PATH ?>/Assets2/images/404/no-image.jpg" alt=""/>
                                    <?
                                }
                                if ($_SESSION['CURRENCY_ID']) {
                                    $conversionResult = round(CCurrencyRates::ConvertCurrency($price['PRICE'], 'BYN', $_SESSION['CURRENCY_ID']), 2);
                                    ?>

                                    <h2 data-value="<?=$item['ID']?>"></h2>

                                    <?
                                } else {
                                    ?>
                                    <h2 data-value="<?=$item['ID']?>"></h2>

                                    <?
                                }
                                ?>


                                <p><?=$item['NAME']?></p>

                                <?php
                                echo '<a href="'.$detailPageUrl.'" class="btn btn-default add-to-cart"><i class="fa fa-eye"></i>'.GetMessage('WATCH').'</a>';

                                if ($USER->IsAuthorized()) {
                                    if (in_array($item['ID'], $productIDs)) {
                                        echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-check"></i>'.GetMessage('IN_CART').'</a>';
                                    } else {
                                        echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>'.GetMessage('ADD_TO_CART').'</a>';
                                    }
                                } else {
                                    echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>'.GetMessage('ADD_TO_CART').'</a>';

                                }

                                ?>

                            </div>
                            <div class="product-overlay">
                                <div class="overlay-content">
                                    <?
                                    if ($_SESSION['CURRENCY_ID']) {
                                        $conversionResult = round(CCurrencyRates::ConvertCurrency($price['PRICE'], 'BYN', $_SESSION['CURRENCY_ID']), 2);
                                        ?>

                                        <h2 data-value="<?=$item['ID']?>">></h2>

                                        <?
                                    } else {
                                        ?>
                                        <h2 data-value="<?=$item['ID']?>"></h2>

                                        <?
                                    }
                                    ?>
                                    <p><?= $item['NAME'] ?></p>
                                    <?php
                                    echo '<a href="'.$detailPageUrl.'" class="btn btn-default add-to-cart"><i class="fa fa-eye"></i>'.GetMessage('WATCH').'</a>';
                                    if ($USER->IsAuthorized()) {
                                        if (in_array($item['ID'], $productIDs)) {
                                            echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-check"></i>'.GetMessage('IN_CART').'</a>';
                                        } else {
                                            echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>'.GetMessage('ADD_TO_CART').'</a>';
                                        }
                                    } else {
                                        echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>'.GetMessage('ADD_TO_CART').'</a>';

                                    }

                                    ?></div>
                            </div>
                        </div>

                    </div>
                </div>
            <? }
        ?>
        </section>
    </div>
    <div class="col-sm-3">
    </div>
<?php if ($arParams['LAZY_OR_PAGE'] === 'PAGINATION') {
    ?>
    <div class="col-sm-9">
        <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <br/><?= $arResult["NAV_STRING"] ?>
        <? endif; ?>
    </div>
    <?php
} else {
    ?>
    <div class="col-sm-9 padding-right">
    <div class="loader-more-wrapper" id="load-more">
        <p ><?=GetMessage('LOAD_MORE')?></p>
    </div>
    </div>
    <?php
}
?>

    <script>

        var navNum = <?=$arResult['NAV_RESULT']->NavNum?>;
        var itemCount=<?=$arResult['NAV_RESULT']->NavRecordCount?>;
        var totalPages=<?=$arResult['NAV_RESULT']->NavPageCount?>;

        <?
        $siteUrl = (\CMain::IsHTTPS() ? "https://" : "http://") . \Bitrix\Main\Context::getCurrent()->getServer()->getHttpHost();
        ?>
        $.ajax({
            url: '/local/ajax/updatePriceData.php',
            type: 'GET',
            data: { arPrices: <?=CUtil::PhpToJSObject($arPrices)?> },
            success: function(response) {
                var currencyData = JSON.parse(response).data[0];
                for (var key in currencyData) {
                    var value = currencyData[key];
                    let id = key;
                    let price = value[0];


                    let currency = value[1];
                    let elements = document.querySelectorAll(`h2[data-value="${id}"]`);

                    elements.forEach(element => {
                        if (price===0){
                            element.textContent = 'Цена не указана';
                        }else{
                            element.textContent = price+' '+currency;

                        }

                    });
                }
                console.log(currencyData);
            },
            error: function(err) {
                console.log(err)
            }
        });

        // JavaScript код с использованием jQuery
        $('#load-more').on('click', function() {
            // Отправка AJAX запроса на текущую страницу
            $.ajax({
                url: '<?=$siteUrl?>/catalog/',
                type: 'GET',
                data: { load_more:true, curPage:navNum+1, teeest:'test', sort:"<?=$arParams['SORT_BY1']?>"  },
                success: function(data) {

                    // var items = JSON.parse(data);
                    $("#items-section").append(data);
                    navNum++;
                    if(totalPages == navNum){
                        $("#load-more").hide();
                    }

                },
                error: function(err) {
                    console.log('Ошибка при загрузке дополнительных товаров');
                    console.log(err)
                }
            });
        });


        function setPrices(prices){
            function updatePrices(prices) {
                for (let id in prices) {
                    let price = prices[id];
                    let elements = document.querySelectorAll(`[data-value="${id}"]`);
                    elements.forEach(element => {
                        element.textContent = price;
                    });
                }
            }
        }
    </script>

<?php
