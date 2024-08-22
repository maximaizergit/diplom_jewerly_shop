<?
/** @global CMain $APPLICATION */
use Bitrix\Main,
    Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\SiteTable,
    Bitrix\Main\UserTable,
    Bitrix\Main\Context,
    Bitrix\Main\Config\Option;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/prolog.php');
require_once(dirname(__DIR__) . '/lib/iblocktranslate.php');

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
    CModule::IncludeModule("phpdevorg.parser");

\Bitrix\Main\UI\Extension::load("ui.buttons");

$APPLICATION->SetTitle('Парсер товаров');

$parser = new test();
$arGroups = [];

$request=\Bitrix\Main\Application::getInstance()->getContext()->getRequest();

//Обновление скидки
//



//$discountGroupsToShow=$visualDiscount->getDiscountGroupsToShow();
//
//$arDiscounts=$visualDiscount->getSaleDiscount();
//
//
//$arSiteID=$visualDiscount->getIdSite();
//
////Период активности
//if (isset($request['ACTIVE_FROM']) && !empty($request['ACTIVE_FROM']) && $request['ACTIVE_FROM'] == 'on') {
//    $visualDiscount->getActiveDiscounts($arDiscounts);
//}
//
//$arCountDiscount = $visualDiscount->GetData($arDiscounts);

CJSCore::RegisterExt('visual_discounts_css', array(

    'css' => array(
        '/bitrix/css/phpdevorg.parser/style.css',
    ),
));
CJSCore::Init(array("visual_discounts_css"));

 $request = Context::getCurrent()->getRequest();
        $parser->url=$request->get('url');
        $parser->arResult['url']=$request->get('url');
        $parser->catalogClass =$request->get('catalog');
        $parser->arResult['catalogClass']=$parser->catalogClass;
        $parser->filterLinks =$request->get('linksfilter');
        $parser->arResult['filteredLinks']=$parser->filterLinks;
        $parser->arResult['ISPARSEDLINKS']=$request->get('isparsedlinks');
        $parser->tags = $parser->processText($request->get('tags'));
        $parser->arResult['tags']=$request->get('tags');
        $parser->codes = $parser->convertStringToArray($request->get('codes'));
        $parser->arResult['codes']=$request->get('codes');
        $parser->links = explode(",",$request->get('links'));
        $parser->arResult['links']=explode(",",$request->get('links'));

        $action = $request->get('action');
        if (!empty($parser->url)){
            if($action == 'getLinks'){
                $parser->parseUrl();
                $parser->arResult['ISPARSEDLINKS']=true;
            }
            if ($action =='parse'){


                if($validateRes = $parser->validateTags()){
                    $parser->arResult['ERROR'] = $validateRes;
                }else{
                    $parser->processedTags = $parser->processText($request->get('tags'));

                    $parser->arResult['arrTags']= $parser->processedTags;
                    if(!empty($parser->links)){
                        foreach ($parser->links as $link){

                            $parser->arResult['item'][] = $parser->parseDetailPage($link);
                        }
                        $parser->arResult['converteditems']=$parser->mergeCodesAndItems($parser->arResult['item'], $parser->codes);

                    }
                }


            }
            if ($action == 'import'){
                if($validateRes = $parser->validateTags()){
                    $parser->arResult['ERROR'] = $validateRes;
                }else {
                    $parser->items = json_decode($request->get('items'), true);
                    foreach ($parser->items as $item) {
                        $parser->arResult['item'][] = $item;
                    }
                    $parser->arResult['importResult'] = ['updated'=>0, 'created'=>0];
                    $convertedItems = $parser->mergeCodesAndItems($parser->arResult['item'], $parser->codes);
                    $parser->importToIblock($request->get('IID'), $convertedItems, $request->get('section'));
                    $parser->arResult['converteditems']=$convertedItems;

                }
            }
        }

