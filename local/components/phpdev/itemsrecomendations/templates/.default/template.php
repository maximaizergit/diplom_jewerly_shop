<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<!--<pre>-->
<?// print_r($arResult) ?>
<!--</pre>-->
<?php
$counter = 1;
$isFirst = true;
?>
<div class="recommended_items"><!--recommended_items-->
    <h2 class="title text-center">recommended items</h2>
    <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?foreach ($arResult['ITEMS'] as $item) {
                if ($counter == 1) {
                    $str = $isFirst ? 'active' : ' ';
                    echo '<div class="item ' . $str . '">';;
                    $isFirst = false;
                }
                ?>
                <div class="col-sm-4">
                    <div class="product-image-wrapper">
                        <div class="single-products">
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
                </div>
                <?
                if ($counter == 3) {
                    echo '</div>';
                    $counter = 1;
                } else {
                    $counter++;
                }
            }
            ?>
        </div>
        <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
            <i class="fa fa-angle-left"></i>
        </a>
        <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div><!--/recommended_items-->




