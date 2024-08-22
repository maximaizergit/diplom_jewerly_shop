<?php



use Bitrix\Main,
    Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\SiteTable,
    Bitrix\Main\UserTable,
    Bitrix\Main\Config\Option,
    Bitrix\Sale;
require  $_SERVER["DOCUMENT_ROOT"] .'/bitrix/simpleHtmlDom/simple_html_dom.php';
class parser
{
    private $request;

    public function __construct(){
        $this->request=\Bitrix\Main\Context::getCurrent()->getRequest();
        CModule::IncludeModule("sale");
    }

    /**
     * @return \Bitrix\Main\HttpRequest|\Bitrix\Main\Request
     */

    public $url;
    public $tags = [];
    public $codes = [];
    public $processedTags = [];
    public $catalogClass;
    public $filterLinks;

    public $links = [];
    public $items =[];
    public function prepareResult(){

    }

    public function importToIblock($iID, $items, $section){
        if (!\Bitrix\Main\Loader::includeModule("iblock"))
            return;

        if (!Bitrix\Main\Loader::includeModule('catalog'))
            return;

        $iblockElement = new CIBlockElement;
        $itemsCode = array_column($items, 'code',null);

        $processedCodes = [];

        foreach ($itemsCode as $code){
            $processedCodes[] = CUtil::translit($code, 'ru', [
                'max_len' => 100,
                'change_case' => 'L',
                'replace_space' => '-',
                'replace_other' => '-',
                'delete_repeat_replace' => true,
            ]);
        }

        $sectionId = null;
        if ($section) {
            $sectionId = CIBlockFindTools::GetSectionID(
                null,
                $section,
                array('GLOBAL_ACTIVE' => 'Y')
            );
            if (!$sectionId) {
                echo 'Раздел не существует';
            }
        }

        $existingElement = CIBlockElement::GetList([], [
            'IBLOCK_ID' => $iID,
            'CODE' => $processedCodes,

        ], false,false, ['ID','NAME','CODE']);

        $debugArr=[];
        while($ob = $existingElement->GetNext()){
            $debugArr[]=$ob['CODE'];
        }

        if ($existingElement) {
            $this->arResult['debug'][] =$existingElement;
        }

        foreach ($items as $item) {
            $props = [];
            $translitedCode = CUtil::translit($item['code'], 'ru', [
                'max_len' => 100,
                'change_case' => 'L',
                'replace_space' => '-',
                'replace_other' => '-',
                'delete_repeat_replace' => true,
            ]);
            $fields = [
                'IBLOCK_ID' => $iID,
                'NAME' => $item['name'],
                'CODE' => $translitedCode,
                'ACTIVE' => 'Y',
                'VAT_ID' => 3, // ID ставки НДС
                'VAT_INCLUDED' => 'Y', // НДС включен в цену
            ];
            if (in_array($translitedCode,$debugArr) ){
                $this->updateCatalogElement($item, $translitedCode, $sectionId);
                continue;
            }


            $price = null;

            foreach ($item as $key => $value) {
                if ($key === 'name' || $key === 'code' ) {
                    continue;
                }
                if ($key === 'img'){
                    $fields['PREVIEW_PICTURE'] = CFile::MakeFileArray($value);
                    continue;
                }
                if($key === 'price'){
                    $price = $value;
                    continue;
                }

                if (strpos($key, 'PROPERTY_') === 0) {
                    $propCode = substr($key, strlen('PROPERTY_'));
                    $props[$propCode] = $value;
                } else {
                    $fields[$key] = $value;
                }
            }

            if ($elementId = $iblockElement->Add($fields)) {
                $propertyValues = [];

                foreach ($props as $propCode => $propValue) {
                    $propertyValues[$propCode] = $propValue;
                }
                CIBlockElement::SetPropertyValuesEx($elementId, false, $propertyValues);
                if($sectionId){
                    CIBlockElement::SetElementSection($elementId, array($sectionId));
                }
                if($price){
                    CPrice::SetBasePrice($elementId, $price, 'BYN');
                }
                $this->arResult['importResult']['created'] += 1;
            } else {
                throw new Exception($iblockElement->LAST_ERROR);
            }

        }
    }

    public function updateCatalogElement($item, $code, $sectionId) {
        // Получаем элемент инфоблока по коду
        $element = CIBlockElement::GetList([], ['CODE' => $code])->Fetch();
        if ($element) {
            $elementId = $element['ID'];

            // Обновляем свойства и поля элемента
            $price=null;
            $fields = [
                'PRICE_VAT_INCLUDE' =>'Y',
            ];
            foreach ($item as $key => $value) {
                if ($key === 'name' || $key === 'code') {
                    continue;
                }
                if ($key === 'img') {
                    $fields['PREVIEW_PICTURE'] = CFile::MakeFileArray($value);
                    continue;
                }
                if($key === 'price'){
                    $price = $value;
                    continue;
                }

                if (strpos($key, 'PROPERTY_') === 0) {
                    $propCode = substr($key, strlen('PROPERTY_'));
                    $props[$propCode] = $value;
                } else {
                    $fields[$key] = $value;
                }
            }

            CIBlockElement::SetPropertyValuesEx($elementId, false, $props);
            $vat=Array("VAT_INCLUDED"=>'Y');
            CCatalogProduct::Update($elementId,$vat);
            if($sectionId){
                CIBlockElement::SetElementSection($elementId, array($sectionId));
            }
            if($price){
                CPrice::SetBasePrice($elementId, $price, 'BYN');
            }
            $this->arResult['importResult']['updated'] += 1;
        } else {
            throw new Exception('Товар не найден.');
        }
    }


