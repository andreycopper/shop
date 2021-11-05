<div class="catalog-left-filter">
    <div class="catalog-left-filter-header">
        Фильтр по параметрам
    </div>

    <div class="catalog-left-filter-item">
        <a href="" class="catalog-left-filter-title active">Розничная цена</a>
        <div class="catalog-left-filter-body">
            <div class="catalog-left-inputrange">
                <label>
                    <input type="text" name="price_min" placeholder="1 300">
                </label>
                <span class="divider"></span>
                <label>
                    <input type="text" name="price_max" placeholder="20 000">
                </label>

                <div id="slider-range"></div>
            </div>
        </div>
    </div>

    <div class="catalog-left-filter-item">
        <a href="" class="catalog-left-filter-title active">Наши предложения</a>
        <div class="catalog-left-filter-body">
            <div class="catalog-left-select">
                <label>
                    <select name="" id="">
                        <option value="">Все</option>
                        <option value="">Хит</option>
                        <option value="">Советуем</option>
                        <option value="">Акция</option>
                    </select>
                </label>
            </div>
        </div>
    </div>
    <div class="catalog-left-filter-item">
        <a href="" class="catalog-left-filter-title active">Бренды</a>
        <div class="catalog-left-filter-body">
            <div class="catalog-left-check">

                <input id="brand-cobra" type="checkbox" name="brand-cobra">
                <label for="brand-cobra">
                    <span class=""></span>Cobra
                </label>

                <input id="brand-garmin" type="checkbox" name="brand-garmin">
                <label for="brand-garmin">
                    <span class=""></span>GARMIN
                </label>

                <input id="brand-intro" type="checkbox" name="brand-intro">
                <label for="brand-intro">
                    <span class=""></span>Intro
                </label>

                <input id="brand-jvc" type="checkbox" name="brand-jvc">
                <label for="brand-jvc">
                    <span class=""></span>JVC
                </label>

                <input id="brand-mystery" type="checkbox" name="brand-mystery">
                <label for="brand-mystery">
                    <span class=""></span>Mystery
                </label>

                <input id="brand-parkcity" type="checkbox" name="brand-parkcity">
                <label for="brand-parkcity">
                    <span class=""></span>Parkcity
                </label>

                <input id="brand-pioneer" type="checkbox" name="brand-pioneer">
                <label for="brand-pioneer">
                    <span class=""></span>Pioneer
                </label>

                <input id="brand-ritmix" type="checkbox" name="brand-ritmix">
                <label for="brand-ritmix">
                    <span class=""></span>RITMIX
                </label>

                <input id="brand-shome" type="checkbox" name="brand-shome">
                <label for="brand-shome">
                    <span class=""></span>Sho me
                </label>
            </div>
        </div>
    </div>

    <div class="catalog-left-filter-item">
        <a href="" class="catalog-left-filter-title active">Тип</a>
        <div class="catalog-left-filter-body">
            <div class="catalog-left-radio">

                <input id="type-all" type="radio" name="type">
                <label for="type-all">
                    <span class="checked"></span>Все
                </label>

                <input id="type-1din" type="radio" name="type">
                <label for="type-1din">
                    <span class=""></span>1 DIN
                </label>

            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        let range = $('#slider-range');

        range.slider({
            range: true,
            min: 0,
            max: 500,
            values: [75, 300],
            slide: function(e, data) {
                $('.catalog-left-inputrange input[name=price_min]').val(data.values[0]);
                $('.catalog-left-inputrange input[name=price_max]').val(data.values[1] );
            }
        });

        $('.catalog-left-inputrange input[name=price_min]').val(range.slider( "values", 0 ));
        $('.catalog-left-inputrange input[name=price_max]').val(range.slider( "values", 1 ));
    });
</script>
