<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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
use Bitrix\Main\Localization\Loc;
CModule::IncludeModule("sale");
IncludeTemplateLangFile(__FILE__);

$this->setFrameMode(true);

$sectionListParams = array(
    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
    "CACHE_TIME" => $arParams["CACHE_TIME"],
    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
    "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
    "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
    "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
    "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
    "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
    "HIDE_SECTION_NAME" => ($arParams["SECTIONS_HIDE_SECTION_NAME"] ?? "N"),
    "ADD_SECTIONS_CHAIN" => ($arParams["ADD_SECTIONS_CHAIN"] ?? '')
);


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


}

if(CSite::InDir('/en/') ) {
    $path = '/en';
    $iblockId = CATALOG_IBLOCK_ID;//_EN;
} else {
    $path = '';
    $iblockId = CATALOG_IBLOCK_ID;
}

?>
<div class="col-sm-3">
    <div class="left-sidebar">
        <h2>Category</h2>
        <div class="panel-group category-products" id="accordian"><!--category-productsr-->
            <div class="panel panel-default">
                <div class="panel-body">
                    <ul>
                        <?php
                        $APPLICATION->IncludeComponent(
                            "bitrix:catalog.section.list",
                            "",
                            $sectionListParams,
                            $component,
                            ($arParams["SHOW_TOP_ELEMENTS"] !== "N" ? array("HIDE_ICONS" => "Y") : array())
                        );
                        unset($sectionListParams);

                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <? $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "banner",
            array(
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "ADD_SECTIONS_CHAIN" => "Y",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "DISPLAY_DATE" => "N",
                "DISPLAY_NAME" => "N",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "DISPLAY_TOP_PAGER" => "N",
                "FIELD_CODE" => array("", "DETAIL_PICTURE", ""),
                "FILTER_NAME" => "",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                "IBLOCK_TYPE" => CATALOG_IBLOCK_TYPE,
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "INCLUDE_SUBSECTIONS" => "N",
                "MESSAGE_404" => "",
                "NEWS_COUNT" => "30",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => ".default",
                "PAGER_TITLE" => "Новости",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "PROPERTY_CODE" => array("", "PROPERTY_LINK"),
                "SET_BROWSER_TITLE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "Y",
                "SHOW_404" => "N",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_BY2" => "SORT",
                "SORT_ORDER1" => "DESC",
                "SORT_ORDER2" => "ASC",
                "STRICT_SECTION_CHECK" => "N"
            )
        ); ?>

    </div>
</div>
<p class="sort"><?=GetMessage("SORT")?>
    <a <? if ($_GET["sort"] == "name"): ?> class="active"
                                           <? endif; ?>href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=name&method=asc"><?=GetMessage("BYNAME")?></a>
    <a <? if ($_GET["sort"] == "catalog_PRICE_1"): ?> class="active"
                                                      <? endif; ?>href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=catalog_PRICE_1&method=asc"><?=GetMessage("BYPRICE")?></a>
    <a <? if ($_GET["sort"] == "property_PRODUCT_TYPE"): ?> class="active"
                                                            <? endif; ?>href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=property_PRODUCT_TYPE&method=desc"><?=GetMessage("BYSELLINGS")?></a>

</p>
<?php $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "catalogAllItems",
    array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "Y",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "CACHE_TIME" => "36000",
        "CACHE_TYPE" => "Y",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "/#SECTION_CODE#/#IBLOCK_CODE#",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array("ID", "CODE", "NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "DETAIL_TEXT", "DETAIL_PICTURE", ""),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => $iblockId,
        "IBLOCK_TYPE" => CATALOG_IBLOCK_TYPE,
        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
        "INCLUDE_SUBSECTIONS" => "Y",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "30",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "bootstrap_v4",
        "PAGER_TITLE" => "Новости",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array(""),
        "SET_BROWSER_TITLE" => "Y",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "Y",
        "SET_META_KEYWORDS" => "Y",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "Y",
        "SHOW_404" => "N",

        "SORT_BY1" => isset($_GET['sort']) ? $_GET['sort'] : 'name',
        "SORT_BY2" => isset($_GET['sort']) ? $_GET['sort'] : 'name',
        "SORT_ORDER1" => isset($_GET['method']) ? $_GET['method'] : 'asc',
        "SORT_ORDER2" => isset($_GET['method']) ? $_GET['method'] : 'asc',
        "STRICT_SECTION_CHECK" => "N",
        "LAZY_OR_PAGE" => $arParams['LAZY_OR_PAGE'],
    )
);

?>






