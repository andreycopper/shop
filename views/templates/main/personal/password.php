<?php
use System\Request;
?>

<div class="catalog-container">
    <? if (\Models\User\User::isAuthorized()): ?>
        <div class="leftmenu">
            <?= $this->render('menu/personal') ?>
            <?= $this->render('side/marketing') ?>
            <?= $this->render('side/subscribe') ?>
            <?= $this->render('side/news') ?>
            <?= $this->render('side/articles') ?>
        </div>
    <? endif; ?>

    <div class="main-section personal">
        <?php if (!empty($this->form)): ?>
            <form action="/personal/password/" method="post" id="change">
                Введите новый пароль и подтвердите его.
                <label>
                    Старый пароль <span class="red">*</span>
                    <input type="password" name="password_old" class="required">
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

                <input type="hidden" name="hash" value="<?= Request::get('hash') ?? null ?>">

                <input type="submit" name="send" value="Отправить">

                <div class="message_error"></div>
            </form>

            <div class="success_message"></div>
        <?php else: ?>
            <?php if (empty($this->success)): ?>
                <form action="/personal/password/" method="get">
                    Введите код из письма в поле.
                    <label>
                        Код <span class="red">*</span>
                        <input type="text" name="hash" class="required">
                        <span class="tooltip"></span>
                    </label>
                    <input type="submit" value="Отправить">
                </form>
            <?php else: ?>
                <div class="success_message block">Пароль успешно изменен.</div>
            <?php endif; ?>

            <?php if (!empty($this->error)): ?>
                <div class="message_error block">Введен неверный или недействительный код.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    function get_extension(filename) {
        return filename.slice(filename.lastIndexOf('.' - 1 >>> 0) + 2);
    }
    $(function() {
        console.log(get_extension('name.txt'));

        /* изменение пароля */
        $('#change').on('submit', function (e) {
            e.preventDefault();
            let form = $(this),
                input_password = form.find('input[name=password'),
                input_password_confirm = form.find('input[name=password_confirm'),
                password = input_password.val(),
                password_confirm = input_password_confirm.val(),
                message_error = form.find('.message_error'),
                error = [];

            form.find('input.required').each(function () {
                if (!$(this).val()) {
                    error.push(true);
                    $(this).addClass('error');
                    $(this).parent().find('.tooltip').html('Введите пароль').addClass('active');
                }
                else if (!checkUserData($(this).val(), 'pass')) {
                    error.push(true);
                    $(this).addClass('error');
                    $(this).parent().find('.tooltip').html('Пароль должен содержать 1 цифру, 1 заглавную и строчные буквы').addClass('active');
                }
                else if (password !== password_confirm) {
                    error.push(true);
                    input_password.addClass('error');
                    input_password_confirm.addClass('error');
                    input_password_confirm.parent().find('.tooltip').html('Пароль и его подтверждение не совпадают').addClass('active');
                }
                else {
                    error.push(false);
                    $(this).removeClass('error');
                }
            });

            if (-1 === error.indexOf(true)) {
                message_error.html('').hide();
                $('.tooltip').html('').removeClass('active');

                let $data = {
                    password: password,
                    password_confirm: password_confirm,
                    hash: form.find('input[name=hash').val()
                };

                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: "/personal/password/",
                    data: $data,
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(data){console.log(data);
                        $('#loader').hide();

                        if (!data.result) {
                            if (1 === data.error) input_password.parent().find('.tooltip').html(data.message).addClass('active');
                            else if (2 === data.error) input_password_confirm.parent().find('.tooltip').html(data.message).addClass('active');
                            else message_error.html(data.message).show();
                        } else {
                            form.hide();
                            $('.success_message').html('Пароль успешно изменен').show();
                        }
                    }
                });
            }
        });
    });
</script>
