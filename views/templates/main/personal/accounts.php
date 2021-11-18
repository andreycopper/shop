<div class="catalog-container">
    <div class="leftmenu">
        <?= $this->render('side/menu_personal') ?>
        <?= $this->render('side/marketing') ?>
        <?= $this->render('side/subscribe') ?>
        <?= $this->render('side/news') ?>
        <?= $this->render('side/articles') ?>
    </div>

    <div class="main-section">
        <div class="personal-bill-state">
            Состояние счета на 02.03.2020
            <span>0 р.</span>
        </div>
        <div class="personal-bill-add">
            <h5>Пополнение счета</h5>
            <p>Сумма</p>
            <span>100</span><span>200</span><span>500</span><span>1000</span><span>5000</span>
            <form action="" method="post">
                <label>
                    <input type="text">р.
                </label>

                <div class="personal-bill-payment radio-container">
                    <label class="radio">
                                <span class="personal-bill-image">
                                    <img src="/uploads/personal/payment/1.gif" alt="">
                                </span>
                        <span class="personal-bill-title">Банковские карты</span>
                        <input type="radio" name="payment">
                    </label>

                    <label class="radio">
                                <span class="personal-bill-image">
                                    <img src="/uploads/personal/payment/2.gif" alt="">
                                </span>
                        <span class="personal-bill-title">Наличные курьеру</span>
                        <input type="radio" name="payment">
                    </label>
                </div>

                <button class="btn" type="submit">Пополнить</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        /* выбор суммы пополнения баланса */
        $('.personal-bill-add > span').on('click', function (e) {
            $('.personal-bill-add input').val($(this).html());
        });
    });
</script>
