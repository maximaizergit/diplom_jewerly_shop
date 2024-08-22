<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
CJSCore::Init('ajax');
?>
<div class="personal-component">
    <form method="post" enctype="multipart/form-data" id="personal-form">

        <div class="personal-wrapper">
            <div class="main-buttons-wrapper">
                <input type="hidden" class="form-control" id="action-input" name="action" value="">
                <input type="hidden" class="form-control" id="data-input" name="data" value="">
                <label for="name">Имя:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?=$arResult['USER_NAME']?>">

                <label for="last-name">Фамилия:</label>
                <input type="text" class="form-control" id="last-name" name="last-name" value="<?=$arResult['USER_LAST_NAME']?>">

                <label for="email">Email:</label>
                <input type="text" class="form-control" id="email" name="email" value="<?=$arResult['USER_EMAIL']?>">

                <label for="login">Login:</label>
                <input type="text" class="form-control" id="login" name="login" value="<?=$arResult['USER_LOGIN']?>">

                <div class="action-wrapper">
                <button type="button" class="btn btn-primary" id="save-submit">Сохранить</button>
                <button type="button" class="btn btn-primary" id="update-photo-submit">Обновить фото</button>

            </div>
                <label for="pass">Пароль:</label>
                <input type="text" class="form-control" id="pass" name="pass" value="">

                <label for="pass-repeat">Подтверждение пароля:</label>
                <input type="text" class="form-control" id="pass-repeat" name="pass-repeat" value="">
                <button type="button" class="btn btn-primary" id="update-password-submit">Сменить пароль</button>

            </div>

            <div class="photo-wrapper">
                <img src="<?=CFile::GetPath($arResult["TEST"]["PERSONAL_PHOTO"]);?>" alt="Фото отсутствует!" style="width: 200px; height: 200px">

                <label class="input-file">
                    <input type="file" id="photo-input" class="file-input" accept="image/*" name="photo">
                    <span>Выберите файл</span>
                </label>

            </div>
        </div>

        <div class="album-wrapper">
            <h1>Ваш фотоальбом</h1>
            <?foreach ($arResult['PHOTOS'] as $photo){

?>
                <div class="image-container">
                    <img src="<?=CFile::GetPath($photo);?>" alt="Фото отсутствует!" style="width: 200px; height: 200px">
                    <button class="delete-button" data-value="<?=$photo?>"></button>
                </div>

                <?

            }?>


            <div class="album-btns-wrapper">
                <label class="input-file">
                    <input name="album[]" id="album-input" type="file"  multiple="true" accept="image/*" />


                    <span>Выберите файл</span>
                </label>
                <button type="button" class="btn btn-primary btn-album" id="update-album-submit">Обновить альбом</button>

            </div>


        </div>



            <script>
                document.getElementById('save-submit').addEventListener('click', function () {
                    var name = document.getElementById('name').value;
                    var lastName = document.getElementById('last-name').value;
                    var email = document.getElementById('email').value;
                    var login = document.getElementById('login').value;

                    var userData = {
                        "NAME": name ,
                        "LAST_NAME": lastName,
                        "EMAIL": email,
                        "LOGIN": login
                    };

                    var userData = {
                        "NAME": name,
                        "LAST_NAME": lastName,
                        "EMAIL": email,
                        "LOGIN": login
                    };

                    userData = Object.keys(userData)
                        .filter(key => userData[key] !== "")
                        .reduce((obj, key) => {
                            obj[key] = userData[key];
                            return obj;
                        }, {});

                    var jsonUserData = JSON.stringify(userData);
                    document.getElementById('action-input').value = 'updateData';
                    document.getElementById('data-input').value = jsonUserData;
                    document.getElementById('personal-form').submit();
                });

                document.getElementById('update-password-submit').addEventListener('click', function () {
                    var pass = document.getElementById('pass').value;
                    var passRepeat = document.getElementById('pass-repeat').value;
                    var login = document.getElementById('login').value;

                    if (pass!==passRepeat){
                        alert('Пароли не совпадают!')
                    }else{

                        var userData = {
                            "PASSWORD": pass,
                            "LOGIN":login
                        };

                        userData = Object.keys(userData)
                            .filter(key => userData[key] !== "")
                            .reduce((obj, key) => {
                                obj[key] = userData[key];
                                return obj;
                            }, {});

                        var jsonUserData = JSON.stringify(userData);
                        document.getElementById('action-input').value = 'updatePass';
                        document.getElementById('data-input').value = jsonUserData;
                        document.getElementById('personal-form').submit();
                    }


                });

                document.getElementById('update-photo-submit').addEventListener('click', function () {

                    var fileInput = document.getElementById('photo-input');

                    if (fileInput.files.length === 0) {
                        // Файл не был добавлен
                        alert('Сначала загрузите изображение!');
                        return;
                    } else {
                        // Получаем информацию о добавленном файле
                        var file = fileInput.files[0];

                        // Проверяем тип файла (должен быть изображением)
                        if (file.type.startsWith('image/')) {
                            // Файл является изображением
                            document.getElementById('action-input').value = 'updatePhoto';
                            document.getElementById('personal-form').submit();
                        } else {
                            // Файл не является изображением
                            alert('Файл не является изображением.');
                        }
                    }

                });

                document.getElementById('update-album-submit').addEventListener('click', function () {

                    var fileInput = document.getElementById('album-input');

                    if (fileInput.files.length === 0) {
                        // Файл не был добавлен
                        alert('Сначала загрузите изображение!');
                        return;
                    } else {
                        // Получаем информацию о добавленном файле
                        var file = fileInput.files[0];

                        // Проверяем тип файла (должен быть изображением)
                        if (file.type.startsWith('image/')) {
                            // Файл является изображением
                            document.getElementById('action-input').value = 'updateAlbum';
                            document.getElementById('personal-form').submit();
                        } else {
                            // Файл не является изображением
                            alert('Файл не является изображением.');
                        }
                    }

                });

                const deleteButtons = document.querySelectorAll('.delete-button');

                // Добавляем клик-событие к каждой кнопке
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Получаем значение атрибута "data-value" у кнопки
                        const photoValue = this.getAttribute('data-value');
                        var userData = {
                            "PHOTO_ID": photoValue,
                        };

                        var jsonUserData = JSON.stringify(userData);
                        document.getElementById('action-input').value = 'deleteAlbumItem';
                        document.getElementById('data-input').value = jsonUserData;
                        document.getElementById('personal-form').submit();
                    });
                });

                $('.input-file input[type=file]').on('change', function(){

                    $(this).next().html("Загружено файлов: "+this.files.length);
                });
            </script>

    </form>
</div>
<!--<pre>-->
<?// print_r($arResult) ?>
<!--</pre>-->


