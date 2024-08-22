<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
<div class="header-body-top-column">
    <ul class="header-body-menu">
<?php foreach ($arResult as $arItem):?>
    <li><a href="<?= $arItem['LINK'] ?>" class="header-body-menu__link"><?= $arItem['TEXT'] ?></a></li>
<?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

