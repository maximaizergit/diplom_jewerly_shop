<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var bool $itemHasDetailUrl
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var string $discountPositionClass
 * @var string $labelPositionClass
 * @var CatalogSectionComponent $component
 */

$optimalPrice = CCatalogProduct::getOptimalPrice(
    $item['ID'], 1, array(), 'N', array(), SITE_ID);
if ($optimalPrice) {
    // Сохраняем данные об оптимальной цене в массив
    $arPrices[$optimalPrice['PRICE']['CATALOG_GROUP_ID'] - 1] = array(
        'PRICE' => $optimalPrice['RESULT_PRICE']['BASE_PRICE'],
        'CURRENCY' => $optimalPrice['RESULT_PRICE']['CURRENCY']
    );
}
$item['ITEM_PRICES'] = $arPrices;
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
                    $conversionResult = round(CCurrencyRates::ConvertCurrency($item['ITEM_PRICES'][0]['PRICE'], 'BYN', $_SESSION['CURRENCY_ID']), 2); ?>
                    <h2><?= $conversionResult ?><?= $_SESSION['CURRENCY_ID'] ?></h2>
                    <?
                } else { ?>
                    <h2><?= $item['ITEM_PRICES'][0]['PRICE'] ?><?= $item['ITEM_PRICES'][0]['CURRENCY'] ?></h2>
                    <?
                }
                ?>

                <p><?= $productTitle ?></p>

                <?php
                echo '<a href="' . $item['DETAIL_PAGE_URL'] . '" class="btn btn-default add-to-cart"><i class="fa fa-eye"></i>'.GetMessage('WATCH').'</a>';

                if ($USER->IsAuthorized()) {
                    if (in_array($item['ID'], $arParams['ITEMS_IN_CART'])) {
                        echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-check"></i>'.GetMessage('IN_CART').'</a>';
                    } else {
                        echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>'.GetMessage('ADD_TO_CART').'</a>';

                    }

                } else {
                    echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>'.GetMessage('ADD_TO_CART').'у</a>';

                }

                ?>
                <a class="btn btn-default add-to-cart" href="?action=ADD_TO_COMPARE_LIST&id=<?=$item['ID']?>"> <?=GetMessage('COMPARE')?></a>
            </div>
            <div class="product-overlay">
                <div class="overlay-content">
                    <?
                    if ($_SESSION['CURRENCY_ID']) {
                        $conversionResult = round(CCurrencyRates::ConvertCurrency($item['ITEM_PRICES'][0]['PRICE'], 'BYN', $_SESSION['CURRENCY_ID']), 2);
                        ?>

                        <h2><?= $conversionResult ?><?= $_SESSION['CURRENCY_ID'] ?></h2>

                        <?
                    } else {
                        ?>
                        <h2><?= $item['ITEM_PRICES'][0]['PRICE'] ?><?= $item['ITEM_PRICES'][0]['CURRENCY'] ?></h2>

                        <?
                    }
                    ?>
                    <p><?= $productTitle ?></p>
                    <?php
                    echo '<a href="' . $item['DETAIL_PAGE_URL'] . '" class="btn btn-default add-to-cart"><i class="fa fa-eye"></i>'.GetMessage('WATCH').'</a>';
                    if ($USER->IsAuthorized()) {
                        if (in_array($item['ID'], $arParams['ITEMS_IN_CART'])) {
                            echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-check"></i>'.GetMessage('IN_CART').'</a>';
                        } else {
                            echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>'.GetMessage('ADD_TO_CART').'</a>';
                        }
                    } else {
                        echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $item['ID'] . '"><i class="fa fa-shopping-cart"></i>'.GetMessage('ADD_TO_CART').'</a>';

                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
