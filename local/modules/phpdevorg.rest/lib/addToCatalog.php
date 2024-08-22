<?php

class addToCatalog
{

    public function __construct(){

    }

    public function print($string) {

        return $string;
    }

    public function addToCatalog($data)
    {
        $iblockID = $value = COption::GetOptionInt("phpdevorg.rest", "IID", "0");
        // Создание экземпляра класса CIBlockElement
        $el = new CIBlockElement;
        $props = array();
        foreach ($data['props'] as $key => $value){
            $props[$key] = $value;
        }

        if(isset( $data['section_code'])){
            $rsSections = CIBlockSection::GetList(array(),array('IBLOCK_ID' => $iblockID, '=CODE' => $data['section_code']));
            if ($arSection = $rsSections->Fetch())
            {
                $sectionID = $arSection['ID'];
            }
        }else{
            $sectionID = null;
        }

        // Поля нового товара
        $fields = array(
            "IBLOCK_ID" => $iblockID,
            "NAME" =>  $data['name'],
            "CODE" =>  $data['code'],
            "IBLOCK_SECTION_ID" => $sectionID,
            "PROPERTY_VALUES" => $props,
        );

        if (isset($data['img'])){
            $fields['PREVIEW_PICTURE'] = CFile::MakeFileArray($data['img']);
            echo $fields['PREVIEW_PICTURE'] ;
        }
        // Добавление нового товара
        if ($productID = $el->Add($fields)) {
            AddMessage2Log("Товар успешно добавлен. ID: " . $productID, "",false);


            // Установка базовой цены
            if (isset($data['price'])) {
                CModule::IncludeModule('catalog');
                $priceFields = array(
                    "PRODUCT_ID" => $productID,
                    "CATALOG_GROUP_ID" => 1, // ID типа цены (может отличаться в вашей установке)
                    "PRICE" => $data['price'],
                    "CURRENCY" => "BYN", // Валюта
                );
                $res = CPrice::GetList(
                    array(),
                    array(
                        "PRODUCT_ID" => $productID,
                        "CATALOG_GROUP_ID" => 1
                    )
                );
                if ($arr = $res->Fetch())
                {
                    CPrice::Update($arr["ID"], $priceFields);
                }
                else
                {
                    CPrice::Add($priceFields);
                }
            }else{
                echo 'noprice';
            }
            // Установка доступного количества
            if (isset($data['quantity'])) {
                $arFields = array(
                    "ID" => $productID,
                    "QUANTITY" => $data['quantity'],
                );

            }else{
                echo 'no quantity';
            }


            if(CCatalogProduct::Add($arFields))
                AddMessage2Log("Добавили параметры товара к элементу каталога ".$productID, "",false);
            else
                AddMessage2Log('Ошибка добавления параметров'.$productID, "",false);

        } else {
            AddMessage2Log("Ошибка добавления товара: " . $el->LAST_ERROR, "",false);
            echo "Ошибка добавления товара: " . $el->LAST_ERROR;
        }
    }
}
