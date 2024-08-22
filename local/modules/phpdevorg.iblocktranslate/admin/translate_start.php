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
CModule::IncludeModule("phpdevorg.iblocktranslate");

\Bitrix\Main\UI\Extension::load("ui.buttons");

$APPLICATION->SetTitle('Переводчик товаров');

$request = Context::getCurrent()->getRequest();

$action = $request->get('action');


//Мой ключ 'AIzaSyB4hJJYbAs5_8fvXtCfqoCBXf2dB3uEIgw'
$translate = new iblocktranslate('https://translation.googleapis.com/language/translate/v2?key=');

if ($action == 'setKey') {
    $translate->key=$request->get('apiKey');
} elseif ($action == 'getCount') {
    $filterCount = $translate->getCount($request->get('filter'), $request->get('inputIblockId'));
} elseif ($action == 'doTranslate') {
    $translate->key=$request->get('apiKey');
    $elements = $translate->getElements($request->get('filter'));
    $result = $translate->copyElements($elements, $request->get('outputIblockId'));
}


?>
    <!--<pre>-->
    <!--    --><? //print_r($translate->arResult);?>
    <!--</pre>-->
    <div class="translate-component">
    <form method="post" id="parser-form">
        <input type="hidden" class="form-control" id="action-input" name="action" value="">
        <div class="link-group">
            <div class="wrapper">
                <h1>Перевод элементов</h1>
                <div class="form-group">
                    <label for="url-input">Google API ключ</label>
                    <input type="text" value="<?= $request->get('apiKey') ?>" class="form-control"
                           id="key-input"
                           name="apiKey" placeholder="Введите ключ api">
                    <button type="button" class="btn btn-primary" id="set-key">Установить ключ</button>

                </div>

                <?php
                if ($request->get('apiKey')){
                echo "<h2>Источник элементов</h2>";
                // Вывод списка всех инфоблоков в виде выпадающего списка (select)
                echo '<select id="iblockSelect" onchange="setInput(this.value)">';
                $iblockList = CIBlock::GetList(array(), array('CHECK_PERMISSIONS' => 'N'));
                echo '<option value="Не выбрано">Не выбрано</option>';

                while ($iblock = $iblockList->Fetch()) {
                    // Вывод опции для каждого инфоблока
                    echo '<option value="' . $iblock['ID'] . '">' . $iblock['NAME'] . '</option>';
                }
                echo '</select>';
                ?>

                <?php
                echo "<h2>Куда перевести</h2>";
                // Вывод списка всех инфоблоков в виде выпадающего списка (select)
                echo '<select id="iblockSelect" onchange="setOutput(this.value)">';
                $iblockList = CIBlock::GetList(array(), array('CHECK_PERMISSIONS' => 'N'));
                echo '<option value="Не выбрано">Не выбрано</option>';

                while ($iblock = $iblockList->Fetch()) {
                    // Вывод опции для каждого инфоблока
                    echo '<option value="' . $iblock['ID'] . '">' . $iblock['NAME'] . '</option>';
                }
                echo '</select>';
                ?>

                <!-- Скрытый Input для установки ID выбранного инфоблока -->
                <input type="hidden" name="inputIblockId" id="inputIblockId">
                <input type="hidden" name="outputIblockId" id="outputIblockId">

                <script>
                    // Функция для установки ID выбранного инфоблока в скрытый Input
                    function setInput(iblockId) {
                        document.getElementById('inputIblockId').value = iblockId;
                    }

                    function setOutput(iblockId) {
                        document.getElementById('outputIblockId').value = iblockId;
                    }
                </script>

            </div>
            <h2>Параметры выборки:</h2>
            <div class="filter-wrapper" style="display: flex; flex-direction: column">
            <textarea id="filterParams" rows="4" cols="50" name="filter"
                      placeholder="Введите параметры выборки"><?= $request->get('filter') ?></textarea>
                <div>
                    <button type="button" class="btn btn-primary" id="get-count">Проверить запрос</button>

                </div>
            <? if ($action == 'getCount') {
                echo "Элементов найдено:" . $filterCount;
            }
            if ($action == 'doTranslate') {
                echo '<pre>';
                print_r($result);
                echo '</pre>';

            } ?>
            </div>

        </div>
        <div class="main-buttons-wrapper">
            <button type="button" class="btn btn-primary" id="do-translate">Перевести</button>
        </div>
        <?
        }
        else {
            echo '</div>';
        } ?>
    </form>


    <!--<pre>-->
    <? // print_r($arResult) ?>
    <!--</pre>-->
    <script>
        document.getElementById('set-key').addEventListener('click', function () {
            document.getElementById('action-input').value = 'setKey';
            document.getElementById('parser-form').submit();
        });
        <?if ($request->get('apiKey')){
            ?>
            document.getElementById('get-count').addEventListener('click', function () {
            document.getElementById('action-input').value = 'getCount';
            document.getElementById('parser-form').submit();
        });
        document.getElementById('do-translate').addEventListener('click', function () {
            document.getElementById('action-input').value = 'doTranslate';
            document.getElementById('parser-form').submit();
        });
            <?
        }?>


    </script>


<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');