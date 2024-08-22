<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;

use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
BXClearCache(true, "/your/cache/relative/path");
if(CSite::InDir('/en/') ) {
    $lang = 'en';
}
?>

<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><? $APPLICATION->ShowTitle() ?></title>
    <?
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/Assets2/css/bootstrap.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/Assets2/css/font-awesome.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/Assets2/css/prettyPhoto.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/Assets2/css/price-range.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/Assets2/css/animate.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/Assets2/css/main.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/Assets2/css/responsive.css');
    ?>
    <!--[if lt IE 9]>

<?
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/Assets2/js/html5shiv.js');
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/Assets2/js/respond.min.js');
    ?>
    <![endif]-->
    <?
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/Assets2/js/jquery.js');
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/Assets2/js/bootstrap.min.js');
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/Assets2/js/jquery.scrollUp.min.js');
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/Assets2/js/price-range.js');
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/Assets2/js/jquery.prettyPhoto.js');
    Asset::getInstance()->addJS(SITE_TEMPLATE_PATH . '/Assets2/js/main.js');
    ?>
    <link rel="shortcut icon" href="<?= SITE_TEMPLATE_PATH ?>/Assets2/images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144"
          href="<?= SITE_TEMPLATE_PATH ?>/Assets2/images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114"
          href="<?= SITE_TEMPLATE_PATH ?>/Assets2/images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72"
          href="<?= SITE_TEMPLATE_PATH ?>/Assets2/images/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed"
          href="<?= SITE_TEMPLATE_PATH ?>/Assets2/images/ico/apple-touch-icon-57-precomposed.png">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <? $APPLICATION->ShowHead(); ?>
</head>

<body>

<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>
<header id="header"><!--header-->
    <div class="header_top"><!--header_top-->
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="contactinfo">

                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array(
                                "AREA_FILE_SHOW" => "sect",
                                "AREA_FILE_SUFFIX" => "inc",
                                "EDIT_TEMPLATE" => ""
                            )
                        ); ?>


                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="social-icons pull-right">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array(
                                "AREA_FILE_SHOW" => "sect",
                                "AREA_FILE_SUFFIX" => "inc2",
                                "EDIT_TEMPLATE" => ""
                            )
                        ); ?>


                    </div>
                </div>
            </div>
        </div>
    </div><!--/header_top-->

    <div class="header-middle"><!--header-middle-->
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="logo pull-left">
                        <? if ($APPLICATION->GetCurPage(false) == SITE_DIR) {
                            ?>
                            <a><img src="<?= SITE_TEMPLATE_PATH ?>/Assets2/images/home/logo.png" alt=""/></a>
                            <?
                        } else { ?>
                            <a href="<?=!empty($lang)? '/'.$lang:""?>/"><img src="<?= SITE_TEMPLATE_PATH ?>/Assets2/images/home/logo.png" alt=""/></a>

                        <? } ?>
                    </div>

                    <div class="btn-group pull-right">

                        <?php
                        // Проверка GET-запроса на выбранную валюту
                        if (isset($_GET['currency'])) {
                            $_SESSION['CURRENCY_ID'] = $_GET['currency'];

                        }
                        ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <?php
                                // Получение текущей валюты пользователя из сессии или установка по умолчанию

                                if (isset($_SESSION['CURRENCY_ID'])) {
                                    $currency = $_SESSION['CURRENCY_ID'];
                                } elseif (isset($_GET['currency'])) {
                                    $_SESSION['CURRENCY_ID'] = $_GET['currency'];
                                    $currency = $_GET['currency'];
                                } else {
                                    $currency = 'BYN';
                                }

                                echo $currency;
                                ?>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="?currency=USD&clear_cache=Y">USD</a></li>
                                <li><a href="?currency=RUB&clear_cache=Y">RUB</a></li>
                                <li><a href="?currency=BYN&clear_cache=Y">BYN</a></li>
                            </ul>
                        </div>

                        <div class="lang-wrapper">

                            <?php
                            // Получаем текущий URL
                            $currentUrl = $APPLICATION->GetCurPage();

                            // Проверяем, является ли текущий URL английским
                            $isEnglish = (strpos($currentUrl, "/en/") !== false);

                            // Формируем ссылку на английскую версию сайта
                            $englishUrl = ($isEnglish) ? str_replace("/en/", "/", $currentUrl) : "/en" . $currentUrl;

                            // Выводим переключатель языка
                            if ($isEnglish) {
                                echo "<a class='lang-switch' href=\"$englishUrl\">RU</a>";
                            } else {
                                echo "<a class='lang-switch' href=\"$englishUrl\">EN</a>";
                            }
                            ?>
                        </div>
                    </div>

                </div>




                <div class="col-sm-8">
                    <div class="shop-menu pull-right">
                        <ul class="nav navbar-nav">

                            <li><a href="<?=!empty($lang)? '/'.$lang :""?>/catalog/compare.php"><i class="fa fa-exchange"></i> <?=GetMessage("COMPARE")?></a></li>

                            <li><a href="<?=!empty($lang)? '/'.$lang :""?>/cart/"><i class="fa fa-shopping-cart"></i> <?=GetMessage("CART")?></a></li>
                            <?
                            $frame = new \Bitrix\Main\Page\FrameBuffered("my_dynamic");
                            $frame->begin();
                            if (!$USER->isAuthorized()) {
                                ?>

                                <li><a href="<?=!empty($lang)? '/'.$lang:""?>/auth/"><i class="fa fa-lock"></i> Login</a></li>
                            <?
                            } else {
                                ?>
                                <li><a href="<?=!empty($lang)? '/'.$lang:""?>/personal/"><i class="fa fa-user"></i> <?= $USER->GetFullName() ?></a></li>
                                <?
                            }
                            $frame->beginStub();
                            ?>


                            <?
                            $frame->end();
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/header-middle-->

    <div class="header-bottom"><!--header-bottom-->
        <div class="container">
            <div class="row">
                <div class="col-sm-9">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="mainmenu pull-left">
                        <ul class="nav navbar-nav collapse navbar-collapse">

                            <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "top_right_menu",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "left",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "N",
                                    "MENU_CACHE_USE_GROUPS" => "N",
                                    "ROOT_MENU_TYPE" => "topright",
                                    "USE_EXT" => "N",
                                    "COMPONENT_TEMPLATE" => "top_right_menu"
                                ),
                                false
                            ); ?>

                        </ul>
                    </div>
                </div>
                <div class="col-sm-3">

                    <? $APPLICATION->IncludeComponent(
                        "bitrix:search.form",
                        "",
                        array(),
                        false
                    ); ?>
                </div>
            </div>
        </div>
    </div><!--/header-bottom-->
</header><!--/header-->

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
