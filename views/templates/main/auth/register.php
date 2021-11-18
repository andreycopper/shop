<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator"></span>
            <a href="">Авторизация</a><span class="breadcrumbs-separator"></span>
            <span>Регистрация</span>
        </div>

        <h1>Регистрация</h1>
    </div>

    <div class="personal register">
        <? if (empty($this->register)): ?>
            <form action="/auth/registration/" method="post" id="register">
                <p>Зарегистрируйтесь, чтобы использовать все возможности личного кабинета: отслеживание заказов, настройку подписки, связи с социальными сетями и другие.</p>
                <p>Уже зарегистрированы? <a href="/auth/">Войдите</a> в личный кабинет.</p>
                <p>Мы никогда и ни при каких условиях не разглашаем личные данные клиентов. Личные данные и пароль хранятся в зашифрованном виде.</p>
                <p>Контактная информация будет использована только для оформления заказов и более удобной работы с сайтом.</p>

                <label>
                    Фамилия <span class="red">*</span>
                    <input type="text" name="last_name" class="required">
                    <span class="tooltip"></span>
                </label>

                <label>
                    Имя <span class="red">*</span>
                    <input type="text" name="name" class="required">
                    <span class="tooltip"></span>
                </label>

                <label>
                    Отчество
                    <input type="text" name="second_name">
                    <span class="tooltip"></span>
                </label>

                <label>
                    Email <span class="red">*</span>
                    <input type="text" name="email" class="required">
                    <span class="tooltip"></span>
                </label>

                <label>
                    Телефон <span class="red">*</span>
                    <input type="text" name="phone" class="required">
                    <span class="tooltip"></span>
                </label>

                <label>
                    Пароль <span class="red">*</span>
                    <input type="password" name="password" class="required">
                    <span class="tooltip"></span>
                </label>

                <label>
                    Подтверждение пароля <span class="red">*</span>
                    <input type="password" name="password_confirm" class="required">
                    <span class="tooltip"></span>
                </label>

                <label class="checkbox">
                    <input type="checkbox" name="personal_data" class="required">
                    Я согласен на <a href="">обработку персональных данных</a> <span class="red">*</span>
                    <span class="tooltip"></span>
                </label>

                <input type="submit" name="send" value="Зарегистрироваться">

                <div class="message_error"></div>
            </form>

            <div class="success_message">
                <p>Пользователь успешно зарегистрирован.</p>
                <p>Вам отправлено письмо со ссылкой для подтверждения регистрации.</p>
                <p>Для подтверждения регистрации перейдите по ссылке в письме, либо введите код в поле для ввода на <a href="/auth/confirm/">странице</a>.</p>
            </div>
        <? else: ?>
            <div class="success_message block">
                <p>Пользователь успешно зарегистрирован.</p>
                <p>Вам отправлено письмо со ссылкой для подтверждения регистрации.</p>
                <p>Для подтверждения регистрации перейдите по ссылке в письме, либо введите код в поле для ввода на <a href="/auth/confirm/">странице</a>.</p>
            </div>
        <? endif; ?>
    </div>
</div>

<script>
    $(function () {
        $('#register input[type=submit]').on('click', function (e) {
            e.preventDefault();
            let form = $(this).parent('form'),
                tooltip = form.find('.tooltip'),
                message_error = form.find('.message_error'),
                error = [];
            tooltip.removeClass('active');

            form.find('input.required').each(function () {
                if ($(this).val()) {
                    if (-1 !== $(this).attr('name').indexOf('name') && !checkUserData($(this).val(), 'rus_eng')) {
                        error.push(true);
                        $(this).addClass('error');
                        $(this).parent().find('.tooltip').html('Проверьте введенные данные').addClass('active');
                    }
                    else if ('email' === $(this).attr('name') && !checkUserData($(this).val(), 'email')) {
                        error.push(true);
                        $(this).addClass('error');
                        $(this).parent().find('.tooltip').html('Проверьте введенный email').addClass('active');
                    }
                    else if ('phone' === $(this).attr('name') && !checkUserData($(this).val(), 'phone')) {
                        error.push(true);
                        $(this).addClass('error');
                        $(this).parent().find('.tooltip').html('Проверьте введенный телефон').addClass('active');
                    }
                    else if (('password' === $(this).attr('name') || 'password_confirm' === $(this).attr('name')) && !checkUserData($(this).val(), 'pass')) {
                        error.push(true);
                        $(this).addClass('error');
                        $(this).parent().find('.tooltip').html('Недостаточная сложность пароля').addClass('active');
                    }
                    else if ('checkbox' === $(this).attr('type') && !$(this).prop('checked')) {
                        error.push(true);
                        $(this).parent().addClass('error');
                        $(this).parent().find('.tooltip').html('Не получено согласие на обработку персональных данных').addClass('active');
                    }
                    else {
                        error.push(false);
                        $(this).removeClass('error');
                    }
                } else {
                    error.push(true);
                    $(this).addClass('error');
                }
            });

            if (-1 === error.indexOf(true)) {
                let $data = {
                    last_name:        form.find('input[name=last_name]').val(),
                    name:             form.find('input[name=name]').val(),
                    second_name:      form.find('input[name=second_name]').val(),
                    email:            form.find('input[name=email]').val(),
                    phone:            form.find('input[name=phone]').val(),
                    password:         form.find('input[name=password]').val(),
                    password_confirm: form.find('input[name=password_confirm]').val(),
                    personal_data:    form.find('input[name=personal_data]').prop('checked') ? 1 : 0,
                };

                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: "/auth/registration/",
                    data: $data,
                    beforeSend: function() {
                        message_error.html('').hide();
                        $('#loader').show();
                    },
                    success: function(data){console.log(data);
                        $('#loader').hide();

                        if (!data.result) {
                            message_error.html(data.message).show();
                        } else {
                            form.hide();
                            $('.success_message').show();
                        }
                    }
                });
            }
        });
    });
</script>
