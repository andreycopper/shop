<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator"></span>
            <span>Оформление заказа</span>
        </div>

        <h1>Заказ сформирован</h1>
    </div>

    <div class="catalog-container">

        <div class="order-success">
            <p>Ваш заказ <span>№<?= $this->order->id ?></span>
                от <?= DateTime::createFromFormat('Y-m-d H:i:s', $this->order->created)->format('d.m.Y') ?> успешно создан.
                <!--Номер вашей оплаты: <span>№4702/1</span>--></p>

            <p>Вы можете следить за выполнением своего заказа в <a href="/personal/">Персональном разделе сайта</a>.
                Обратите внимание, что для входа в этот раздел вам необходимо будет ввести логин и пароль пользователя сайта.</p>
        </div>
    </div>
</div>
