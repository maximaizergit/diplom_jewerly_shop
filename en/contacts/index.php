<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Контакты");

use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
?><div class="container" style="min-height: 600px;">
    <div class="row">
        <div class="col-sm-12">
            <h2 class="title text-center">Contact <strong>Us</strong></h2>
        </div>
    </div>

        <?

        // Подключение модуля инфоблоков
        Bitrix\Main\Loader::includeModule('iblock');


        $iblockId = 7;//УБРАТЬ ID
        $arFilter = array(
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
        );
        $arSelect = array('NAME', 'DETAIL_TEXT', 'PREVIEW_TEXT', 'ID', 'PROPERTY_NUMBER', 'PROPERTY_LOCATION');
        $rsItems = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);

        $addresses = array();

        while ($arItem = $rsItems->Fetch()) {
            $coordinates = $arItem['PROPERTY_LOCATION_VALUE'];
            $coordinatesArray = explode(',', $coordinates);
            $addresses[] = array('ID'=>$arItem['ID'],'NAME'=>$arItem['NAME'],
                'COORDINATES'=>$coordinatesArray,
                    'NUMBER'=>$arItem['PROPERTY_NUMBER_VALUE']) ;
        }


        ?>
        <script> var adresses =<?=CUtil::PhpToJSObject($addresses)?>;
        console.log(adresses)</script>
        <script src="https://api-maps.yandex.ru/2.1/?apikey=e8a8cc1b-7d22-4c5e-b0fd-bb7f7cb48965&lang=ru_RU" type="text/javascript"></script>
        <script type="text/javascript">
            ymaps.ready(init);

            function init() {
                var myMap = new ymaps.Map("map", {
                    center: [53.919217, 27.500327], // Координаты центра карты (например, Москва)
                    zoom: 12 // Уровень масштабирования карты
                });

                adresses.forEach(async function (placemark) {

                    if(placemark.COORDINATES){
                        var marker = new ymaps.Placemark(placemark.COORDINATES, {
                            balloonContent: placemark.NAME+ "<br>Телефон: " + placemark.NUMBER
                        });
                    }


                    myMap.geoObjects.add(marker);
                });

                $("#shop-select").on("select2:select", function (e) {
                    var selectedShop = e.params.data.text;
                    console.log('select2');
                    // Найти метку выбранного магазина и отобразить ее на карте
                    adresses.forEach(function (placemark) {
                        if (placemark.NAME === selectedShop) {
                            myMap.setCenter(placemark.COORDINATES, 15); // Переместить карту к выбранной метке
                        }
                    });
                });
            }



        </script>

        <br>
        <select id="shop-select">
            <option value="">Select shop</option>
            <? foreach ($addresses as $adress){ ?>
                <option value="<?=$adress['NAME']?>"><?=$adress['NAME']?></option>
            <? } ?>
        </select>
    <div id="map" style="width: 100%; height: 400px;"></div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script> $(document).ready(function() {
        $('#shop-select').select2();
    });</script>

    <div class="bg">

        <div class="row">
            <div class="col-sm-8">
                <div class="contact-form">
                    <h2 class="title text-center">Get In Touch</h2>
                    <div class="status alert alert-success" style="display: none"></div>
                    <form id="main-contact-form" class="contact-form row" name="contact-form" method="post">
                        <div class="form-group col-md-6">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Name">
                        </div>
                        <div class="form-group col-md-6">
                            <input type="email" name="email" class="form-control" required="required" placeholder="Email">
                        </div>
                        <div class="form-group col-md-12">
                            <input type="text" name="subject" class="form-control" required="required" placeholder="Subject">
                        </div>
                        <div class="form-group col-md-12">
                            <textarea name="message" id="message" required="required" class="form-control" rows="8" placeholder="Your Message Here"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <input type="submit" name="submit" class="btn btn-primary pull-right" value="Submit">
                        </div>
                    </form>
                    <script>
                        // Обработчик события отправки формы
                        $("#main-contact-form").submit(function(event) {
                            event.preventDefault(); // Отмена стандартной отправки формы

                            // Сбор данных формы
                            var formData = {
                                name: $("input[name=name]").val(),
                                email: $("input[name=email]").val(),
                                subject: $("input[name=subject]").val(),
                                message: $("textarea[name=message]").val()
                            };

                            // Отправка AJAX-запроса на сервер
                            $.ajax({
                                type: "POST",
                                url: "/local/ajax/contactUs.php", // Замените "URL_ВАШЕГО_СЕРВЕРА" на адрес вашего сервера
                                data: formData,
                                success: function(response) {
                                    console.log(JSON.parse(response));
                                }
                            });
                        });

                    </script>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="contact-info">
                    <h2 class="title text-center">Contact Info</h2>
                    <address>
                        <p>E-Shopper Inc.</p>
                        <p>935 W. Webster Ave New Streets Chicago, IL 60614, NY</p>
                        <p>Newyork USA</p>
                        <p>Mobile: +2346 17 38 93</p>
                        <p>Fax: 1-714-252-0026</p>
                        <p>Email: info@e-shopper.com</p>
                    </address>
                    <div class="social-networks">
                        <h2 class="title text-center">Social Networking</h2>
                        <ul>
                            <li>
                                <a href="#"><i class="fa fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa fa-youtube"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>