<?php


use Bitrix\Main,
    Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\SiteTable,
    Bitrix\Main\UserTable,
    Bitrix\Main\Config\Option,
    Bitrix\Sale;
use \Shiglov\IBlock\IBlockInterface;
CModule::IncludeModule("shiglov.iblockinterface");

class iblocktranslate
{
    /**
     * @return \Bitrix\Main\HttpRequest|\Bitrix\Main\Request
     */
    private $request;
    public $url;
    public $key;
    public $elements;

    public function __construct($url)
    {
        $this->request = \Bitrix\Main\Context::getCurrent()->getRequest();
        CModule::IncludeModule("iblock");
        $this->url = $url;
    }

    function translate($text)
    {

        $body = [
            'q' => $text,
            'target' => 'en',
        ];
        // Инициализируем cURL-сессию
        $ch = curl_init($this->url.$this->key);


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);

        curl_close($ch);

        $data = json_decode($response, true);
        return $data['data']['translations'][0]['translatedText'];
    }

    public function dump($text)
    {
        return $text;
    }

    public function getElements($filter){
        $els = IBlockInterface::getElements(json_decode($filter,true),
            array("ID", "NAME", "CODE", "PREVIEW_TEXT", "DETAIL_TEXT"),
            0,
            [],
            true,
            true);

        return $els;
    }

    public function getCount($filter,$ibLock){

        if(intval($ibLock)!=0 && intval($ibLock)!=null){
            $filter = json_decode($filter,true);
            $filter["IBLOCK_ID"]=$ibLock;
            $cnt = CIBlockElement::GetList(
                array(),
                $filter,
                array(),
                false,
                array('ID', 'NAME')
            );
            return $cnt;
        }

        return 'Ошибка выбора инфоблока';
    }

    public function copyElements($elements, $targetIB){

        $successCounter = 0;
        $errorCounter = 0;
        $errors = [];
        $this->arResult['debug'][] = $elements;
        if (empty($this->key)){
            $errorCounter++;
            $errors[]='Отсутствует ключ';

            return ['Success' => $successCounter, "Errors" => $errorCounter, "Errors logs" => $errors];
        }
        if(intval($targetIB)!=0 && intval($targetIB)!=null) {

            foreach ($elements as $el) {

                $translatedName = $this->translate($el["NAME"]);

                $fields = array(
                    "IBLOCK_SECTION_ID" => false,
                    "IBLOCK_ID" => $targetIB,
                    "NAME" => $translatedName,
                    "CODE" => $el['CODE'] . '_en',
                    "ACTIVE" => "Y",
                    // Добавьте другие поля по аналогии
                );



                if (!empty($el['PREVIEW_TEXT'])) {
                    $fields["PREVIEW_TEXT"] = $this->translate($el['PREVIEW_TEXT']);
                }
                if (!empty($el['DETAIL_TEXT'])) {
                    $fields["DETAIL_TEXT"] = $this->translate($el['DETAIL_TEXT']);
                }

                $newel = new CIBlockElement;

                if ($elementId = $newel->Add($fields, false, false, true)) {
                    // Установка свойств элемента
                    $props = array();
                    foreach ($el['PROPERTIES'] as $key=>$value){
                        $props[$key]=$value;
                    }


                    CIBlockElement::SetPropertyValuesEx($elementId, $targetIB, $props);
                    IBlockInterface::setPrice($elementId,$el['PRICE']['PRICE']);
                    IBlockInterface::setQuantity($elementId,$el['PRICE']['CATALOG_QUANTITY']);
                    $successCounter++;
                } else {
                    $errorCounter++;
                    $errors[] = $newel->LAST_ERROR;
                }
            }

            return ['Success' => $successCounter, "Errors" => $errorCounter, "Errors logs" => $errors];
        }
        $errorCounter++;
        $errors[]='Ошибка выбора инфоблока для загрузки';

        return ['Success' => $successCounter, "Errors" => $errorCounter, "Errors logs" => $errors];
    }


}
