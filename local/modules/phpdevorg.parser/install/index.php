<?

IncludeModuleLangFile(__FILE__);

Class phpdevorg_parser extends CModule
{
    var $MODULE_ID = "phpdevorg.parser";

    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $MODULE_GROUP_RIGHTS = 'Y';
    public function __construct()
    {
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION           = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE      = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_ID            = 'phpdevorg.parser';
        $this->MODULE_NAME          = 'Парсер';
        $this->MODULE_DESCRIPTION   = GetMessage("VISUAL_WORK_DISCOUNTS_MODULE_DESCRIPTION");
        $this->PARTNER_NAME         = GetMessage("VISUAL_WORK_DISCOUNTS_PARTNER_NAME");
        $this->PARTNER_URI          = "https://phpdev.org/";
    }

    function DoInstall()
    {
        global $APPLICATION;
        $this->InstallDB();         // выполнение операций с базой данных
        $this->InstallEvents();     // регистрируем обработчики которые нужны
        $this->InstallFiles();      // производим манипуляции с файлами
        RegisterModule($this->MODULE_ID);
        return true;
    }

    function DoUninstall()
    {
        global $APPLICATION;
        echo $this->GetPath();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        UnRegisterModule($this->MODULE_ID);
        return true;
    }

    public function GetPath($notDocumentRoot = false){
        if($notDocumentRoot){
            return str_replace(\Composer\Console\Application::getDocumentRoot(), '', dirname(__DIR__));
        }else{
            return dirname(__DIR__);
        }
    }

    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }

    function InstallDB()
    {
        return true;
    }

    function InstallEvents()
    {
        // \Bitrix\Main\EventManager::getInstance()->registerEventHandler($this->MODULE_ID, 'TestEventD7', $this->MODULE_ID, '\Academy\D7\Event', 'eventHandler');
        return true;
    }

    function UnInstallDB(){
        return true;
    }

    function UnInstallEvents()
    {
        // \Bitrix\Main\EventManager::getInstance()->unRegisterEventHandler($this->MODULE_ID, 'TestEventD7', $this->MODULE_ID, '\Academy\D7\Event', 'eventHandler');
        return true;
    }

    function InstallFiles()
    {
        CopyDirFiles($this->GetPath() . "/install/css/phpdevorg.parser", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/css/phpdevorg.parser", true);
        CopyDirFiles($this->GetPath() . "/install/admin/parser_start.php", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/parser_start.php", true);
        CopyDirFiles($this->GetPath() . "/install/simpleHtmlDom/simple_html_dom.php", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/simpleHtmlDom/simple_html_dom.php", true);
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($this->GetPath() . "/install/admin/", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
        DeleteDirFilesEx("/bitrix/css/phpdevorg.parser");
        return true;
    }
}?>