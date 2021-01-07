<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator"></span>
            <a href="">Авторизация</a><span class="breadcrumbs-separator"></span>
            <span>Подтверждение регистрации</span>
        </div>

        <h1>Подтверждение регистрации</h1>
    </div>

    <div class="personal confirm">
        <? if (empty($this->success)): ?>
            <form action="/auth/confirm/" method="get">
                Введите код из письма в поле.
                <label>
                    Код <span class="red">*</span>
                    <input type="text" name="hash" class="required" value="<?= App\System\Request::get('hash') ?? '' ?>">
                    <span class="tooltip"></span>
                </label>
                <input type="submit" value="Отправить">
            </form>

            <? if (!empty(App\System\Request::get('hash'))): ?>
                <div class="message_error block">Введен неверный или недействительный код.</div>
            <? endif; ?>
        <? else: ?>
            <div class="success_message block">Регистрация пользователя подтверждена. <a href="/auth/">Авторизуйтесь</a> для входа в личный кабинет</div>
        <? endif; ?>
    </div>
</div>
