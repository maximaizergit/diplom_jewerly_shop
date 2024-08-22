<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init('ajax');
?>
<div class="parser-component">
    <form method="post" id="parser-form">

        <div class="main-buttons-wrapper">
        <input type="hidden" class="form-control" id="action-input" name="action" value="">
        <input type="hidden" class="form-control" id="isparsedlinks-input" name="isparsedlinks" value="<?=$arResult['ISPARSEDLINKS']?>">

        <button type="button" class="btn btn-primary" id="get-links-submit">Получить ссылки</button>


        <?php if($arResult['ISPARSEDLINKS'] == true && !empty($arResult['ISPARSEDLINKS'])){?>
            <button type="button" class="btn btn-primary" id="parse-submit">Найти товары</button>
        </div>
        <div class="tags-group">



            <input type="hidden" value="<?echo implode(',',$arResult['links'])?>" class="form-control" id="links-input" name="links" placeholder="Введите коды через запятую">
        </div>
        <?php }else{ ?>
        </div>
        <?php }?>


<script>
    document.getElementById('get-links-submit').addEventListener('click', function() {
        document.getElementById('action-input').value = 'getLinks';
        document.getElementById('parser-form').submit();
    });

    document.getElementById('parse-submit').addEventListener('click', function() {
        document.getElementById('action-input').value = 'parse';
        document.getElementById('parser-form').submit();
    });
</script>
<? if(!$arResult['ERROR']){?>
<?php if($arResult['ISPARSEDLINKS']){
?>
    <div class="found-links">
        <h1>Ссылки</h1>
        <ul>
            <?foreach ($arResult['links'] as $link){
                ?>  <li><a href="<?=$link?>"><?=$link?></a></li><?
            }?>

        </ul>
    </div>

    <?php
}

//             print_r($arResult);
?>

<? if(!empty($arResult['item'])){
    ?>
    <div class="import-group">
        <div>
            <button type="button" class="btn btn-primary" id="import-submit">Импорт в каталог</button>
        </div>
         <?php if($arResult['importResult']){ ?>
        <h1>Создано товаров:<?=$arResult['importResult']['created']?>, обновлено:<?=$arResult['importResult']['updated']?></h1>
    <? }?>
    </div>

    <? $jsonString = json_encode($arResult['item']);
    echo '<input type="hidden" name="items" value="' . htmlspecialchars($jsonString) . '">';
    ?>
    <div class="items-group">
        <h1>Товары</h1>

        <?foreach ($arResult['converteditems'] as $item){ ?>
        <ul>
            <?foreach ($item as $key=>$value){ ?>
            <li>[<?=$key?>] = <?=$value?></li>
            <? } ?>
        </ul>
        <? } ?>
    </div>

    <?php
}
}else{
    ?> <h1><?=$arResult["ERROR"]?></h1><?
}?>
</form>
</div>
<!--<pre>-->
<?// print_r($arResult) ?>
<!--</pre>-->

<script>
    document.getElementById('import-submit').addEventListener('click', function() {
        document.getElementById('action-input').value = 'import';
        document.getElementById('parser-form').submit();

    });

</script>
