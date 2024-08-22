<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Категория");
?>
    <div class="content">
        <div class='container'>
            <div class="content-body">
                <div class="filter">
                    <div class="filter__open btn-2 fw spoller">Фильтр</div>
                    <div class="filter-body">
                        <div class="filter-modules">
                            <div class="filter-module">
                                <div class="filter__label">Категории</div>
                                <ul class="filter-menu">
                                    <li>
                                        <a href="" class="filter-menu__link">Кольца <span class="fa fa-angle-down"></span></a>
                                        <ul class="filter-submenu">
                                            <li><a href="" class="filter-submenu__link">Коллекция 1</a></li>
                                            <li><a href="" class="filter-submenu__link">Коллекция 2</a></li>
                                            <li><a href="" class="filter-submenu__link">Коллекция 3</a></li>
                                            <li><a href="" class="filter-submenu__link">Коллекция 4</a></li>
                                            <li><a href="" class="filter-submenu__link">Коллекция 5</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="" class="filter-menu__link">Комплекты украшений</a></li>
                                    <li><a href="" class="filter-menu__link">Серьги</a></li>
                                    <li><a href="" class="filter-menu__link">Кулоны и подвески</a></li>
                                    <li><a href="" class="filter-menu__link">Колье и бусы</a></li>
                                    <li><a href="" class="filter-menu__link">Браслеты</a></li>
                                    <li><a href="" class="filter-menu__link">Украшения для мужчин</a></li>
                                </ul>
                            </div>
                            <div class="filter-module">
                                <div class="filter__label">Стоимость</div>
                                <div class="filter-price">
                                    <div class="filter-price-table table">
                                        <div class="cell">
                                            <input type="text" name="form[]" id="rangefrom" />
                                        </div>
                                        <div class="cell">
                                            <input type="text" name="form[]" id="rangeto" />
                                        </div>
                                    </div>
                                    <div id="range" class="filter-price-range"></div>
                                </div>
                            </div>
                            <div class="filter-module">
                                <div class="filter__label">Коллекции</div>
                                <div class="filter-checks">
                                    <div class="check">Коллекция 1<input type="checkbox" value="1" name="form[]" /></div>
                                    <div class="check">Коллекция 2<input type="checkbox" value="1" name="form[]" /></div>
                                    <div class="check">Коллекция 3<input type="checkbox" value="1" name="form[]" /></div>
                                    <div class="check">Коллекция 4<input type="checkbox" value="1" name="form[]" /></div>
                                    <div class="check">Коллекция 5<input type="checkbox" value="1" name="form[]" /></div>
                                </div>
                            </div>
                            <div class="filter-module">
                                <div class="filter__label">Камень</div>
                                <div class="filter-checks">
                                    <div class="check">Алмаз<input type="checkbox" value="1" name="form[]" /></div>
                                    <div class="check">Бриллиант<input type="checkbox" value="1" name="form[]" /></div>
                                    <div class="check">Рубин<input type="checkbox" value="1" name="form[]" /></div>
                                    <div class="check">Изумруд<input type="checkbox" value="1" name="form[]" /></div>
                                    <div class="check">Жемчуг<input type="checkbox" value="1" name="form[]" /></div>
                                    <div class="check">Сапфир<input type="checkbox" value="1" name="form[]" /></div>
                                </div>
                            </div>
                        </div>
                        <div class="filter-footer">
                            <a href="" class="filter__btn btn fw">Подобрать</a>
                            <a href="" class="filter-footer__clean">Очистить</a>
                        </div>
                        <div class="filter-results">
                            <div class="filter-results__quantity">Выбрано 3</div>
                            <a href="" class="filter-results__link">Показать</a>
                        </div>
                    </div>
                </div>
                <div class="content-block">
                    <div class="catalog">
                        <div class="catalog__title cnt title">Кольца</div>
                        <div class="catalog-items">
                            <div class="catalog-items-sector">
                                <div class="catalog-items-sector__subtitle">«Название коллекции 1»</div>
                                <div class="catalog-items-block">
                                    <div class="catalog-item white_1">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/01.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                    <div class="catalog-item black">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/02.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                    <div class="catalog-item white">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/03.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="catalog-items-button">
                                    <a href="" class="catalog-items__btn btn-3">Посмотреть все</a>
                                </div>
                            </div>
                            <div class="catalog-items-sector">
                                <div class="catalog-items-sector__subtitle">«Название коллекции 2»</div>
                                <div class="catalog-items-block">
                                    <div class="catalog-item white">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/04.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                    <div class="catalog-item white_2">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/05.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                    <div class="catalog-item black">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/06.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="catalog-items-button">
                                    <a href="" class="catalog-items__btn btn-3">Посмотреть все</a>
                                </div>
                            </div>
                            <div class="catalog-items-sector">
                                <div class="catalog-items-sector__subtitle">«Название коллекции 3»</div>
                                <div class="catalog-items-block">
                                    <div class="catalog-item black">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/06.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                    <div class="catalog-item white">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/04.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                    <div class="catalog-item white_3">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/05.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="catalog-items-button">
                                    <a href="" class="catalog-items__btn btn-3">Посмотреть все</a>
                                </div>
                            </div>
                            <div class="catalog-items-sector">
                                <div class="catalog-items-sector__subtitle">«Название коллекции 4»</div>
                                <div class="catalog-items-block">
                                    <div class="catalog-item white_4">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/01.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                    <div class="catalog-item black">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/02.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                    <div class="catalog-item white">
                                        <div class="catalog-item-content">
                                            <a href="" class="catalog-item-content__image ibg"><img src="img/catalog/03.png" alt="" /></a>
                                            <a href="" class="catalog-item-content__text">
                                                <span>Название украшения возможно в несколько строк, или может быть в три строки</span>
                                                <span>12 000 ₽</span>
                                            </a>
                                        </div>
                                        <a href="" class="catalog-item-hover">
                                            <span class="catalog-item-hover__btn btn-4">Смотреть</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="catalog-items-button">
                                    <a href="" class="catalog-items__btn btn-3">Посмотреть все</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?//require($_SERVER["DOCUMENT_ROOT"]."/local/templates/main/subscribe_form.php");?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>