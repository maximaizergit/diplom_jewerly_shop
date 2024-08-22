<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main;

use Bitrix\Main\Context;
CModule::IncludeModule("shiglov.iblockinterface");
use \Shiglov\IBlock\IBlockInterface;
\CModule::IncludeModule("iblock");
class CPersonal extends \CBitrixComponent
{

    public function executeComponent()
    {
        $this->prepareResult();
        if (!isset($this->arParams['SKIP_TEMPLATE']) || !$this->arParams['SKIP_TEMPLATE'])
        {
            $this->includeComponentTemplate();
        }

        return $this->arResult;
    }

    private function prepareResult()
    {
        $request = Context::getCurrent()->getRequest();
        $action = $request->get('action');
        if($action=='updateData'){
            $this->arResult['RESULT']=$this->updateUserSettings(json_decode($request->get('data'),true));
        }else if ($action=='updatePhoto'){
           $this->updatePhoto();
        }else if($action=='updatePass'){
            $this->arResult['RESULT']=$this->updateUserSettings(json_decode($request->get('data'),true));
        }else if($action=="updateAlbum"){
            $this->updateAlbum();
        }else if ($action === 'deleteAlbumItem'){
            $this->deleteAlbumItem(json_decode($request->get('data'),true));
        }

        $this->getUserData();
    }

    private function getUserData(){
        global $USER;
        if ($USER->IsAuthorized()) {
            $userInfo = $USER->GetByID($USER->GetID())->Fetch();
            $this->arResult['USER_NAME'] = $userInfo['NAME'];
            $this->arResult['USER_LAST_NAME'] = $userInfo['LAST_NAME'];
            $this->arResult['USER_EMAIL'] = $userInfo['EMAIL'];
            $this->arResult['USER_LOGIN'] = $userInfo['LOGIN'];
            $this->arResult['TEST'] = $userInfo;

        }
        $arFilter = array( "IBLOCK_ID" => 19, "PROPERTY_UID" => $USER->GetID(), );
        $rsItems = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, false, Array());
        if ($arItem = $rsItems->fetch()) {
            $currentValues = CIBlockElement::GetProperty(19, $arItem["ID"], "sort", "asc", array("CODE" => "PHOTOS"));
            $photos = array();
            while ($photo = $currentValues->Fetch()) {
                $photos[] = $photo["VALUE"];
            }
            $this->arResult["PHOTOS"]=$photos;
            $this->arResult["temp"][]=$arItem;

        }

    }
    private function updateUserSettings($userData) {
        global $USER;
        $userId = $USER->GetID();
        $user = new CUser;
        $user->Update($userId, $userData);
        $strError = $user->LAST_ERROR;
        if($strError){
            return $strError;
        }
      return true;
    }
    private function updatePhoto(){
        $this->arResult['RESULT']= $_FILES;

        $arr_file = array(
            "name" =>$_FILES['photo']['name'],
            "size" =>$_FILES['photo']['size'],
            "tmp_name" =>$_FILES['photo']['tmp_name'],
            "type" => "image/jpeg",
            "old_file" => "",
            "del" => "Y",
            "MODULE_ID" => ""
        );
        $fid = CFile::SaveFile($arr_file,'personal',true);
        $fileData = CFile::GetFileArray($fid);
        $arFile['name']=$fileData['FILE_NAME'];
        $arFile['size']=$fileData['FILE_SIZE'];
        $arFile['external_id']=$fileData['EXTERNAL_ID'];

        $relativePath = $fileData['SRC'];
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $relativePath;

        $arFile['tmp_name']=$fullPath;
        $arFile['type']='image/jpeg';
        $arFile['del'] = "N";
        $arFile['old_file'] = "";

        $this->updateUserSettings(['PERSONAL_PHOTO'=>$arFile]);
    }

    private function updatePassword($userData){
        global $USER;
        $rsUser = CUser::GetByID(1);
        if ($arUser = $rsUser->Fetch())
            echo substr($arUser['CHECKWORD'], 0, 8);
        $this->arResult['pass'] = $USER->ChangePassword($userData['LOGIN'], "WRD45GT", $userData['PASSWORD'], $userData['PASSWORD']);

    }

    private function updateAlbum()
    {

        global $USER;

// Путь к загруженному изображению
        $this->arResult['RESULT'][] = $_FILES;
        $this->arResult['RESULT']['rearray'] = $this->reArrayFiles($_FILES['album']);
        $uploadedPhotos = $this->reArrayFiles($_FILES['album']);


        foreach ($uploadedPhotos as $photo) {
            $arr_file = array(
                "name" => $photo['name'],
                "size" => $photo['size'],
                "tmp_name" => $photo['tmp_name'],
                "type" => "image/jpeg",
                "old_file" => "",
                "del" => "Y",
                "MODULE_ID" => ""
            );
            $fid = CFile::SaveFile($arr_file, 'album');
            $fileData = CFile::MakeFileArray($fid);
            $fileData["MODULE_ID"] = "iblock";
            $this->arResult['RESULT'][] = $fileData;
            $arFilter = array("IBLOCK_ID" => 19, "PROPERTY_UID" => $USER->GetID(),);
            $rsItems = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, array());
            if (!empty($arItem = $rsItems->fetch())) {
                CIBlockElement::SetPropertyValueCode($arItem['ID'], "PHOTOS", array("VALUE" => $fileData));

            } else {
                $id = IBlockInterface::createElement(19, ['NAME' => $USER->GetID(), "CODE" => $USER->GetID(), "IBLOCK_ID" => 19]);
                CIBlockElement::SetPropertyValueCode($id, "PHOTOS", array("VALUE" => $fileData));
                CIBlockElement::SetPropertyValueCode($id, "UID", $USER->GetID());

            }
            $this->arResult['id'] = $id;

            $this->arResult['!!!'] = IBlockInterface::getElementProperties(19, 875);
        }
    }

    function reArrayFiles(&$file_post) {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    private function deleteAlbumItem($data){

        global $USER;
        $arFilter = array( "IBLOCK_ID" => 19, "PROPERTY_UID" => $USER->GetID(), );
        $rsItems = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, false, Array());
        if (!empty($arItem = $rsItems->fetch())) {
            $ELEMENT_ID = $arItem['ID']; // ID элемента инфоблока
            $propertyCode = "PHOTOS"; // Код множественного свойства

            // Получение свойств элемента инфоблока
            $arElement = CIBlockElement::GetByID($ELEMENT_ID)->GetNext();
            $arProperties = [];
            if ($arElement) {
                $db_props = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $ELEMENT_ID, "sort", "asc", array("CODE" => $propertyCode));
                while ($ar_props = $db_props->Fetch()) {
                    $arProperties[] = $ar_props['VALUE'];
                }
                $this->arResult['!!!'][]=$arProperties;
                // Идентификатор файла, который нужно удалить
                $fileIdToRemove = $data["PHOTO_ID"]; // ID файла, который нужно удалить
                // Удаление идентификатора файла из списка значений свойства
                $key = array_search($fileIdToRemove, $arProperties);
                if ($key !== false) {
                    unset($arProperties[$key]);
                }
                $this->arResult['!!!'][]=$arProperties;
                $this->arResult['!!!'][]=$fileIdToRemove;

                // Сохранение обновленного значения свойства "FILES" для данного элемента инфоблока
                $fileArrays=[];
                foreach ($arProperties as $ids){
                    $fileArrays[] = CFile::MakeFileArray($ids);
                }
                CIBlockElement::SetPropertyValuesEx($ELEMENT_ID, false, array($propertyCode => $fileArrays));
            }
        }

    }


}
