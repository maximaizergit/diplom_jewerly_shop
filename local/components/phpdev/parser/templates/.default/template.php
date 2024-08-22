<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CJSCore::Init('ajax');
?>
<div class="parser-component">
    <form method="post" id="parser-form">
        <div class="link-group">
            <div class="wrapper">
                <div class="form-group">
                    <label for="url-input">Ссылка каталог</label>
                    <input type="text" value="<?= $arResult['url']?>" class="form-control" id="url-input" name="url" placeholder="Введите ссылку на сайт">
                </div>
                <div class="form-group">
                    <label for="url-input">Класс каталога (контейнер с товарами)</label>
                    <input type="text" value="<?= $arResult['catalogClass']?>" class="form-control" id="catalog-input" name="catalog" placeholder="Введите класс каталога">
                </div>
                <div class="form-group">
                    <label for="url-input">Ссылки исключения</label>
                    <input type="text" value="<?= $arResult['filteredLinks']?>" class="form-control" id="linksfilter-input" name="linksfilter" placeholder="Введите ссылки, которые будут проигнорированы">
                </div>
            </div>
        </div>
        <div class="main-buttons-wrapper">
        <input type="hidden" class="form-control" id="action-input" name="action" value="">
        <input type="hidden" class="form-control" id="isparsedlinks-input" name="isparsedlinks" value="<?=$arResult['ISPARSEDLINKS']?>">

        <button type="button" class="btn btn-primary" id="get-links-submit">Получить ссылки</button>


        <?php if($arResult['ISPARSEDLINKS'] == true && !empty($arResult['ISPARSEDLINKS'])){?>
            <button type="button" class="btn btn-primary" id="parse-submit">Найти товары</button>
        </div>
        <div class="tags-group">

                <label for="tags-input">Поисковой запрос</label>
                <textarea class="form-control" id="tags-input" name="tags" placeholder="Введите запрос"><?= $arResult['tags']?></textarea>

                <label for="tags-input">Коды свойств элемента для заполнения</label>
                <input type="text" value="<?= $arResult['codes']?>" class="form-control" id="codes-input" name="codes" placeholder="Введите коды через запятую">

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
}?>
<? if(!empty($arResult['item'])){
    ?>
    <div class="import-group">
        <label for="IID-input">ID инфоблока для импорта</label>
        <input type="text" value="" class="form-control" id="IID-input" name="IID" placeholder="Введите ID инфоблока">
        <label for="dir-input">Символьный код раздела</label>
        <input type="text" value="" class="form-control" id="dir-input" name="section" placeholder="Введите раздел инфоблока">
        <div>
            <button type="button" class="btn btn-primary" id="import-submit">Импорт в каталог</button>
        </div>
         <?php if($arResult['importResult']){ ?>
        <h1>Создано товаров:<?=$arResult['importResult']['created']?>, обновлено:<?=$arResult['importResult']['updated']?></h1>
    <? } ?>
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
        if(document.getElementById('IID-input').value){
               document.getElementById('action-input').value = 'import';
        document.getElementById('parser-form').submit();
        }
        else{
            alert("Введите номер инфоблока")
        }
    });

</script>
