<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>


<? if ($arResult["NAV_RESULT"]): ?>
    <? if ($arParams["DISPLAY_TOP_PAGER"]): ?><?= $arResult["NAV_STRING"] ?><br/><? endif; ?>
    <? echo $arResult["NAV_TEXT"]; ?>
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?><br/><?= $arResult["NAV_STRING"] ?><? endif; ?>

<? endif ?>

<?php // Получение текущего ID новости или элемента
$currentItemId = $arResult['ID']; // Замените на ваш код получения ID

// Запрос к инфоблоку для получения оценок
$ratings = [];
$filter = [
    'IBLOCK_ID' => 16, // ID инфоблока с оценками
    'PROPERTY_ITEM_ID' => $currentItemId,
];
$select = ['PROPERTY_RATING'];
$result = CIBlockElement::GetList([], $filter, false, false, $select);
while ($rating = $result->Fetch()) {
    $ratings[] = $rating['PROPERTY_RATING_VALUE'];
}

// Вычисление среднего арифметического
$averageRating = count($ratings) > 0 ? array_sum($ratings) / count($ratings) : 0;

// Округление до ближайшего целого значения
$roundedRating = round($averageRating);


$reviewsFilter = array(
    "IBLOCK_CODE" => "newsComments",
    "PROPERTY_ITEM_ID" => $arResult['ID']
);

$reviewsSelect = array(
    "ID",
    "NAME",
    "PROPERTY_USER_NAME",
    "DETAIL_TEXT",
    "DATE_CREATE"
);


$reviewsResult = CIBlockElement::GetList(
    array("DATE_CREATE" => "DESC"), // сортировка по убыванию даты создания
    $reviewsFilter,
    false,
    false,
    $reviewsSelect
);
$reviews = [];
while ($reviews[] = $reviewsResult->GetNext()) {
}
$avgRating = 5;

if ($reviews){

$reviews = array_filter($reviews, function ($subarray) {
    return !empty($subarray);
});

?>

<? foreach ($arResult["FIELDS"] as $code => $value):
    if ('PREVIEW_PICTURE' == $code || 'DETAIL_PICTURE' == $code) {
        ?><?= GetMessage("IBLOCK_FIELD_" . $code) ?>:&nbsp;<?
        if (!empty($value) && is_array($value)) {
            ?><img border="0" src="<?= $value["SRC"] ?>" width="<?= $value["WIDTH"] ?>"
                   height="<?= $value["HEIGHT"] ?>"><?
        }
    } else {
        ?><?= GetMessage("IBLOCK_FIELD_" . $code) ?>:&nbsp;<?= $value; ?><?
    }
    ?><br/>
<?endforeach;


?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="blog-post-area">
                    <div class="single-blog-post">
                        <h3><?= $arResult["NAME"] ?></h3>
                        <div class="post-meta">
                            <ul>
                                <li><i class="fa fa-clock-o"></i> <?= $arResult["DISPLAY_ACTIVE_FROM"] ?></li>
                            </ul>

                        </div>
                        <a href="">
                            <img src="<?= $arResult["DETAIL_PICTURE"]['SRC'] ?>" alt="">
                        </a>
                        <p>
                            <?= $arResult["PREVIEW_TEXT"]; ?>
                        <p>
                            <?= $arResult["DETAIL_TEXT"]; ?>
                        </p>

                    </div>
                </div><!--/blog-post-area-->

                <div class="rating-area">

                    <ul class="ratings">
                        <li class="rate-this">Rate this item:</li>

                        <li>
                            <div class="rating">
                                <?
                                // Установка атрибута "checked" для соответствующего инпута
                                for ($i = 5; $i >= 1; $i--) {
                                    $checked = $i == $roundedRating ? 'checked' : '';
                                    echo '<input type="radio" name="rating" id="star' . $i . '" value="' . $i . '" ' . $checked . '><label for="star' . $i . '">&#9733;</label>';
                                }
                                ?>
                            </div>
                        </li>
                    </ul>
                </div>
                <?
                if (!$USER->IsAuthorized()) {
                    echo "Авторизируйтесь чтобы поставить оценку и писать комментарии";
                } else {
                    ?>
                    <script>
                        const ratingInputs = document.querySelectorAll('input[name="rating"]');
                        let selectedRating = 5;
                        let userId = <?= $USER->GetID();?>;

                        ratingInputs.forEach(input => {
                            input.addEventListener('click', () => {
                                selectedRating = input.value;
                                console.log(`Selected rating: ${selectedRating}`);

                                console.log('qwe');

                                var itemId = '<?php echo $arResult["ID"]; ?>'; // Получаем значение переменной arResult['ID']

                                // Отправляем AJAX-запрос на сервер
                                $.ajax({
                                    type: 'POST',
                                    url: '/local/ajax/sendBlogRating.php',
                                    data: {itemId: itemId, rating: selectedRating, userId: userId},
                                    success: function (res) {
                                        console.log(res); // Выводим ответ сервера в консоль
                                    },
                                    error: function () {
                                        console.log('Ошибка отправки запроса'); // Выводим сообщение об ошибке в консоль
                                    }
                                });
                            });
                        });
                    </script>
                    <?
                } ?>

            </div><!--/rating-area-->

            <!--Comments-->
            <div class="response-area">
                <h2><?= count($reviews) ?> RESPONSES</h2>
                <ul class="media-list">
                    <?
                    foreach ($reviews as $review) {

                        ?>
                        <li class="media">
                            <div class="media-body">
                                <ul class="sinlge-post-meta">
                                    <li><i class="fa fa-user"></i><?= $review['PROPERTY_USER_NAME_VALUE'] ?></li>
                                    <li><i class="fa fa-clock-o"></i> <?= $review['DATE_CREATE'] ?></li>
                                </ul>
                                <p><?= $review['DETAIL_TEXT'] ?></p>
                            </div>
                        </li>
                        <?
                    }
                    ?>
                </ul>
            </div><!--/Response-area-->
            <?
            }
            ?>

            <?
            if ($USER->IsAuthorized()) {
                $currentUserName = $USER->GetFullName();
                ?>
                <div class="replay-box">
                    <div class="row">

                        <form id="replay-form">
                            <div class="col-sm-8">
                                <div class="text-area">
                                    <div class="blank-arrow">
                                        <label>Your comment</label>
                                    </div>
                                    <span>*</span>
                                    <textarea name="message" id="reply" rows="11"></textarea>
                                    <input type="submit" class="btn btn-primary" href="">
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!--/Repaly Box-->
                <?
            } ?>
        </div>
    </div>
    </div>
</section>

<script>
    // Обработчик события submit на форме
    $('#replay-form').on('submit', function (event) {
        event.preventDefault(); // Отменяем стандартное поведение браузера
        console.log('hello world');
        var itemId = '<?php echo $arResult["ID"]; ?>'; // Получаем значение переменной arResult['ID']
        let userName = '<?= $currentUserName?>';
        let comment = document.getElementById('reply').value;
        console.log(comment);
        $.ajax({
            type: 'POST',
            url: '/local/ajax/addNewsComment.php',
            data: {itemId: itemId, comment: comment, userName: userName},
            success: function (res) {
                console.log(res); // Выводим ответ сервера в консоль
            },
            error: function () {
                console.log('Ошибка отправки запроса'); // Выводим сообщение об ошибке в консоль
            }
        });
    });
</script>