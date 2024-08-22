<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<!--<pre>-->
<?// print_r($arParams) ?>
<!--</pre>-->
<div class="category-tab">
    <div class="col-sm-12">
        <ul class="nav nav-tabs">
            <? foreach ($arResult['SECTIONS'] as $section) { ?>
                <li <?= !$initedTabs ? "class='active'" : '' ?>><a href="#<?= $section['CODE'] ?>"
                                                                   data-toggle="tab"><?= $section['NAME'] ?></a>
                </li>
                <?
                $initedTabs = true;
            }
            ?>
        </ul>
    </div>
    <div class="tab-content">
        <?php $initedTabs = false ?>
        <? foreach ($arResult['SECTIONS'] as $section) { ?>
            <div class="tab-pane fade <?= !$initedTabs ? "active in" : '' ?>"
                 id="<?= $section['CODE'] ?>">
                <? foreach ($arResult['ITEMS'] as $item) {
                    if ($item['SECTION_CODE'] == $section['CODE']) {
                        ?>
                        <div class="col-sm-3">
                            <div class="product-image-wrapper">
                                            <?
                                            $APPLICATION->IncludeComponent(
                                                "phpdev:catalogitem",
                                                "",
                                                array(
                                                    "ELEMENT_ID" => $item['ID'],
                                                    "IN_CART" => in_array($item['ID'], $arParams['PRODUCT_IDS']),
                                                    "AJAX_PRICE_LOAD" => true,
                                                ),
                                            );
                                            ?>
                            </div>
                        </div>
                        <?
                    }
                } ?>
            </div>
            <?
            $initedTabs = true;
        } ?>
    </div>
</div>
</div>
<?php
$counter = 1;
$isFirst = true;
?>



