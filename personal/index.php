<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональный раздел");
?>

<?php if($USER->IsAuthorized()){

   $APPLICATION->IncludeComponent(
        "phpdev:personal",
        ".default",
        array(	),
        false
    );
   ?>
    <form action="<?=$arResult["AUTH_URL"]?>">
        <?=bitrix_sessid_post()?>
        <input type="hidden" name="logout" value="yes" />
        <input type="submit" name="logout_butt" class="btn btn-primary" value="Выйти" />
    </form>
    <?php
}else{
    $APPLICATION->IncludeComponent(
        "bitrix:main.profile",
        ".default",
        Array(
            "CHECK_RIGHTS" => "N",
            "COMPONENT_TEMPLATE" => ".default",
            "SEND_INFO" => "N",
            "SET_TITLE" => "N",
            "USER_PROPERTY" => array(),
            "USER_PROPERTY_NAME" => ""
        )
    );
}
?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>