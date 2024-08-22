<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
    <ul class="header-submenu-list">
<?php foreach ($arResult as $arItem):?>
    <li><a href="<?= $arItem['LINK'] ?>" class="header-submenu__link"><?= $arItem['TEXT'] ?></a></li>
<?php endforeach; ?>
    </ul>
<?php endif; ?>
