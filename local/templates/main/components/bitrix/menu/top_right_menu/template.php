<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
<?php foreach ($arResult as $arItem):?>
    <? if ($arItem['LINK'] != $_SERVER['REQUEST_URI'] ){?>
    <li><a href="<?= $arItem['LINK'] ?>" class="header-body-menu__link"><?= $arItem['TEXT'] ?></a></li>
    <?}else{?>
    <li><a class="header-body-menu__link active"><?= $arItem['TEXT'] ?></a></li>
    <?} ?>
<?php endforeach; ?>
<?php endif; ?>

