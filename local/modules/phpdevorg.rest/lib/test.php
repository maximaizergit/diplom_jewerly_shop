<?php
use Bitrix\Main,
    Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\SiteTable,
    Bitrix\Main\UserTable,
    Bitrix\Main\Config\Option,
    Bitrix\Sale;

class test
{
    private $request;

    public function __construct(){
        $this->request=\Bitrix\Main\Context::getCurrent()->getRequest();
        CModule::IncludeModule("sale");
    }


    public function print($string) {

        return $string;
    }
}
