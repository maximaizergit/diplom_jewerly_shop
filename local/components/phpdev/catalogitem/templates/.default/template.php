<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

            <div class="single-products">
                <div class="productinfo text-center">
                    <? if (!empty($arResult['fields']['PREVIEW_PICTURE'])) { ?>
                        <img src="<?= CFile::GetFileArray($arResult['fields']['PREVIEW_PICTURE'])['SRC']; ?>" alt=""/>
                    <? } else { ?>
                        <img src="<?= SITE_TEMPLATE_PATH ?>/Assets2/images/404/no-image.jpg" alt=""/>
                    <? }
                    if ($_SESSION['CURRENCY_ID']) {
                        $conversionResult = round(CCurrencyRates::ConvertCurrency($arResult['PRICE']['PRICE'], 'BYN', $_SESSION['CURRENCY_ID']), 2);
                        ?>

                        <h2 data-value="<?= $arResult['fields']['ID'] ?>"><?= $conversionResult . $_SESSION["CURRENCY_ID"] ?></h2>

                        <?
                    } else if (!$arParams['AJAX_PRICE_LOAD']) {
                        ?>
                        <h2 data-value="<?= $arResult['fields']['ID'] ?>"><?= $arResult["PRICE"]["PRICE"] ?>
                            <?= $arResult["PRICE"]["CURRENCY"] ?></h2>
                    <? } else { ?>
                        <h2 data-value="<?= $arResult['fields']['ID'] ?>"></h2>
                    <?} ?>


                    <p><?= $arResult['fields']['NAME'] ?></p>

                    <?php
                    echo '<a href="' . $arResult['fields']['DETAIL_PAGE_URL'] . '" class="btn btn-default add-to-cart"><i class="fa fa-eye"></i>' . ($arParams['LANGUAGE_ID']=='en' ? 'Details' : GetMessage('WATCH')) . '</a>';

                    if ($arParams['IN_CART'] == true) {
                        echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $arResult['fields']['ID'] . '"><i class="fa fa-check"></i>' . ($arParams['LANGUAGE_ID']=='en' ? 'In cart!' :GetMessage('IN_CART')) . '</a>';
                    } else {
                        echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $arResult['fields']['ID'] . '"><i class="fa fa-shopping-cart"></i>' . ($arParams['LANGUAGE_ID']=='en' ? 'Add to cart' :GetMessage('ADD_TO_CART')) . '</a>';
                    }

                    ?>
                    <a class="btn btn-default add-to-cart"
                       href="?action=ADD_TO_COMPARE_LIST&id=<?= $item['ID'] ?>"> <?= $arParams['LANGUAGE_ID']=='en' ? 'Compare' :GetMessage('COMPARE') ?></a>
                </div>
                <div class="product-overlay">
                    <div class="overlay-content">
                        <?
                        if ($_SESSION['CURRENCY_ID']) {
                            $conversionResult = round(CCurrencyRates::ConvertCurrency($arResult['PRICE']['PRICE'], 'BYN', $_SESSION['CURRENCY_ID']), 2);
                            ?>

                            <h2 data-value="<?= $arResult['fields']['ID'] ?>"><?= $conversionResult . $_SESSION["CURRENCY_ID"] ?></h2>

                            <?
                        } else if (!$arParams['AJAX_PRICE_LOAD']) {
                            ?>
                            <h2 data-value="<?= $arResult['fields']['ID'] ?>"><?= $arResult["PRICE"]["PRICE"] ?>
                                <?= $arResult["PRICE"]["CURRENCY"] ?></h2>
                        <? } else { ?>
                            <h2 data-value="<?= $arResult['fields']['ID'] ?>"></h2>
                        <?
                        } ?>
                        <p><?= $$arResult['fields']['NAME'] ?></p>
                        <?php
                        echo '<a href="' . $arResult['fields']['DETAIL_PAGE_URL'] . '" class="btn btn-default add-to-cart"><i class="fa fa-eye"></i>' . ($arParams['LANGUAGE_ID']=='en' ? 'Details' :GetMessage('WATCH')) . '</a>';

                        if ($arParams['IN_CART'] == true) {
                            echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $arResult['fields']['ID'] . '"><i class="fa fa-check"></i>' . ($arParams['LANGUAGE_ID']=='en' ? 'In cart!' :GetMessage('IN_CART')) . '</a>';
                        } else {
                            echo '<a class="btn btn-default add-to-cart" id="add-to-cart" data-value="' . $arResult['fields']['ID'] . '"><i class="fa fa-shopping-cart"></i>' . ($arParams['LANGUAGE_ID']=='en' ? 'Add to cart' :GetMessage('ADD_TO_CART')) . '</a>';
                        }


                        ?>

                        <a class="btn btn-default add-to-cart"
                           href="?action=ADD_TO_COMPARE_LIST&id=<?= $arResult['fields']['ID'] ?>"> <?= $arParams['LANGUAGE_ID']=='en' ? 'Compare' :GetMessage('COMPARE') ?></a>

                    </div>
                </div>
            </div>



<?php
//echo '<pre>';
//print_r($arResult);
//echo '</pre>';

