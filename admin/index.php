<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/csv_data.php");

$APPLICATION->SetTitle("admin");
// Обработчик кнопки
CModule::IncludeModule('iblock');
// Обработчик GET запроса

    // Получаем все товары каталога
    $arFilter = array('IBLOCK_ID' => 6); // Замените 1 на ID вашего инфоблока каталога
    $arSelect = array('ID', 'NAME', 'DETAIL_PAGE_URL'); // Укажите нужные свойства
    $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    $products = [['ID','NAME','URL']];

    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $productData = array(
            'ID' => $arFields['ID'],
            'NAME' => $arFields['NAME'],
            'URL' => $arFields['DETAIL_PAGE_URL'],
        );

        $products[] = $productData;
    }

    header("Content-Description: File Transfer\r\n");
    header("Pragma: public\r\n");
    header("Expires: 0\r\n");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0\r\n");
    header("Cache-Control: public\r\n");
    header("Content-Type: text/plain; charset=UTF-8\r\n");
    header("Content-Disposition: attachment; filename=\"myfile.csv\"\r\n");
$APPLICATION->RestartBuffer();
    $buffer = fopen('php://output', 'w');
    foreach($products as $val) {
        fputcsv($buffer, $val, ';');
    }
    fclose($buffer);
    die();




?>

    <a href="?exportCatalog">Text here....</a>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>