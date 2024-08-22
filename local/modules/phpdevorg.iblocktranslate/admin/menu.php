<?

IncludeModuleLangFile(__FILE__);
$APPLICATION->SetAdditionalCSS("/bitrix/panel/main/export_onliner_menu.css");

if ($APPLICATION->GetGroupRight("phpdevorg.iblocktranslate") != "D") {
    $aMenu = array(
        "parent_menu" => "global_menu_marketing",
        //"section" => "phpdevorg.exportonliner",
        "sort" => 500,
        "icon" => "export_onliner_menu_icon",
        "text" => GetMessage("YAUHENI_DISCOUNT"),
        "title" => GetMessage("YAUHENI_DISCOUNT"),
        "items_id" => "phpdevorg.iblocktranslate",
        "url" => "translate_start.php?lang=" . LANGUAGE_ID,
        "items" => array()

    );
    return $aMenu;

}
return false;
?>