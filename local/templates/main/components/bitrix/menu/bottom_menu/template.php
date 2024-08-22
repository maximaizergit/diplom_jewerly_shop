<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if (!empty($arResult)):?>
    <div class="footer-content-column">
        <ul class="footer-menu">
<?php $count = 0; ?>
<?php foreach ($arResult as $arItem):?>
    <?php if ($count!=3): ?>
        <li><a href="<?= $arItem['LINK'] ?>" class="footer-menu__link"><?= $arItem['TEXT'] ?></a></li>
        <?php $count++ ?>
    <?php else: ?>
       </ul>
    </div>
       <div class="footer-content-column">
            <ul class="footer-menu">
                <li><a href="<?= $arItem['LINK'] ?>" class="footer-menu__link"><?= $arItem['TEXT'] ?></a></li>
                <?php $count = 1; ?>
    <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

