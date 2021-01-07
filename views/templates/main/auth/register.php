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
                <p>Мы никогда и ни при каких условиях не разглашаем личные данные клиентов. Контактная информация будет использована только для оформления заказов и более удобной работы с сайтом. </p>

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
