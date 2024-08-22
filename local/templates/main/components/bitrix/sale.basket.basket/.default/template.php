<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale;
use Bitrix\Sale\Basket;
use Bitrix\Main\Context;

\Bitrix\Main\UI\Extension::load(["ui.fonts.ruble", "ui.fonts.opensans"]);
IncludeTemplateLangFile(__FILE__);
/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */

$documentRoot = Main\Application::getDocumentRoot();

if (empty($arParams['TEMPLATE_THEME'])) {
    $arParams['TEMPLATE_THEME'] = Main\ModuleManager::isModuleInstalled('bitrix.eshop') ? 'site' : 'blue';
}

if ($arParams['TEMPLATE_THEME'] === 'site') {
    $templateId = Main\Config\Option::get('main', 'wizard_template_id', 'eshop_bootstrap', $component->getSiteId());
    $templateId = preg_match('/^eshop_adapt/', $templateId) ? 'eshop_adapt' : $templateId;
    $arParams['TEMPLATE_THEME'] = Main\Config\Option::get('main', 'wizard_' . $templateId . '_theme_id', 'blue', $component->getSiteId());
}

if (!empty($arParams['TEMPLATE_THEME'])) {
    if (!is_file($documentRoot . '/bitrix/css/main/themes/' . $arParams['TEMPLATE_THEME'] . '/style.css')) {
        $arParams['TEMPLATE_THEME'] = 'blue';
    }
}

if (!isset($arParams['DISPLAY_MODE']) || !in_array($arParams['DISPLAY_MODE'], array('extended', 'compact'))) {
    $arParams['DISPLAY_MODE'] = 'extended';
}

$arParams['USE_DYNAMIC_SCROLL'] = isset($arParams['USE_DYNAMIC_SCROLL']) && $arParams['USE_DYNAMIC_SCROLL'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_FILTER'] = isset($arParams['SHOW_FILTER']) && $arParams['SHOW_FILTER'] === 'N' ? 'N' : 'Y';

$arParams['PRICE_DISPLAY_MODE'] = isset($arParams['PRICE_DISPLAY_MODE']) && $arParams['PRICE_DISPLAY_MODE'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['TOTAL_BLOCK_DISPLAY']) || !is_array($arParams['TOTAL_BLOCK_DISPLAY'])) {
    $arParams['TOTAL_BLOCK_DISPLAY'] = array('top');
}

if (empty($arParams['PRODUCT_BLOCKS_ORDER'])) {
    $arParams['PRODUCT_BLOCKS_ORDER'] = 'props,sku,columns';
}

if (is_string($arParams['PRODUCT_BLOCKS_ORDER'])) {
    $arParams['PRODUCT_BLOCKS_ORDER'] = explode(',', $arParams['PRODUCT_BLOCKS_ORDER']);
}

$arParams['USE_PRICE_ANIMATION'] = isset($arParams['USE_PRICE_ANIMATION']) && $arParams['USE_PRICE_ANIMATION'] === 'N' ? 'N' : 'Y';
$arParams['EMPTY_BASKET_HINT_PATH'] = isset($arParams['EMPTY_BASKET_HINT_PATH']) ? (string)$arParams['EMPTY_BASKET_HINT_PATH'] : '/';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

\CJSCore::Init(array('fx', 'popup', 'ajax'));
Main\UI\Extension::load(['ui.mustache']);


$this->addExternalJs($templateFolder . '/js/action-pool.js');
$this->addExternalJs($templateFolder . '/js/filter.js');
$this->addExternalJs($templateFolder . '/js/component.js');

$mobileColumns = isset($arParams['COLUMNS_LIST_MOBILE'])
    ? $arParams['COLUMNS_LIST_MOBILE']
    : $arParams['COLUMNS_LIST'];
$mobileColumns = array_fill_keys($mobileColumns, true);

$jsTemplates = new Main\IO\Directory($documentRoot . $templateFolder . '/js-templates');
/** @var Main\IO\File $jsTemplate */
foreach ($jsTemplates->getChildren() as $jsTemplate) {
    include($jsTemplate->getPath());
}

$displayModeClass = $arParams['DISPLAY_MODE'] === 'compact' ? ' basket-items-list-wrapper-compact' : '';

$site = Bitrix\Main\Context::getCurrent()->getSite();

