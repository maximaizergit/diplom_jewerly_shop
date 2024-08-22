<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
    <ul class="header-menu-list">
<?php foreach ($arResult as $arItem):?>
    <li><a href="<?= $arItem['LINK'] ?>" class="header-menu__link"><?= $arItem['TEXT'] ?></a></li>
<?php endforeach; ?>
        <li><span class="header-menu-more">Ещё <span class="header-menu-more__icon"><span></span><span></span><span></span></span></span></li>
    </ul>
<?php endif; ?>


