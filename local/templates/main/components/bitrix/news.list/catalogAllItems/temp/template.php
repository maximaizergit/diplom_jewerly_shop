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
            'LID' => SITE_ID,
            'ORDER_ID' => null,
        ],
        'select' => ['PRODUCT_ID'],
    ]);

    $productIDs = [];
    while ($basketItem = $basketItems->fetch()) {
        $productIDs[] = $basketItem['PRODUCT_ID'];
    }


} else {

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
                        $arPrices[$item['ID']] = $price;
//                    echo '<h1>test 2</h1>';
//                    print_r($price);
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

                                            <h2 data-value="<?=$item['ID']?>"><?= $conversionResult ?><?= $_SESSION['CURRENCY_ID'] ?></h2>

                                            <?
                                        } else {
                                            ?>
                                            <h2 data-value="<?=$item['ID']?>"><?= $price['PRICE'] ?>BYN</h2>

                                            <?
                                        }
                                        ?>


                                        <p><?=$item['NAME']?></p>

                                        <?php
                                        echo '<a href="'.$detailPageUrl.'" class="btn btn-default add-to-cart"><i class="fa fa-eye"></i>Смотреть</a>';

                                        if ($USER->IsAuthorized()) {
                                            if (in_array($item['ID'], $productIDs)) {
                                                echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-check"></i>В корзине!</a>';
                                            } else {
                                                echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>В корзину</a>';
                                            }
                                        } else {
                                            echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>В корзину</a>';

                                        }

                                        ?>

                                    </div>
                                    <div class="product-overlay">
                                        <div class="overlay-content">
                                            <?
                                            if ($_SESSION['CURRENCY_ID']) {
                                                $conversionResult = round(CCurrencyRates::ConvertCurrency($price['PRICE'], 'BYN', $_SESSION['CURRENCY_ID']), 2);
                                                ?>

                                                <h2 data-value="<?=$item['ID']?>"><?= $conversionResult ?><?= $_SESSION['CURRENCY_ID'] ?></h2>

                                                <?
                                            } else {
                                                ?>
                                                <h2 data-value="<?=$item['ID']?>"><?= $price['PRICE'] ?>BYN</h2>

                                                <?
                                            }
                                            ?>
                                            <p><?= $item['NAME'] ?></p>
                                            <?php
                                            echo '<a href="'.$detailPageUrl.'" class="btn btn-default add-to-cart"><i class="fa fa-eye"></i>Смотреть</a>';
                                            if ($USER->IsAuthorized()) {
                                                if (in_array($item['ID'], $productIDs)) {
                                                    echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-check"></i>В корзине!</a>';
                                                } else {
                                                    echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>В корзину</a>';
                                                }
                                            } else {
                                                echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>В корзину</a>';

                                            }

                                            ?></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <? } ?>

    </section>


    </div>

    <div class="col-sm-3">

    </div>

    <?php if($arParams['LAZY_OR_PAGE'] === 'PAGINATION'){
     ?>
    <div class="col-sm-9">
        <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <br/><?= $arResult["NAV_STRING"] ?>
        <? endif; ?>
    </div>
        <?php
    }else{
        ?>
    <div class="col-sm-9 padding-right">
        <div class="loader-more-wrapper" id="load-more">
            <p >Загрузить ещё</p>
        </div>
    </div>
        <?php
}

?>




<script>

    var navNum = <?=$arResult['NAV_RESULT']->NavNum?>;
    var itemCount=<?=$arResult['NAV_RESULT']->NavRecordCount?>;
    var totalPages=<?=$arResult['NAV_RESULT']->NavPageCount?>;
    if(totalPages == navNum){
        $("#load-more").hide();
    }

    $.ajax({
        url: '<?=$siteUrl?>/catalog/',
        type: 'GET',
        data: { load_more:true, curPage:1, teeest:'test', sort:"<?=$arParams['SORT_BY1']?>"  },
        success: function(data) {

            $("#items-section").append(data);

            if(totalPages == navNum){
                $("#load-more").hide();
            }

        },
        error: function(err) {
            console.log('Ошибка при загрузке дополнительных товаров');
            console.log(err)
        }
    });

    <?


    $siteUrl = (\CMain::IsHTTPS() ? "https://" : "http://") . \Bitrix\Main\Context::getCurrent()->getServer()->getHttpHost();


    ?>
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
                // var newItems = JSON.parse(data);
                // Добавление новых товаров в контейнер
                // newItems.forEach(function(item) {
                    // Добавление товара в контейнер, например:
                    // var itemHtml = "<div class="item">' + item.name + '</div>';
                    // $('#items-container').append(itemHtml);
                // });
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



//print_r($arParams['LAZY_OR_PAGE']);