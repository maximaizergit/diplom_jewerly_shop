<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<?php if (!empty($arResult['ITEMS'])): ?>


    <section id="slider"><!--slider-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#slider-carousel" data-slide-to="1"></li>
                            <li data-target="#slider-carousel" data-slide-to="2"></li>
                        </ol>

                        <div class="carousel-inner">
                            <? $firstItem = true ?>
                            <?php foreach ($arResult['ITEMS'] as $arItem):
                                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))); ?>

                                <div class="item <?= $firstItem ? 'active' : ' ' ?>">
                                    <? if ($firstItem) {
                                        $firstItem = false;
                                    } ?>
                                    <div class="col-sm-6">
                                        <h1><span>E</span>-SHOPPER</h1>
                                        <h2><?= isset($arItem['NAME']) ? $arItem['NAME'] : "" ?></h2>
                                        <p> <?= isset($arItem['PREVIEW_TEXT']) ? $arItem['PREVIEW_TEXT'] : "" ?> </p>
                                        <button type="button" class="btn btn-default get">Get it now</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <?php if (!empty($arItem['PREVIEW_PICTURE']['SRC'])): ?>
                                            <img src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>"
                                                 class="girl img-responsive" alt="">
                                        <?php endif; ?>
                                        <?php if (!empty($arItem['DETAIL_PICTURE']['SRC'])): ?>
                                            <img src="<?= $arItem['DETAIL_PICTURE']['SRC'] ?>" class="pricing" alt="">
                                        <?php endif; ?>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </div>

                        <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section><!--/slider-->

<?php endif; ?>




