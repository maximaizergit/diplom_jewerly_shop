<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<div class="shipping text-center"><!--shipping-->
    <?php if (!empty($arResult['ITEMS'])): ?>
        <?php foreach ($arResult['ITEMS'] as $arItem):
            if (!empty($arItem['DETAIL_PICTURE']['SRC'])):?>
                <a href="<?= $arItem['PROPERTIES']['LINK']['VALUE'] ?>">
                    <img src="<?= $arItem['DETAIL_PICTURE']['SRC'] ?>" class="" alt="">
                </a>
            <?php endif; ?>

        <?php endforeach; ?>

    <?php endif; ?>

</div><!--/shipping-->



