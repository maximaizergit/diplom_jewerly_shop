<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Личный кабинет");
$APPLICATION->SetTitle("Личный кабинет");

$userName = $USER->GetFullName();
if (!$userName) {
    $userName = $USER->GetLogin();
    LocalRedirect('auth/', false,"302 Found");
}
else {
    $APPLICATION->IncludeComponent(
        "bitrix:system.auth.form",
        "phptemplate",
        array(
            "FORGOT_PASSWORD_URL" => "#SITE_DIR#/auth/?forgot_password=yes",
            "PROFILE_URL" => "#SITE_DIR#/perosnal/",
            "REGISTER_URL" => "#SITE_DIR#/auth/?register=yes",
            "SHOW_ERRORS" => "N",
            "COMPONENT_TEMPLATE" => "phptemplate"
        ),
        false
    );
}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>