<div class="catalog-container">
    <div class="leftmenu">
        <?= $this->render('side/menu_personal') ?>
        <?= $this->render('side/marketing') ?>
        <?= $this->render('side/subscribe') ?>
        <?= $this->render('side/news') ?>
        <?= $this->render('side/articles') ?>
    </div>

    <div class="main-section">
        <form action="" class="profile-form">
            <label>Тип плательщика
                <input type="text" value="Физическое лицо" disabled>
            </label>

            <label>Название <span class="required"></span>
                <input type="text" value="">
            </label>

            <h6>Личные данные</h6>

            <label>Ф.И.О. <span class="required"></span>
                <input type="text" value="">
            </label>

            <label>E-mail <span class="required"></span>
                <input type="text" value="">
            </label>

            <label>Телефон <span class="required"></span>
                <input type="text" value="">
            </label>

            <h6>Данные для доставки</h6>

            <label>Индекс <span class="required"></span>
                <input type="text" value="">
            </label>

            <label>Населенный пункт <span class="required"></span>
                <input type="text" value="">
            </label>

            <label>Адрес <span class="required"></span>
                <input type="text" value="">
            </label>


            <a href="" class="btn">Сохранить</a>
            <a href="" class="btn">Применить</a>
            <a href="" class="btn-alt">Отмена</a>
        </form>
    </div>
</div>