<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $site
?>

</div>
</div>
</div>
</section>

<footer id="footer"><!--Footer-->
    <h1><?=$site?></h1>
    <div class="footer-top">
        <div class="container">
            <div class="row" style="    display: flex;
    justify-content: space-between;">
                <div class="col-sm-2">
                    <div class="companyinfo">
                        <h2><span>e</span>-shopper</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,sed do eiusmod tempor</p>
                    </div>
                </div>
                <div class="col-sm-3 col-sm-offset-1">
                    <div class="single-widget">
                        <h2><?=GetMessage("ABOUT")?></h2>
                        <form action="#" class="searchform">
                            <input type="text" placeholder="Your email address"/>
                            <button type="submit" class="btn btn-default" id="subForm"><i
                                        class="fa fa-arrow-circle-o-right"></i></button>
                            <p><?=GetMessage("SUBSCRIPTION")?></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-widget">
        <div class="container">
            <div class="row" style="    display: flex;
    justify-content: space-between;">
                <div class="mainmenu pull-left">
                    <ul class="nav navbar-nav collapse navbar-collapse">

                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "top_right_menu",
                            array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "left",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "1",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_TYPE" => "N",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "ROOT_MENU_TYPE" => "topright",
                                "USE_EXT" => "N",
                                "COMPONENT_TEMPLATE" => "top_right_menu"
                            ),
                            false
                        ); ?>

                    </ul>
                </div>


            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                array(
                    "AREA_FILE_SHOW" => "sect",
                    "AREA_FILE_SUFFIX" => "inc3",
                    "EDIT_TEMPLATE" => ""
                )
            ); ?>


        </div>
    </div>



</footer><!--/Footer-->

<script>

    $(document).ready(function () {
        $("a.add-to-cart").click(function () {
            <? if($USER->isAuthorized()){?>
            var dataValue = $(this).data("value");
            console.log($(this).data("value"));
            console.log('test')
            $("a[data-value='" + dataValue + "']").html('<i class="fa fa-check"></i>В корзине!');
            $.ajax({
                type: "POST",
                url: "/local/ajax/add2cart.php",
                data: {
                    id: dataValue,
                    site: '<?=$site?>'
                },
                success: function (response) {

                    console.log(JSON.parse(response));
                }

            });
            <?
            }else{
            ?>
            window.location.href = "/auth/";
            <?
            }
            ?>
        });
        $('.searchform').submit(function (e) {
            e.preventDefault();
            var email = $(this).find('input[type="text"]').val();
            $.ajax({
                type: 'POST',
                url: '/local/ajax/subscribeToNews.php',
                data: {email: email},
                success: function (response) {
                    // Обработка успешного ответа
                },
                error: function (error) {
                    // Обработка ошибки
                }
            });
        });
    });

</script>

<script>
    function setPrices(prices) {
        console.log(Object.values(prices));
        Object.values(prices).forEach(item => {
            let id = item.PRODUCT_ID;
            let price = item.PRICE;


            let currency = item.CURRENCY;
            let elements = document.querySelectorAll(`h2[data-value="${id}"]`);

            elements.forEach(element => {
                if (price===0){
                    element.textContent = 'Цена не указана';
                }else{
                    element.textContent = price+' '+currency;

                }

            });
        });
    }
    setPrices(Object.values(<?=CUtil::PhpToJSObject($arPrices)?>))
</script>


</body>
</html>

