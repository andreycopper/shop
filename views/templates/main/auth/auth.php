<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator"></span><span>Авторизация</span>
        </div>

        <h1>Авторизация</h1>
    </div>

    <div class="auth">
        <form action="/auth/" method="post">
            <label>
                Email / Телефон <span class="red">*</span>
                <input type="text" name="login" class="required">
                <span class="tooltip"></span>
            </label>
            <label>
                Пароль <span class="red">*</span>
                <input type="password" name="password" class="required">
                <span class="tooltip"></span>
            </label>
<!--            <label class="checkbox">-->
<!--                <input type="checkbox" name="personal_data" class="required">-->
<!--                Я согласен на <a href="">обработку персональных данных</a> <span class="red">*</span>-->
<!--                <span class="tooltip"></span>-->
<!--            </label>-->
            <div class="flex-wrap additional">
                <label class="checkbox checked">
                    <input type="checkbox" name="remember" checked>
                    Запомнить меня
                </label>
                <a href="/auth/restore/" class="textLeft">Забыли пароль?</a>
            </div>

            <div class="flex-wrap additional">
                <input type="submit" value="Войти">
                <a href="/auth/registration/" class="btn-alt">Регистрация</a>
            </div>

            <div class="message_error"></div>
        </form>
    </div>
</div>
