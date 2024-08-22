<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Catalog\ProductTable;
use Bitrix\Sale;

Bitrix\Main\Loader::includeModule("catalog");
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);


$templateLibrary = array('popup', 'fx', 'ui.fonts.opensans');
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$haveOffers = !empty($arResult['OFFERS']);

$templateData = [
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'ITEM' => [
        'ID' => $arResult['ID'],
        'IBLOCK_ID' => $arResult['IBLOCK_ID'],
    ],
];
if ($haveOffers) {
    $templateData['ITEM']['OFFERS_SELECTED'] = $arResult['OFFERS_SELECTED'];
    $templateData['ITEM']['JS_OFFERS'] = $arResult['JS_OFFERS'];
}
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
    'ID' => $mainId,
    'DISCOUNT_PERCENT_ID' => $mainId . '_dsc_pict',
    'STICKER_ID' => $mainId . '_sticker',
    'BIG_SLIDER_ID' => $mainId . '_big_slider',
    'BIG_IMG_CONT_ID' => $mainId . '_bigimg_cont',
    'SLIDER_CONT_ID' => $mainId . '_slider_cont',
    'OLD_PRICE_ID' => $mainId . '_old_price',
    'PRICE_ID' => $mainId . '_price',
    'DESCRIPTION_ID' => $mainId . '_description',
    'DISCOUNT_PRICE_ID' => $mainId . '_price_discount',
    'PRICE_TOTAL' => $mainId . '_price_total',
    'SLIDER_CONT_OF_ID' => $mainId . '_slider_cont_',
    'QUANTITY_ID' => $mainId . '_quantity',
    'QUANTITY_DOWN_ID' => $mainId . '_quant_down',
    'QUANTITY_UP_ID' => $mainId . '_quant_up',
    'QUANTITY_MEASURE' => $mainId . '_quant_measure',
    'QUANTITY_LIMIT' => $mainId . '_quant_limit',
    'BUY_LINK' => $mainId . '_buy_link',
    'ADD_BASKET_LINK' => $mainId . '_add_basket_link',
    'BASKET_ACTIONS_ID' => $mainId . '_basket_actions',
    'NOT_AVAILABLE_MESS' => $mainId . '_not_avail',
    'COMPARE_LINK' => $mainId . '_compare_link',
    'TREE_ID' => $mainId . '_skudiv',
    'DISPLAY_PROP_DIV' => $mainId . '_sku_prop',
    'DISPLAY_MAIN_PROP_DIV' => $mainId . '_main_sku_prop',
    'OFFER_GROUP' => $mainId . '_set_group_',
    'BASKET_PROP_DIV' => $mainId . '_basket_prop',
    'SUBSCRIBE_LINK' => $mainId . '_subscribe',
    'TABS_ID' => $mainId . '_tabs',
    'TAB_CONTAINERS_ID' => $mainId . '_tab_containers',
    'SMALL_CARD_PANEL_ID' => $mainId . '_small_card_panel',
    'TABS_PANEL_ID' => $mainId . '_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
    : $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
    : $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
    : $arResult['NAME'];

if ($haveOffers) {
    $actualItem = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']] ?? reset($arResult['OFFERS']);
    $showSliderControls = false;

    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['MORE_PHOTO_COUNT'] > 1) {
            $showSliderControls = true;
            break;
        }
    }
} else {
    $actualItem = $arResult;
    $showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

if ($arParams['SHOW_SKU_DESCRIPTION'] === 'Y') {
    $skuDescription = false;
    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['DETAIL_TEXT'] != '' || $offer['PREVIEW_TEXT'] != '') {
            $skuDescription = true;
            break;
        }
    }
    $showDescription = $skuDescription || !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
} else {
    $showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
}

$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';

$productType = $arResult['PRODUCT']['TYPE'];

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');

if ($arResult['MODULES']['catalog'] && $arResult['PRODUCT']['TYPE'] === ProductTable::TYPE_SERVICE) {
    $arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE_SERVICE']
        ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE_SERVICE');
    $arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE_SERVICE']
        ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE_SERVICE');
} else {
    $arParams['~MESS_NOT_AVAILABLE'] = $arParams['~MESS_NOT_AVAILABLE']
        ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
    $arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE']
        ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
}

$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$positionClassMap = array(
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top'
);

$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());

$reviewsFilter = array(
    "IBLOCK_CODE" => "reviews",
    "PROPERTY_ITEM_ID" => $arResult['ID']
);

$reviewsSelect = array(
    "ID",
    "NAME",
    "PROPERTY_RATING",
    "DETAIL_TEXT",
    "DATE_CREATE"
);

$reviewsResult = CIBlockElement::GetList(
    array("DATE_CREATE" => "DESC"), // сортировка по убыванию даты создания
    $reviewsFilter,
    false,
    false,
    $reviewsSelect
);
$reviews = [];
while ($reviews[] = $reviewsResult->GetNext()) {
}
$avgRating = 5;