?>

 <div class="parser-component">
    <form method="post" id="parser-form">
        <div class="link-group">
            <div class="wrapper">
                <div class="form-group">
                    <label for="url-input">Ссылка каталог</label>
                    <input type="text" value="<?= $parser->arResult['url']?>" class="form-control" id="url-input" name="url" placeholder="Введите ссылку на сайт">
                </div>
                <div class="form-group">
                    <label for="url-input">Класс каталога (контейнер с товарами)</label>
                    <input type="text" value="<?= $parser->arResult['catalogClass']?>" class="form-control" id="catalog-input" name="catalog" placeholder="Введите класс каталога">
                </div>
                <div class="form-group">
                    <label for="url-input">Ссылки исключения</label>
                    <input type="text" value="<?= $parser->arResult['filteredLinks']?>" class="form-control" id="linksfilter-input" name="linksfilter" placeholder="Введите ссылки, которые будут проигнорированы">
                </div>
            </div>
        </div>
        <div class="main-buttons-wrapper">
        <input type="hidden" class="form-control" id="action-input" name="action" value="">
        <input type="hidden" class="form-control" id="isparsedlinks-input" name="isparsedlinks" value="<?=$parser->arResult['ISPARSEDLINKS']?>">

        <button type="button" class="btn btn-primary" id="get-links-submit">Получить ссылки</button>


        <?php if($parser->arResult['ISPARSEDLINKS'] == true && !empty($parser->arResult['ISPARSEDLINKS'])){?>
            <button type="button" class="btn btn-primary" id="parse-submit">Найти товары</button>
        </div>
        <div class="tags-group">

                <label for="tags-input">Поисковой запрос</label>
                <textarea class="form-control" id="tags-input" name="tags" placeholder="Введите запрос"><?= $parser->arResult['tags']?></textarea>

                <label for="tags-input">Коды свойств элемента для заполнения</label>
                <input type="text" value="<?= $parser->arResult['codes']?>" class="form-control" id="codes-input" name="codes" placeholder="Введите коды через запятую">

            <input type="hidden" value="<?echo implode(',',$parser->arResult['links'])?>" class="form-control" id="links-input" name="links" placeholder="Введите коды через запятую">
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

<?php if($parser->arResult['ISPARSEDLINKS']){
?>
    <div class="found-links">
        <h1>Ссылки</h1>
        <ul>
            <?foreach ($parser->arResult['links'] as $link){
                ?>  <li><a href="<?=$link?>"><?=$link?></a></li><?
            }?>

        </ul>
    </div>

    <?php
}?>
<?
if($parser->arResult['ERROR']){?>
     <h1><?=$parser->arResult["ERROR"]?></h1><?
}
if(!empty($parser->arResult['item'])){
    ?>
    <div class="import-group">
        <label for="IID-input">ID инфоблока для импорта</label>
        <input type="text" value="" class="form-control" id="IID-input" name="IID" placeholder="Введите ID инфоблока">
        <label for="dir-input">Символьный код раздела</label>
        <input type="text" value="" class="form-control" id="dir-input" name="section" placeholder="Введите раздел инфоблока">
        <div>
            <button type="button" class="btn btn-primary" id="import-submit">Импорт в каталог</button>
        </div>
         <?php if($parser->arResult['importResult']){ ?>
        <h1>Создано товаров:<?=$parser->arResult['importResult']['created']?>, обновлено:<?=$parser->arResult['importResult']['updated']?></h1>
    <? } ?>
    </div>

    <? $jsonString = json_encode($parser->arResult['item']);
    echo '<input type="hidden" name="items" value="' . htmlspecialchars($jsonString) . '">';
    ?>
    <div class="items-group">
        <h1>Товары</h1>

        <?foreach ($parser->arResult['converteditems'] as $item){ ?>
        <ul>
            <?foreach ($item as $key=>$value){ ?>
            <li>[<?=$key?>] = <?=$value?></li>
            <? } ?>
        </ul>
        <? } ?>
    </div>

    <?php
}
?>
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

<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');