    public function mergeCodesAndItems($items, $codes) {


            $mergedItems =[];
            foreach ($items as $item) {
                if (count($item) != count($codes)){
                    $this->arResult["ERROR"]='Ошибка в запросе, полученное количество элементов не равно заданым кодам '.count($items) . ' !='.count($codes);
                    return $items;
                }
                $item = array_combine($codes, $item);
                $mergedItems[] =$item;
            }
            unset($item); // Очистка ссылки на последний элемент массива

            return $mergedItems;




    }

    public function parseDetailPage($url) {
        $html = file_get_html($url);
        $result = [];

        foreach ($this->processedTags as $item) {
            $type = $item['type'];

            if ($type === 'classSearch') {


                $class = $item['class'];
                $element = $html->find($class, 0);

                if ($element) {
                    $value = $element->plaintext;

                    if (isset($item['format']) && $item['format'] === 'int') {
                        preg_match('/\d+\.\d+/', $value, $matches);
                        $value = isset($matches[0]) ? (float) $matches[0] : null;
                    }

                    if ($item['format'] == 'img') {
                        $image = $element->find('img', 0);
                        $imageUrl = $image->src;

                        $value = $imageUrl;
                    }

                    $result[] = $value;
                }
            } elseif ($type === 'idSearch') {
                $id = $item['id'];
                $element = $html->find($id, 0);
                if ($element) {
                    $value = $element->plaintext;
                    if (isset($item['format']) && $item['format'] === 'int') {
                        preg_match('/\d+\.\d+/', $value, $matches);
                        $value = isset($matches[0]) ? (float) $matches[0] : null;
                    }
                    if ($item['format'] == 'img') {
                        $image = $element->find('img', 0);
                        $imageUrl = $image->src;

                        $value = $imageUrl;
                    }

                    $result[] = $value;
                }
            } elseif ($type === 'complexSearch') {
                $class = $item[0]['class'];
                $prop = $item[0]['prop'];
                $valueClass = $item[0]['valueClass'];

                foreach ($html->find($class) as $element) {

                    if ($element->plaintext === $prop) {

                        $siblingElement = $element->next_sibling();
                        if ($siblingElement && $siblingElement->class === $valueClass) {
                            $value = $siblingElement->plaintext;
                            if (isset($item['format']) && $item['format'] === 'int') {
                                preg_match('/\d+\.\d+/', $value, $matches);
                                $value = isset($matches[0]) ? (float) $matches[0] : null;
                            }

                            $result[] = $value;
                            break;
                        }
                    }
                }
            }
            if ( $this->processedTags['config']['getCode']){
                $parts = explode('/', $url);
                $lastPart = end($parts);
                $result['code'] = $lastPart;

            }

        }

        return $result;
    }

    public function processText($text) {
        $lines = explode("\n", $text);
        $result = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            if ($line === '<json>') {
                $jsonStart = true;
                $jsonData = '';
                continue;
            }


            if ($line === '</json>') {
                $jsonStart = false;
                $array = json_decode($jsonData, true);
                $array['type'] = 'complexSearch';
                $result[] = $array;
                continue;
            }

            if (isset($jsonStart) && $jsonStart) {
                $jsonData .= $line;
                continue;
            }

            $format = null;
            if (preg_match('/^(.*) \(int\)$/', $line, $matches)) {
                $line = trim($matches[1]);
                $format = 'int';
                $line = str_replace(' (int)', '', $line);
            }
            if (preg_match('/^(.*) \(img\)$/', $line, $matches)) {
                $line = trim($matches[1]);
                $format = 'img';
                $line = str_replace(' (img)', '', $line);
            }

            if (strpos($line, '.') === 0) {
                $result[] = ['class' => $line, 'type' => 'classSearch', 'format' => $format];
            } elseif (strpos($line, '#') === 0) {
                $result[] = ['id' => $line, 'type' => 'idSearch', 'format' => $format];
            }

            if ($line === 'getCode()') {
                $result['config']=['getCode' => 'true'];
            }

        }

        return $result;
    }


    public function parseUrl() {
        $response = file_get_contents($this->url);
        if ($response !== false) {
            $html = new simple_html_dom();
            $html->load($response);
            $res = [];
            $baseUrl = $this->url;
            foreach ($html->find($this->catalogClass . ' a') as $link) {
                $href = $link->href;
                if (strpos($href, '/') === 0) {
                    $res[] = parse_url($baseUrl, PHP_URL_SCHEME) . '://' . parse_url($baseUrl, PHP_URL_HOST) . $href;
                } else {
                    $res[] = $href;
                }
            }

            $res = array_unique($res);

            if (!empty($this->filterLinks)){
                $stringWithoutSpaces = str_replace(' ', '', $this->filterLinks);
                $filterLinks = explode(',', $stringWithoutSpaces);
                $res = array_filter($res, function($link) use ($filterLinks) {
                    foreach ($filterLinks as $filterLink) {
                        if (strpos($link, $filterLink) !== false) {
                            return false;
                        }
                    }
                    return true;
                });
            }



            $this->links = $res;
            $this->arResult['links'] = $this->links;
        } else {
            $this->arResult['ERROR'] = 'Не удалось получить информацию по данной ссылке.';
        }
    }
    public function validateTags(){
        if (count($this->tags)==0){
            return 'Введите хоть 1 тег';
        }
        if (count($this->tags) != count($this->codes)){
            return 'Кол-во тегов не равно количеству кодов'.count($this->tags)." ".count($this->codes);
        }

        return null;
    }

    public function convertStringToArray($string) {
        $stringWithoutSpaces = str_replace(' ', '', $string);
        $array = explode(',', $stringWithoutSpaces);
        return $array;
    }
}