if (empty($arResult['ERROR_MESSAGE'])) {
    $basket = Bitrix\Sale\Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), 's1');
    ?>


    <div id="basket-root" class="bx-basket bx-<?= $arParams['TEMPLATE_THEME'] ?> bx-step-opacity" style="opacity: 0;">
        <div class="cart">
            <div class='container'>
                <div class="cart-tabs">
                    <a class="cart-tabs__item active"><?=GetMessage("BASKET")?></a>
                </div>
                <div class="cart-content">
                    <div class="cart-body">
                        <div class="cart-items">
                            <?
                            foreach ($basket as $item) {
                                $basketPropertyCollection = $item->getPropertyCollection()->getPropertyValues();

                                $data = $item->getFields();
                                $element = CIBlockElement::GetByID($data['PRODUCT_ID'])->GetNextElement();
                                if ($element) {
                                    $fields = $element->GetFields();
                                    $properties = $element->GetProperties();

                                    $stone = $properties['STONE']['VALUE'];
                                    $model = $properties['MODEL']['VALUE'];
                                    $metal = $properties['METAL']['VALUE'];
                                    $imageSrc = CFile::GetPath($fields['PREVIEW_PICTURE']);

                                }
                                ?>
                                <div class="cart-item" id="item<?= $data['ID'] ?>">
                                    <div class="cart-item-product">
                                        <a href="" class="cart-item-product__image"><img src="<?= $imageSrc ?>" alt=""/></a>
                                        <div class="cart-item-product-body">
                                            <a class="cart-item-product__title"><?= $data['NAME'] ?></a>
                                            <div class="cart-item-product__label"><?= $model ?></div>
                                        </div>
                                    </div>
                                    <? if ($_SESSION['CURRENCY_ID']) {
                                        $conversionResult = round(CCurrencyRates::ConvertCurrency($data['PRICE'], 'BYN', $_SESSION['CURRENCY_ID']), 2);
                                        ?>
                                        <div class="cart-item__price"><?= $conversionResult ?> <?= $_SESSION['CURRENCY_ID'] ?></div>

                                        <?
                                    } else {
                                        ?>
                                        <div class="cart-item__price"><?= $data['PRICE'] ?>BYN</div>
                                        <?
                                    }
                                    ?>

                                    <div style="font-size: 30px; display: flex; flex-direction: column; min-width: 60px; align-items: center; justify-content: center">
                                        <div style="padding-right: 10px" id="increaseQuantity"
                                             data-entity="<?= $data['PRODUCT_ID'] ?>">+
                                        </div>
                                        <div style="align-items: center; justify-content: center; display: flex"><input
                                                    id="valueInput" data-entity="<?= $data['PRODUCT_ID'] ?>"
                                                    type="number" value="<?= round($data['QUANTITY']) ?>" min="1"
                                                    style="width: 80%; text-align: center; font-size: 20px;"></div>
                                        <div style="padding-right: 10px" id="decreaseQuantity"
                                             data-entity="<?= $data['PRODUCT_ID'] ?>">-
                                        </div>
                                    </div>
                                    <a class="cart-item__delete  fa fa-times" id="deleteBtn"
                                       data-entity="<?= $data['ID'] ?>"></a>
                                </div>
                            <? } ?>

                        </div>
                    </div>
                    <div class="cart-info">
                        <div class="cart-info-table table">
                            <div class="trow">
                                <div class="cell">
                                    <div class="cart-info__label" id="test"><?=GetMessage("ITEMS")?></div>
                                </div>
                                <div class="cell">
                                    <div class="cart-info__value" id="itemsCount">Загрузка...</div>
                                </div>
                            </div>
                            <div class="trow">
                                <div class="cell">
                                    <div class="cart-info__label"><?=GetMessage("SUMM")?></div>
                                </div>
                                <div class="cell">
                                    <div class="cart-info__value" id="itemsSum">Загрузка...</div>
                                </div>
                            </div>

                        </div>
                        <div class="cart-info-total">
                            <div class="cart-info-total__label"><?=GetMessage("TOTAL")?></div>
                            <div class="cart-info-total__value" id="itemsTotalSum">Загрузка...</div>
                        </div>
                    </div>
                </div>
                <div class="cart-actions">
                    <div class="cart-actions-column">


                    </div>
                    <div class="cart-actions-column">
                        <a href="/checkout/" class="cart-actions__btn btn-3 min"><?=GetMessage("TO_ORDER")?></a>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-12">
                <div class="basket-items-list-wrapper basket-items-list-wrapper-height-fixed basket-items-list-wrapper-light<?= $displayModeClass ?>"
                     id="basket-items-list-wrapper">

                    <div class="basket-items-list-container" id="basket-items-list-container">
                        <div class="basket-items-list-overlay" id="basket-items-list-overlay"
                             style="display: none;"></div>
                        <div class="basket-items-list" id="basket-item-list">
                            <div class="basket-search-not-found" id="basket-item-list-empty-result"
                                 style="display: none;">
                                <div class="basket-search-not-found-icon"></div>
                                <div class="basket-search-not-found-text">
                                    <?= Loc::getMessage('SBB_FILTER_EMPTY_RESULT') ?>
                                </div>
                            </div>
                            <table class="basket-items-list-table" id="basket-item-table"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?
    if (!empty($arResult['CURRENCIES']) && Main\Loader::includeModule('currency')) {
        CJSCore::Init('currency');

        ?>
        <script>
            BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
            let currency = '<?=$_SESSION['CURRENCY_ID'] ?>';
            updateCartData()


            // получаем все элементы с определенным id
            var deleteBtns = document.querySelectorAll('[id="deleteBtn"]');
            // проходимся по каждому элементу и прикрепляем обработчик события
            deleteBtns.forEach(function (element) {
                BX.bind(element, 'click', function () {
                    var pid = element.getAttribute('data-entity');
                    console.log(pid);
                    $.ajax({
                        type: "POST",
                        url: "/local/ajax/deleteFromCart.php",
                        data: {
                            pid: pid,
                        },
                        success: function (response) {

                            console.log(JSON.parse(response));
                            itemBlock = document.getElementById('item' + pid);
                            itemBlock.style = "display: none";
                            updateCartData();
                        }
                    });
                });
            });

            var increaseBtns = document.querySelectorAll('[id="increaseQuantity"]');

            // проходимся по каждому элементу и прикрепляем обработчик события
            increaseBtns.forEach(function (element) {
                BX.bind(element, 'click', function () {
                    var pid = element.getAttribute('data-entity');
                    var action = 'increment';
                    console.log(pid);
                    $.ajax({
                        type: "POST",
                        url: "/local/ajax/changeItemQuantity.php",
                        data: {
                            pid: pid,
                            action: action,
                        },
                        success: function (response) {
                            console.log(JSON.parse(response));
                            updateCartData();
                        }
                    });
                    var localInput = Array.from(valueInputs).find(btn => btn.getAttribute("data-entity") === pid);
                    localInput.value = parseInt(localInput.value) + 1;
                });
            });


            var decreaseBtns = document.querySelectorAll('[id="decreaseQuantity"]');

            // проходимся по каждому элементу и прикрепляем обработчик события
            decreaseBtns.forEach(function (element) {
                BX.bind(element, 'click', function () {
                    var pid = element.getAttribute('data-entity');
                    var action = 'decrement';
                    console.log(pid);
                    $.ajax({
                        type: "POST",
                        url: "/local/ajax/changeItemQuantity.php",
                        data: {
                            pid: pid,
                            action: action,
                        },
                        success: function (response) {
                            console.log(JSON.parse(response));
                            updateCartData();

                        }
                    });
                    var localInput = Array.from(valueInputs).find(btn => btn.getAttribute("data-entity") === pid);
                    if (localInput.value > 1) {
                        localInput.value = localInput.value - 1;
                    }

                });
            });


            var valueInputs = document.querySelectorAll('[id="valueInput"]');

            // проходимся по каждому элементу и прикрепляем обработчик события
            valueInputs.forEach(function (element) {
                BX.bind(element, 'input', function () {
                    if (element.value != null && element.value != ' ' && element.value != 0) {
                        var pid = element.getAttribute('data-entity');
                        var action = 'setValue';
                        console.log(pid);
                        console.log(element.value);
                        console.log(action);
                        
                    }

                });
            });


            function updateCartData() {
                $.ajax({
                    type: "GET",
                    url: "/local/ajax/getCartData.php",
                    data: {
                        currency: currency,
                        site: '<?=$site?>'
                    },
                    success: function (response) {
                        console.log((response));
                        console.log(JSON.parse(response));
                        var res = JSON.parse(response);
                        var basketCount = res.data.result.BASKET_COUNT;
                        var basketSum = res.data.result.BASKET_SUM;

                        itemsCount = document.getElementById('itemsCount')
                        itemsCount.textContent = Math.round(basketCount);
                        itemsSum = document.getElementById('itemsSum')
                        itemsSum.textContent = Math.round(basketSum, 2) + currency;
                        itemsTotal = document.getElementById('itemsTotalSum')
                        console.log(itemsTotal);
                        itemsTotal.textContent = Math.round(basketSum, 2) + currency;
                        ;


                    }
                });
            }

            var test = document.getElementById('test');
            BX.bind(test, 'click', function () {
                console.log('test');
                updateCartData();


            })
        </script>
        <?
    }

    $signer = new \Bitrix\Main\Security\Sign\Signer;
    $signedTemplate = $signer->sign($templateName, 'sale.basket.basket');
    $signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.basket.basket');
    $messages = Loc::loadLanguageFile(__FILE__);
    ?>
    <script>
        BX.message(<?=CUtil::PhpToJSObject($messages)?>);
        BX.Sale.BasketComponent.init({
            result: <?=CUtil::PhpToJSObject($arResult, false, false, true)?>,
            params: <?=CUtil::PhpToJSObject($arParams)?>,
            template: '<?=CUtil::JSEscape($signedTemplate)?>',
            signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
            siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
            siteTemplateId: '<?=CUtil::JSEscape($component->getSiteTemplateId())?>',
            templateFolder: '<?=CUtil::JSEscape($templateFolder)?>'
        });
    </script>
    <?

} elseif ($arResult['EMPTY_BASKET']) {
    include(Main\Application::getDocumentRoot() . $templateFolder . '/empty.php');
} else {
    ShowError($arResult['ERROR_MESSAGE']);
}