<?php
/**
 * @var array $priceRange
 * @var array $filters
 */

$rangeMin = !empty($priceRange['min']) ? round($priceRange['min']) : 0;
$rangeMax = !empty($priceRange['max']) ? round($priceRange['max']) : 1000000;

$priceMin = !empty($filters['price'][0]) && !empty($filters['price'][1]) ? min($filters['price'][0], $filters['price'][1]) : $rangeMin;
$priceMax = !empty($filters['price'][0]) && !empty($filters['price'][1]) ? max($filters['price'][0], $filters['price'][1]) : $rangeMax;
?>

<div class="catalog-left-filter">
    <form action="" class="catalog-left-filter-form">
        <div class="catalog-left-filter-header">
            Фильтр по параметрам
        </div>

        <div class="catalog-left-filter-item active">
            <a href="" class="catalog-left-filter-title">Розничная цена, р</a>
            <div class="catalog-left-filter-body active">
                <div class="catalog-left-inputrange">
                    <label>
                        <input type="text" name="price[]" class="price_min" value="<?= $priceMin ?: $rangeMin ?>">
                    </label>
                    <span class="divider"></span>
                    <label>
                        <input type="text" name="price[]" class="price_max" value="<?= $priceMax ?: $rangeMax ?>">
                    </label>

                    <div id="slider-range"></div>
                </div>
            </div>
        </div>

        <div class="catalog-left-filter-item active">
            <a href="" class="catalog-left-filter-title">Наши предложения</a>
            <div class="catalog-left-filter-body active">
                <div class="catalog-left-check">
                    <label class="checkbox <?= !empty($filters['actions']) && in_array('new', $filters['actions']) ? 'checked' : '' ?>">
                        <input type="checkbox" name="actions[]" value="new"
                            <?= !empty($filters['actions']) && in_array('new', $filters['actions']) ? 'checked' :'' ?>> Новинка
                    </label>
                    <label class="checkbox <?= !empty($filters['actions']) && in_array('hit', $filters['actions']) ? 'checked' : '' ?>">
                        <input type="checkbox" name="actions[]" value="hit"
                            <?= !empty($filters['actions']) && in_array('hit', $filters['actions']) ? 'checked' :'' ?>> Хит
                    </label>
                    <label class="checkbox <?= !empty($filters['actions']) && in_array('recommend', $filters['actions']) ? 'checked' : '' ?>">
                        <input type="checkbox" name="actions[]" value="recommend"
                            <?= !empty($filters['actions']) && in_array('recommend', $filters['actions']) ? 'checked' :'' ?>> Советуем
                    </label>
                    <label class="checkbox <?= !empty($filters['actions']) && in_array('action', $filters['actions']) ? 'checked' : '' ?>">
                        <input type="checkbox" name="actions[]" value="action"
                            <?= !empty($filters['actions']) && in_array('action', $filters['actions']) ? 'checked' : '' ?>> Акция
                    </label>
                </div>
            </div>
        </div>

        <?php if (!empty($vendors) && is_array($vendors)): ?>
            <div class="catalog-left-filter-item <?= !empty($filters['vendors']) ? 'active' : '' ?>">
                <a href="" class="catalog-left-filter-title">Бренды</a>
                <div class="catalog-left-filter-body <?= !empty($filters['vendors']) ? 'active' : '' ?>">
                    <div class="catalog-left-check">
                        <?php foreach ($vendors as $vendor): ?>
                            <label class="checkbox <?= !empty($filters['vendors']) && in_array($vendor['id'], $filters['vendors']) ? 'checked' :'' ?>">
                                <input type="checkbox" name="vendors[]" value="<?= $vendor['id'] ?>"
                                    <?= !empty($filters['vendors']) && in_array($vendor['id'], $filters['vendors']) ? 'checked' :'' ?>>
                                <?= $vendor['name'] ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

<!--        <div class="catalog-left-filter-item">-->
<!--            <a href="" class="catalog-left-filter-title">Тип</a>-->
<!--            <div class="catalog-left-filter-body">-->
<!--                <div class="catalog-left-radio">-->
<!--                    <label class="radio">-->
<!--                        <input id="type-all" type="radio" name="type"> Все-->
<!--                    </label>-->
<!---->
<!--                    <label class="radio">-->
<!--                        <input id="type-1din" type="radio" name="type"> 1 DIN-->
<!--                    </label>-->
<!--                </div>-->
<!---->
<!--                <div class="catalog-left-select">-->
<!--                    <label>-->
<!--                        <select name="" id="">-->
<!--                            <option value="">Все</option>-->
<!--                            <option value="">1 DIN</option>-->
<!--                            <option value="">2 DIN</option>-->
<!--                            <option value="">3 DIN</option>-->
<!--                        </select>-->
<!--                    </label>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->

        <div class="catalog-left-filter-submit">
            <input type="submit" class="btn" value="Показать">
        </div>
    </form>
</div>

<script>
    $(function () {
        let range = $('#slider-range');

        range.slider({
            range: true,
            min: <?= $rangeMin ?>,
            max: <?= $rangeMax ?>,
            values: [
                <?= $priceMin ?>,
                <?= $priceMax ?>
            ],
            slide: function(e, data) {
                $('.catalog-left-inputrange .price_min').val(data.values[0]);
                $('.catalog-left-inputrange .price_max').val(data.values[1]);
            }
        });

        //$('.catalog-left-inputrange input.price_min').val(range.slider('values', 0));
        //$('.catalog-left-inputrange input[name=price_min]').val(range.slider('values', 0).toLocaleString());
        //$('.catalog-left-inputrange input.price_max').val(range.slider('values', 1));
        //$('.catalog-left-inputrange input[name=price_max]').val(range.slider('values', 1).toLocaleString());

        /* применение фильтров */
        $('.catalog-left-filter-form').on('submit', function (e) {
            e.preventDefault();
            let params = $(this).serialize();
            let obj = getParamsObject(params.split('&'));
            window.location.href = getRedirectUrl(obj);
        });

        /* разворачивание/сворачивание пунктов в фильтре товаров */
        $('.catalog-left-filter-title').on('click', function (e) {
            e.preventDefault();
            let next = $(this).next();
            $(this).parents('.catalog-left-filter-item').toggleClass('active');
            next.slideToggle();
        });
    });

    /**
     * Формирует объект с параметрами адресной строки
     * @param params
     * @returns {{}}
     */
    function getParamsObject(params) {
        let obj = {};

        for (let i=0; i<params.length; i++) {
            let a = params[i].split('=');
            let paramName = a[0].replace('%5B%5D', '').toLowerCase(),
                paramValue = a[1].replace('%C2%A0', '').toLowerCase();

            if (obj[paramName]) {// если ключ параметра уже задан
                if (typeof obj[paramName] === 'string') obj[paramName] = [obj[paramName]]; // преобразуем текущее значение в массив
                obj[paramName].push(paramValue); // помещаем значение в конец массива
            }
            else obj[paramName] = paramValue; // если параметр не задан, делаем это вручную
        }

        return obj;
    }

    /**
     * ФОрмирует адресную строку для редиректа
     * @param obj
     * @returns {string}
     */
    function getRedirectUrl(obj) {
        let url = [];
        for (let key in obj) {
            if (typeof obj[key] === 'object') url.push(key + '=' + obj[key].join('-'));
            else url.push(key + '=' + obj[key]);
        }
        return '?' + url.join('&');
    }
</script>
