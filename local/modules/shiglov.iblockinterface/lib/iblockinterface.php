<?php
namespace Shiglov\IBlock;
\CModule::IncludeModule("iblock");
\CModule::IncludeModule("sale");
class IBlockInterface
{
    //CREATE

    public static function createElement($iblockId, array $fields=["NAME"=>'Untitled', "Code"=>'Untitled'])
    {
        // Подготавливаем основные данные
        $el = new \CIBlockElement;
        $propertyValues = $fields['PROPERTIES'];
        unset($fields['PROPERTIES']);

        $id = $el->Add($fields);

        if(!$id) {
            return false;
        }

        \CIBlockElement::SetPropertyValuesEx($id, $iblockId, $propertyValues);

        if(isset($fields['PRICE'])) {
            self::setPrice($id, $fields['PRICE']);
        }
        if(isset($fields['QUANTITY'])) {
            self::setQuantity($id, $fields['QUANTITY']);
        }

        return $id;
    }


    //READ
    public static function getElementAllData($id)
    {

        if ($result = self::getElement($id)) {
            $iblockId = \CIBlockElement::GetIBlockByID($id);
            $properties = self::getElementProperties($iblockId, $id);
            $ar_res = \CCatalogProduct::GetByID($id);

            $result['QUANTITY'] = $ar_res['QUANTITY'];
            $result['PROPERTIES'] = $properties;
            $result['PRICE'] = self::getElementPrice($id);
            return $result;
        }

        return false;
    }

    public static function getElement($id)
    {
        $arSelect = array('*');
        $arFilter = array('ID' => $id);
        $res = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        if ($result = $res->GetNextElement()) {
            return (array)$result;
        }

        return false;

    }
    public static function getElements(array $filter, array $select = [],$limit = 0, $order = [], $price=false, $allprops=false )
    {

        $arNavParams = array();
        if ($limit > 0) {
            $arNavParams['nTopCount'] = $limit;
        }

        if($filter['PAGE'] && $filter["PAGE_COUNT"]){
            $arNavParams = array();
            $arNavParams['nPageSize'] = $filter["PAGE_COUNT"];
            $arNavParams['iNumPage'] = $filter['PAGE'];
        }
        $res = \CIBlockElement::GetList(
            $order,
            $filter,
            false,
            !empty($arNavParams)? $arNavParams: false,
            $select ? $select : array('ID', 'NAME')
        );

        $result = [];
        while($ob = $res->GetNextElement()) {
            $fields = $ob->GetFields();
            if ($price){
                $fields['PRICE']=self::getElementPrice($fields['ID']);
            }
            if($allprops){
                $fields['PROPERTIES']=self::getElementProperties(\CIBlockElement::GetIBlockByID($fields['ID']),$fields['ID']);

            }
            $result[] = $fields;
        }

        return $result;
    }
    public static function getElementProperties($iblockId, $elementId)
    {
        $properties = \CIBlockElement::GetProperty($iblockId, $elementId);

        $props = array();
        while ($arProp = $properties->GetNext()) {
            $props[$arProp['CODE']] = $arProp['VALUE'];
        }

        return $props;
    }

    public static function getElementPrice($elementId)
    {
        $arSelect = array('ID', 'CATALOG_PRICE_1', 'CATALOG_CURRENCY_1');

        $arFilter = array('ID' => $elementId);

        $res = \CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        $arPrice = array();
        if ($ob = $res->GetNextElement()) {

            $fields = $ob->GetFields();
            $arPrice['PRICE'] = $fields['CATALOG_PRICE_1'];
            $arPrice['CURRENCY'] = $fields['CATALOG_CURRENCY_1'];
            $arPrice['CATALOG_QUANTITY'] = $fields['CATALOG_QUANTITY'];
            return $arPrice;
        }
        return false;
    }

    public static function getElementsPage($filter,$select=['*'], $page,$order=[], $limit = 50, $showPrice = false, $showProps = false) {
       // $offset = ($page - 1) * $limit;
        $filter['PAGE_COUNT'] = $limit;
        $filter['PAGE'] = $page;

        $elements = self::getElements($filter, $select, $limit,$order, $showPrice,$showProps);

        return $elements;
    }



    //UPDATE
    public static function updateElementField($id, $field, $value)    {
        $el = new \CIBlockElement;
        $arLoadProductArray = array(
            "ID"       => $id,
            $field => $value,
        );

        $res = $el->Update($id, $arLoadProductArray);
        return $res;
    }

    public static function setQuantity($id, $quantity)
    {
        $arFields = array(
            "ID" => $id,
            "QUANTITY" => $quantity,
        );
        if(\CCatalogProduct::Add($arFields)){
            return self::getElementPrice($id);
        }
        return false;
    }

    public static function setPrice($id, $price,$currency='BYN')
    {
        $priceFields = array(
            "PRODUCT_ID" => $id,
            "CATALOG_GROUP_ID" => 1, // ID типа цены (может отличаться в вашей установке)
            "PRICE" => $price,
            "CURRENCY" => $currency, // Валюта
        );
        $res = \CPrice::GetList(
            array(),
            array(
                "PRODUCT_ID" => $id,
                "CATALOG_GROUP_ID" => 1
            )
        );
        if ($arr = $res->Fetch())
        {
            \CPrice::Update($arr["ID"], $priceFields);
        }
        else
        {
            \CPrice::Add($priceFields);
        }
        return self::getElementPrice($id);
    }

    public static function setProps($id, $props)
    {
        \CIBlockElement::SetPropertyValuesEx($id, false, $props);
        return self::getElementProperties(\CIBlockElement::GetIBlockByID($id),$id);
    }

    public static function setSection($id, $sectionId)
    {
        $result = self::updateElementField($id, 'IBLOCK_SECTION_ID', $sectionId);
        return $result ?: false;
    }

    public static function setFields($id, array $fields)
    {
        $el = new \CIBlockElement();
        $dbElement = $el->GetByID($id)->Fetch();

        if(!$dbElement) {
            return false;
        }

        $updateFields = [
            "ID" => $id
        ];

        foreach($fields as $code => $value) {
            $updateFields[$code] = $value;
        }

        $result = $el->Update($id, $updateFields);

        return $result;
    }

    public static function setElementsPrice(array $idAndPrice) {
        foreach($idAndPrice as $element) {
            self::setPrice($element['ID'], $element['PRICE']);
        }
    }

    public static function setElementsProps(array $idAndProps) {
        foreach($idAndProps as $element) {
            self::setProps($element['ID'], $element['PROPERTIES']);
        }
    }

    public static function setElementsFields(array $idAndFields) {
        foreach($idAndFields as $element) {
            self::setProps($element['ID'], $element['FIELDS']);
        }
    }

    public static function setElementsSections(array $idAndSection) {
        foreach($idAndSection as $element) {
            self::setSection($element['ID'], $element['SECTION_ID']);
        }
    }

    public static function activateElements(array $elementIds) {
        self::updateElementField($elementIds,'ACTIVE' ,'Y');
    }

    public static function diactivateElements(array $elementIds) {
        self::updateElementField($elementIds,'ACTIVE' , 'N');
    }


    //DELETE
    public static function deleteElement($id)
    {
        $el = new \CIBlockElement;
        $dbElement = $el->GetByID($id)->Fetch();

        if(!$dbElement) {
            return false;
        }

        $result = $el->Delete($id);

        if(!$result) {
            return false;
        }

        \CPrice::Delete($id);

        return true;
    }

    public static function deleteElements(array $elementIds) {
        foreach($elementIds as $id) {
            self::deleteElement($id);
        }
    }

}
