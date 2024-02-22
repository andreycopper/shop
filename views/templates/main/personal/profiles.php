<div class="catalog-container">
    <div class="leftmenu">
        <?= $this->render('menu/personal') ?>
        <?= $this->render('side/marketing') ?>
        <?= $this->render('side/subscribe') ?>
        <?= $this->render('side/news') ?>
        <?= $this->render('side/articles') ?>
    </div>

    <div class="main-section">
        <table class="personal-profiles">
            <tr>
                <th>Название</th>
                <th>Тип плательщика</th>
                <th>Изменен</th>
                <th></th>
            </tr>
            <tr>
                <td class="personal-profiles-title">Иванов Иван Иванович, Гоголя, 1</td>
                <td>Физическое лицо</td>
                <td>03.03.2020 13:12:20</td>
                <td>
                    <a href="" class="edit"></a>
                    <a href="" class="del"></a>
                </td>
            </tr>
            <tr>
                <td class="personal-profiles-title">ООО "Рога и копыта", Гоголя, 2</td>
                <td>Юридическое лицо</td>
                <td>03.03.2020 13:12:20</td>
                <td>
                    <a href="" class="edit" title="Изменить"></a>
                    <a href="" class="del" title="Удалить"></a>
                </td>
            </tr>
        </table>
    </div>
</div>
