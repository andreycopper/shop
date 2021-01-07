<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator"></span>
            <a href="">Авторизация</a><span class="breadcrumbs-separator"></span>
            <span>Восстановление пароля</span>
        </div>

        <h1>Восстановление пароля</h1>
    </div>


    <div class="personal restore">
        <? if (empty($this->restore)): ?>
            <form action="/auth/restore/" method="post">
                Введите номер телефона или e-mail. Контрольная строка для смены пароля будет выслана вам на e-mail.
                <label>
                    Email / Телефон <span class="red">*</span>
                    <input type="text" name="login" class="required">
                    <span class="tooltip"></span>
                </label>

                <input type="submit" name="send" value="Отправить">

                <div class="message_error"></div>
            </form>

            <div class="success_message">
                Вам отправлено сообщение со ссылкой для смены пароля. Либо введите код из сообщения на странице <a href="/personal/change/">"Изменение пароля"</a>
            </div>

            <div class="error_message">
                Произошел сбой во время создания запроса на изменение пароля. Повторите попытку позднее.
            </div>
        <? else: ?>
            <div class="success_message block">
                Вам отправлено сообщение со ссылкой для смены пароля. Либо введите код из сообщения на странице <a href="/personal/change/">"Изменение пароля"</a>
            </div>
        <? endif; ?>
    </div>
</div>
