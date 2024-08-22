<?php

namespace Shiglov\IBlock;

use Bitrix\Main\Loader;

Loader::includeModule("highloadblock");

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;



class HighloadBlockInterface
{


    //CREATE

    public static function createElement($iblockId, array $data = ["UF_STRING" => 'Untitled'])
    {
        $hlblock = HL\HighloadBlockTable::getById($iblockId)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();


        $result = (array)$entity_data_class::add($data);

        return $result;
    }


    //READ

    public static function getElements($id, $select = ['*'], $order = ["ID" => "ASC"], $filter = [])
    {
        if($select==null){
            $select = ['*'];
        }
        if($order==null){
            $order = ["ID" => "ASC"];
        }
        $hlblock = HL\HighloadBlockTable::getById($id)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => $select,
            "order" => $order,
            "filter" => $filter  // Задаем параметры фильтра выборки
        ));

        $result = [];
        while ($arData = $rsData->Fetch()) {
            $result[] = ($arData);
        }
        return $result;

    }


    public static function getElementsPage($id, $select = ['*'], $filter = [], $page = 1, $limit = 50)
    {
        if($select==null){
            $select = ['*'];
        }

        $offset = ($page - 1) * $limit;

        $hlblock = HL\HighloadBlockTable::getById($id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();

        $rsData = $entityDataClass::getList(array(
            "select" => $select,
            "filter" => $filter,
            "order" => array("ID" => "ASC"),
            "limit" => $limit,
            "offset" => $offset
        ));

        $result = [];
        while ($arData = $rsData->Fetch()) {
            $result[] = $arData;
        }

        return $result;
    }

    public static function getEntity($id)
    {
        $hlblock = HL\HighloadBlockTable::getById($id)->fetch();

        return $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    }

    public static function getTotalCount($id, $filter = [])
    {
        $hlblock = HL\HighloadBlockTable::getById($id)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entityDataClass = $entity->getDataClass();

        $totalCount = $entityDataClass::getCount($filter);

        return $totalCount;
    }


    //UPDATE

    public static function setProps($tableId, $elementId, array $data)
    {

        $hlblock = HL\HighloadBlockTable::getById($tableId)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $result = (array)$entity_data_class::update($elementId, $data);

        return $result;
    }

    public static function setElementsProps($tableId, array $idAndData = array(3=>["UF_STRING" => 'qwer'],4=>["UF_STRING" => 'qwer']))
    {

        $hlblock = HL\HighloadBlockTable::getById($tableId)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        $result=[];
        foreach ($idAndData as $key=>$value){
            $res = (array)$entity_data_class::update($key, $value);
            $result[]=$res;
        }

        return $result;
    }


    //DELETE
    public static function deleteElement($tableId, $elementId)
    {
        $hlblock = HL\HighloadBlockTable::getById($tableId)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $result = (array)$entity_data_class::Delete($elementId);
        return $result;
    }
    public static function deleteElements($tableId, $elementsId=[])
    {
        $hlblock = HL\HighloadBlockTable::getById($tableId)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $res =[];
        foreach ($elementsId as $elementId){
            $result = (array)$entity_data_class::Delete($elementId);
            $res[]= $result;
        }

        return $res;
    }


}
