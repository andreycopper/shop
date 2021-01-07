<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator"></span>
            <a href="">Личный кабинет</a><span class="breadcrumbs-separator"></span>
            <span>Изменение пароля</span>
        </div>

        <h1>Изменение пароля</h1>
    </div>

    <div class="personal change">
        <? if (!empty($this->form)): ?>
            <form action="/personal/change/" method="post" id="change">
                Введите новый пароль и подтвердите его.
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

                <input type="hidden" name="hash" value="<?= \App\System\Request::get('hash') ?? null ?>">

                <input type="submit" name="send" value="Отправить">

                <div class="message_error"></div>
            </form>

            <div class="success_message"></div>
        <? else: ?>
            <? if (empty($this->success)): ?>
                <form action="/personal/change/" method="get">
                    Введите код из письма в поле.
                    <label>
                        Код <span class="red">*</span>
                        <input type="text" name="hash" class="required">
                        <span class="tooltip"></span>
                    </label>
                    <input type="submit" value="Отправить">
                </form>
            <? else: ?>
                <div class="success_message block">Пароль успешно изменен.</div>
            <? endif; ?>

            <? if (!empty($this->error)): ?>
                <div class="message_error block">Введен неверный или недействительный код.</div>
            <? endif; ?>
        <? endif; ?>
    </div>
</div>