if ($reviews) {
    $avgRating = 0;
    foreach ($reviews as $review) {
        $avgRating += $review['PROPERTY_RATING_VALUE'];
    }
    $avgRating = round($avgRating / (count($reviews)));
}
?>
    <script>


    </script

    <div class="product-details"><!--product-details-->
        <div class="col-sm-5">
            <div class="view-product">
                <img src="<?= $arResult['PREVIEW_PICTURE']['SRC'] ?>" alt=""/>
            </div>
            <div id="similar-product" class="carousel slide" data-ride="carousel">

                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <a href=""><img src="<?= $arResult['PREVIEW_PICTURE']['SRC'] ?>" alt=""></a>
                        <a href=""><img src="<?= $arResult['PREVIEW_PICTURE']['SRC'] ?>" alt=""></a>
                        <a href=""><img src="<?= $arResult['PREVIEW_PICTURE']['SRC'] ?>" alt=""></a>
                    </div>
                </div>

                <!-- Controls -->
                <a class="left item-control" href="#similar-product" data-slide="prev">
                    <i class="fa fa-angle-left"></i>
                </a>
                <a class="right item-control" href="#similar-product" data-slide="next">
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>

        </div>
        <div class="col-sm-7">
            <div class="product-information"><!--/product-information-->
                <h2><?= $arResult['NAME'] ?></h2>
                <p>Web ID: <?= $arResult['ID'] ?></p>

                <?php
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $avgRating) {
                        echo '<i class="fa fa-star"></i>';
                    } else {
                        echo '<i class="fa fa-star-o"></i>';
                    }
                }
                ?>
                <span>

                    <? if ($_SESSION['CURRENCY_ID']) {
                        $conversionResult = round(CCurrencyRates::ConvertCurrency($arResult['ITEM_PRICES'][0]['PRICE'], 'BYN', $_SESSION['CURRENCY_ID']), 2);
                        ?>

                        <span><?= $conversionResult?><?=  $_SESSION['CURRENCY_ID'] ?></span>

                        <?
                    } else {
                        ?>
                        <span><?= $arResult['ITEM_PRICES'][0]['PRICE']?>BYN</span>

                        <?
                    }
                    ?>

                    <? if ($arResult['PRODUCT']['QUANTITY'] > 0) {
                        ?>
                        <a type="button" class="btn btn-default cart" id='cart'>
                            <i class="fa fa-shopping-cart"></i><?= GetMessage('ADD_TO_CART') ?>
                        </a>
                        <?
                    } else {

                        ?>
                        <a type="button" class="btn btn-default cart" id='preorder'>
                            <i class="fa fa-shopping-cart"></i>Уведомить при появлении
                        </a>
                        <?
                    }
                    ?>

                </span>
                <div class="preorder-form" id="preorder-form" style="display: none">
                    <label for="preorder-email">Email:</label>
                    <input type="email" value="<?=$USER->GetEmail()?>" placeholder="EMAIL" name="email" id="preorder-email">
                    <a type="button" class="btn btn-default" id='confirm-preorder'>
                       Подтвердить
                    </a>
                </div>
                <p>
                    <b>Availability:</b> <?= $arResult['PRODUCT']['QUANTITY'] > 0 ? GetMessage('IN_STOCK') : GetMessage('NOT_IN_STOCK') ?>
                </p>
                <p><b>Condition:</b> <?= $arResult['PROPERTIES']['STATUS']["VALUE"] ?></p>
                <p><b>Brand:</b> <?= $arResult['PROPERTIES']['BRAND']["VALUE"] ?></p>
            </div><!--/product-information-->
        </div>
    </div><!--/product-details-->

    <div class="category-tab shop-details-tab"><!--category-tab-->
        <div class="col-sm-12">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#reviews" data-toggle="tab"><?= GetMessage('REVIEWS') ?></a></li>
                <li><a href="#sendreview" data-toggle="tab"><?= GetMessage('SEND_REVIEW') ?></a></li>
            </ul>
        </div>
        <div class="tab-content">

            <div class="tab-pane fade" id="sendreview">
                <div class="col-sm-12">
                    <p><b>Write Your Review</b></p>
                    <?
                    if (!$USER->IsAuthorized()) {
                        echo "Требуется авторизация.";
                    } else {
                        ?>
                        <form id="review-form" action="#">
                            <span>
                                <input id="name-field" type="text" placeholder="Your Name"/>
                            </span>
                            <textarea id="comment-field" name=""></textarea>
                            <div class="rating-wrapper">
                                <b style="font-size: 23px">Rating: </b>
                                <div class="rating">
                                    <input type="radio" name="rating" id="star5" value="5" checked><label for="star5">&#9733;</label>
                                    <input type="radio" name="rating" id="star4" value="4"><label
                                            for="star4">&#9733;</label>
                                    <input type="radio" name="rating" id="star3" value="3"><label
                                            for="star3">&#9733;</label>
                                    <input type="radio" name="rating" id="star2" value="2"><label
                                            for="star2">&#9733;</label>
                                    <input type="radio" name="rating" id="star1" value="1"><label
                                            for="star1">&#9733;</label>
                                </div>
                                <button type="submit" class="btn btn-default pull-right" style="margin-left: 20px">
                                    Submit
                                </button>
                            </div>
                            <script>
                                const ratingInputs = document.querySelectorAll('input[name="rating"]');
                                let selectedRating = 5;

                                ratingInputs.forEach(input => {
                                    input.addEventListener('click', () => {
                                        selectedRating = input.value;
                                        console.log(`Selected rating: ${selectedRating}`);
                                    });
                                });
                            </script>

                        </form>

                        <?php
                    }
                    ?>

                </div>
            </div>
            <script>
                // Обработчик события submit на форме
                $('#review-form').on('submit', function (event) {
                    event.preventDefault(); // Отменяем стандартное поведение браузера
                    console.log('qwe');
                    var name = $('#name-field').val(); // Получаем значение поля имени
                    var comment = $('#comment-field').val(); // Получаем значение поля комментария
                    var itemId = '<?php echo $arResult["ID"]; ?>'; // Получаем значение переменной arResult['ID']

                    // Отправляем AJAX-запрос на сервер
                    $.ajax({
                        type: 'POST',
                        url: '/local/ajax/addReview.php',
                        data: {name: name, comment: comment, itemId: itemId, rating: selectedRating},
                        success: function (res) {
                            console.log(res); // Выводим ответ сервера в консоль
                        },
                        error: function () {
                            console.log('Ошибка отправки запроса'); // Выводим сообщение об ошибке в консоль
                        }
                    });
                });

                function add2cart(id) {
                    console.log('start');
                    $.ajax({
                        type: "POST",
                        url: "/local/ajax/add2cart.php",
                        data: {
                            id: <?=$arResult['ID']?>,
                            stone: "<?=$arResult['PROPERTIES']['STONE']['VALUE']?>",
                            src: "<?= $arResult['PREVIEW_PICTURE']['SRC']?>"
                        },
                        success: function (response) {

                            console.log(JSON.parse(response));
                        }
                    });
                }
            </script>


            <div class="tab-pane fade active in" id="reviews">
                <?

                foreach ($reviews as $review) {
                    if ($review) {
                        ?>

                        <div class="col-sm-12">
                            <ul>
                                <li><a href=""><i class="fa fa-user"></i><?= $review['NAME'] ?></a></li>
                                <li><a href=""><i class="fa fa-calendar-o"></i><?= $review['DATE_CREATE'] ?> </a></li>
                                <li> <?php
                                    $ratingValue = $review['PROPERTY_RATING_VALUE'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $ratingValue) {
                                            echo '<i class="fa fa-star"></i>';
                                        } else {
                                            echo '<i class="fa fa-star-o"></i>';
                                        }
                                    }
                                    ?></li>
                            </ul>
                            <p><?= $review['DETAIL_TEXT'] ?></p>
                        </div>

                        <?
                    }
                }

                ?>

            </div>

        </div>
    </div><!--/category-tab-->

    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
            TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
            TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
            BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
            BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
            BTN_MESSAGE_DETAIL_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
            BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
            BTN_MESSAGE_DETAIL_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
            TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
            COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
            COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
            COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
            PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
            PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
            RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
            RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
            SITE_ID: '<?=CUtil::JSEscape($component->getSiteId())?>'
        });

        BX.ready(function () {
            var cartBtn = document.getElementById('cart');
            BX.bind(cartBtn, 'click', function () {
                add2cart()
            });
            var preorder = document.getElementById('preorder');
            BX.bind(preorder, 'click', function () {
                showPreorder();
            });
            var confirmPreorderBtn = document.getElementById('confirm-preorder');
            BX.bind(confirmPreorderBtn, 'click', function () {
                confirmPreorder();
            });
        });

        function showPreorder(){
            let form = document.getElementById('preorder-form');
            form.style.display = 'block';
        }

        function confirmPreorder(){
            let email =  document.getElementById('preorder-email');
            if(validateEmail(email.value)){
                $.ajax({
                    type: "POST",
                    url: "/local/ajax/preorder.php",
                    data: {
                        id: <?=$arResult['ID']?>,
                        email: email.value,
                    },
                    success: function (response) {
                        alert('Мы вам сообщим как только товар появится в продаже!')
                    }
                });
            }
            else{
                alert('Неправильная почта!')
            }

        }

        function validateEmail(email) {
            const re = /\S+@\S+\.\S+/;
            return re.test(email);
        }



        var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    </script>
<?php
unset($actualItem, $itemIds, $jsParams);
CJSCore::Init(array('ajax'));




