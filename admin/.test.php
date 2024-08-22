<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/csv_data.php");

$APPLICATION->SetTitle("admin");
// Обработчик кнопки

$curl = new CurlServer('AIzaSyB4hJJYbAs5_8fvXtCfqoCBXf2dB3uEIgw');
var_dump($curl->getToken());
echo "</br>";

// Замените 'YOUR_API_KEY' на ваш собственный ключ API
$apiKey = 'AIzaSyB4hJJYbAs5_8fvXtCfqoCBXf2dB3uEIgw';

$texts = ['Привет, мир!', 'Как дела?'];

// Язык, на который вы хотите перевести текст (например, 'en' для английского)
$targetLanguage = 'en';

// Создаем массив cURL-дескрипторов
$curlHandles = [];

// Инициализируем cURL-сессии для каждого текста
foreach ($texts as $text) {
    $url = 'https://translation.googleapis.com/language/translate/v2?key=' . $apiKey;
    $body = [
        'q' => $text,
        'target' => $targetLanguage,
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $curlHandles[] = $ch;
}

// Создаем набор cURL-дескрипторов
$mh = curl_multi_init();

// Добавляем все cURL-дескрипторы в набор
foreach ($curlHandles as $ch) {
    curl_multi_add_handle($mh, $ch);
}

// Выполняем все запросы параллельно
$running = null;
do {
    curl_multi_exec($mh, $running);
} while ($running > 0);

// Получаем результаты и закрываем все cURL-дескрипторы
foreach ($curlHandles as $ch) {
    $response = curl_multi_getcontent($ch);
    $data = json_decode($response, true);
    $translatedText = $data['data']['translations'][0]['translatedText'];
    echo $translatedText . "\n";
    curl_multi_remove_handle($mh, $ch);
    curl_close($ch);
}

// Закрываем набор cURL-дескрипторов
curl_multi_close($mh)

?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>