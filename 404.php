<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
define("HIDE_SIDEBAR", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");?>

    <div class="page404">
        <div class='container'>
            <div class="page404-body">
                <div class="page404__title">404</div>
                <div class="page404__text">Запрашиваемая страница не найдена. Возможно она была перемещена или удалена. Попробуйте <a href="">вернуться на предыдущую страницу</a> или воспользуйтесь навигационным меню.</div>
            </div>
        </div>
    </div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>