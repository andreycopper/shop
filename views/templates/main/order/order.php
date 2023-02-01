<?php
use System\RSA;

$rsa = new RSA($this->user->private_key);
$last_name = !empty($this->user->last_name) ? $rsa->decrypt($this->user->last_name) : '';
$name = !empty($this->user->name) ? $rsa->decrypt($this->user->name) : '';
$second_name = !empty($this->user->second_name) ? $rsa->decrypt($this->user->second_name) : '';
?>

<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator"></span>
            <span>Оформление заказа</span>
        </div>

        <h1>Оформление заказа</h1>
    </div>

    <?php if (!empty($this->cart['items']) && is_array($this->cart['items'])): ?>
        <form action="/orders/finish/" method="post" class="catalog-container" id="order">
            <div class="main-section">
                <div class="order-item">
                    <a href="" class="order-item-title order-user">Покупатель</a>

                    <div class="order-item-container radio-container">
                        <?php if ($this->user->id !== '2'): ?>
                            <div class="order-user-type">
                                <label class="radio checked">
                                    <input type="radio" name="type" value="1" data-target="order-user-physical" class="required" checked>
                                    <span class="order-item-name">Физическое лицо</span>
                                </label>

                                <label class="radio">
                                    <input type="radio" name="type" value="2" class="required" data-target="order-user-juridical">
                                    <span class="order-item-name">Юридическое лицо</span>
                                </label>
                            </div>
                        <?php endif; ?>

                        <div class="order-item-slider order-user-physical">
                            <?php if ($this->user->id !== '2'): ?>
                                <label for="p_profile">Профиль доставки</label>
                                <select name="p_profile" id="p_profile" class="required">
                                    <option value="0" selected>Новый профиль</option>
                                    <?php if (!empty($this->profiles) && is_array($this->profiles)):
                                        foreach ($this->profiles as $profile):
                                            if ($profile->user_type_id !== '1') continue; ?>
                                            <option value="<?= $profile->id ?>"
                                                    data-user_type_id="<?= $profile->user_type_id ?>"
                                                    data-p_name="<?= ($rsa)->decrypt($profile->name) ?>"
                                                    data-p_email="<?= $profile->email ?>"
                                                    data-p_phone="<?= $profile->phone ?>"
                                                    data-index=""
                                                    data-city_id="<?= $profile->city_id ?>"
                                                    data-city="<?= $profile->city ?>"
                                                    data-street_id="<?= $profile->street_id ?>"
                                                    data-street="<?= $profile->street ?>"
                                                    data-house="<?= $profile->house ?>"
                                                    data-building="<?= $profile->building ?>"
                                                    data-flat="<?= $profile->flat ?>"
                                                    data-comment="<?= $profile->comment ?>"
                                            >
                                                <?= $profile->city . ', ' . $profile->street . ', ' . $profile->house . ($profile->flat ? ('-' . $profile->flat) : '') ?>
                                            </option>
                                        <?php endforeach;
                                    endif; ?>
                                </select>
                                <div class="message_error"></div>
                            <?php else: ?>
                                <input type="hidden" name="p_profile" value="0">
                            <?php endif; ?>

                            <label for="p_name">Контактное лицо <span class="red">*</span></label>
                            <input type="text" name="p_name" id="p_name" value="<?= ($last_name . ' ' . $name . ' ' . $second_name) ?>" class="required">
                            <div class="message_error"></div>

                            <label for="p_email">E-mail <span class="red">*</span></label>
                            <input type="text" name="p_email" id="p_email" value="<?= $this->user->email ?? '' ?>" class="required">
                            <div class="message_error"></div>

                            <label for="p_phone">Телефон <span class="red">*</span></label>
                            <input type="text" name="p_phone" id="p_phone" value="<?= $this->user->phone ?? '' ?>" class="required">
                            <div class="message_error"></div>
                        </div>

                        <?php if ($this->user->id !== '2'): ?>
                            <div class="order-item-slider order-user-juridical">
                                <label for="j_profile">Профиль доставки</label>
                                <select name="j_profile" id="j_profile" class="required">
                                    <option value="0" selected>Новый профиль</option>
                                    <?php if (!empty($this->profiles) && is_array($this->profiles)):
                                        foreach ($this->profiles as $profile):
                                            if ($profile->user_type_id !== '2') continue; ?>
                                            <option value="<?= $profile->id ?>"
                                                    data-user_type_id="<?= $profile->user_type_id ?>"
                                                    data-j_name="<?= ($rsa)->decrypt($profile->name) ?>"
                                                    data-j_email="<?= $profile->email ?>"
                                                    data-j_phone="<?= $profile->phone ?>"
                                                    data-index=""
                                                    data-city_id="<?= $profile->city_id ?>"
                                                    data-city="<?= $profile->city ?>"
                                                    data-street_id="<?= $profile->street_id ?>"
                                                    data-street="<?= $profile->street ?>"
                                                    data-house="<?= $profile->house ?>"
                                                    data-building="<?= $profile->building ?>"
                                                    data-flat="<?= $profile->flat ?>"
                                                    data-comment="<?= $profile->comment ?>"
                                                    data-company='<?= $profile->company ?>'
                                                    data-j_address="<?= $profile->address_legal ?>"
                                                    data-inn="<?= $profile->inn ?>"
                                                    data-kpp="<?= $profile->kpp ?>"
                                            >
                                                <?= $profile->company . ', ' . $profile->city . ', ' . $profile->street . ', ' . $profile->house ?>
                                            </option>
                                        <?php endforeach;
                                    endif; ?>
                                </select>
                                <div class="message_error"></div>

                                <label for="j_name">Контактное лицо <span class="red">*</span></label>
                                <input type="text" name="j_name" id="j_name" value="<?= ($last_name . ' ' . $name . ' ' . $second_name) ?>" class="required">
                                <div class="message_error"></div>

                                <label for="j_email">E-mail <span class="red">*</span></label>
                                <input type="text" name="j_email" id="j_email" value="<?= $this->user->email ?? '' ?>" class="required">
                                <div class="message_error"></div>

                                <label for="j_phone">Телефон <span class="red">*</span></label>
                                <input type="text" name="j_phone" id="j_phone" value="<?= $this->user->phone ?? '' ?>" class="required">
                                <div class="message_error"></div>

                                <label for="company">Название компании <span class="red">*</span></label>
                                <input type="text" name="company" id="company" class="required">
                                <div class="message_error"></div>

                                <label for="j_address">Юридический адрес <span class="red">*</span></label>
                                <input type="text" name="j_address" id="j_address" class="required">
                                <div class="message_error"></div>

                                <label for="inn">ИНН <span class="red">*</span></label>
                                <input type="text" name="inn" id="inn" class="required">
                                <div class="message_error"></div>

                                <label for="kpp">КПП</label>
                                <input type="text" name="kpp" id="kpp">
                                <div class="message_error"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="order-item">
                    <a href="" class="order-item-title order-delivery">Доставка</a>

                    <div class="order-item-container">
                        <?php if (!empty($this->deliveries) && is_array($this->deliveries)): ?>
                            <?php foreach ($this->deliveries as $delivery): ?>
                                <div class="radio-container">
                                    <label class="radio <?= $delivery->id === '1' ? 'checked' : '' ?>">
                                        <input type="radio" name="delivery" value="<?= $delivery->id ?>" <?= $delivery->id === '1' ? 'checked' : '' ?>>
                                        <span class="order-item-name"><?= $delivery->name ?></span>
                                        <span class="order-item-price">
                                        Стоимость:
                                        <?php if (!empty($delivery->price)): ?>
                                            <span><?= $delivery->price ?></span> р.
                                        <?php elseif (!empty($delivery->price_from) || !empty($delivery->price_to)): ?>
                                            <span>
                                                <?=
                                                ($delivery->price_from ? ('от ' . $delivery->price_from . ' ') : '') .
                                                ($delivery->price_to ? ('до ' . $delivery->price_to) : '')
                                                ?>
                                            </span> р.
                                        <?php else: ?>
                                            бесплатно
                                        <?php endif; ?>
                                    </span>
                                        <span class="order-item-time">Срок доставки: <span><?= $delivery->time ?></span></span>
                                        <span class="order-item-desc"><?= $delivery->description ?></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="order-item order-item-delivery hidden">
                    <a href="" class="order-item-title order-region">Адрес доставки</a>

                    <div class="order-item-container">
                        <div class="relative visible">
                            <label for="delivery_city">Населенный пункт <span class="red">*</span></label>
                            <input type="hidden" name="city_id" value="<?= $this->location->id ?? '' ?>" class="required">
                            <input type="text" name="city" id="delivery_city" class="required"
                                   value="<?= $this->location->id ? ($this->location->region . ', ' . $this->location->name . ' ' . $this->location->shortname) : '' ?>">
                            <ul class="order-item-search-result"></ul>
                            <div class="message_error"></div>
                        </div>

                        <div class="relative visible">
                            <label for="delivery_street">Улица <span class="red">*</span></label>
                            <input type="hidden" name="street_id" class="required">
                            <input type="text" name="street" id="delivery_street" class="required">
                            <ul class="order-item-search-result"></ul>
                            <div class="message_error"></div>
                        </div>

                        <div class="order-delivery-address">
                            <div class="order-delivery-house">
                                <label for="delivery_house">Дом <span class="red">*</span></label>
                                <input type="text" name="house" id="delivery_house" class="required">
                                <div class="message_error"></div>
                            </div>

                            <div class="order-delivery-building">
                                <label for="delivery_building">Корпус</label>
                                <input type="text" name="building" id="delivery_building">
                            </div>

                            <div class="order-delivery-flat">
                                <label for="delivery_flat">Квартира</label>
                                <input type="text" name="flat" id="delivery_flat">
                            </div>
                        </div>

                        <label for="delivery_comment">Комментарий к заказу</label>
                        <textarea name="comment" id="delivery_comment"></textarea>

                        <div class="order-item-comment">
                            Выберите профиль доставки или введите свой город и адрес.
                        </div>
                    </div>
                </div>

                <div class="order-item">
                    <a href="" class="order-item-title order-payment">Оплата</a>

                    <div class="order-item-container radio-container">
                        <?php if (!empty($this->payments) && is_array($this->payments)): ?>
                            <?php foreach ($this->payments as $payment): ?>
                                <label class="radio <?= $payment->id === '1' ? 'checked' : '' ?>">
                                    <input type="radio" name="payment" value="<?= $payment->id ?>" <?= $payment->id === '1' ? 'checked' : '' ?>>
                                    <span class="order-item-name"><?= $payment->name ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="order-item">
                    <a href="" class="order-item-title order-items">Заказ</a>

                    <div class="order-item-container order-product-container">
                        <div class="order-product-header">
                            <div class="order-product-cell order-product-image"></div>
                            <div class="order-product-cell order-product-title">Наименование</div>
                            <div class="order-product-cell order-product-weight">Вес</div>
                            <div class="order-product-cell order-product-dicsount">Скидка</div>
                            <div class="order-product-cell order-product-values">Цена</div>
                            <div class="order-product-cell order-product-count">Кол-во</div>
                            <div class="order-product-cell order-product-totalvalues">Сумма</div>
                        </div>

                        <?php foreach ($this->cart['items'] as $cart_item): ?>
                            <div class="order-product">
                                <div class="order-product-desc">
                                    <div class="order-product-cell order-product-image">
                                        <a href=""><img src="/uploads/catalog/<?= $cart_item->product_id ?>/<?= $cart_item->preview_image ?>" alt=""></a>
                                    </div>
                                    <div class="order-product-cell order-product-title">
                                        <a href=""><?= $cart_item->name ?></a>
                                        <span>Производитель <?= $cart_item->vendor_name ?></span>
                                    </div>
                                    <div class="order-product-cell order-product-weight">
                                        <i>Вес</i>
                                        0 кг
                                    </div>
                                    <div class="order-product-cell order-product-dicsount">
                                        <i>Скидка</i>
                                        <?= floatval($cart_item->discount) ?>%
                                    </div>
                                </div>
                                <div class="order-product-val">
                                    <div class="order-product-cell order-product-values">
                                        <i>Цена</i>
                                        <div class="order-product-price">
                                            <?=
                                            $cart_item->price_discount ?
                                                number_format(round($cart_item->price_discount, 2), 2, '.', ' ') :
                                                number_format(round($cart_item->price, 2), 2, '.', ' ')
                                            ?>
                                        </div>
                                        <?php if (!empty($cart_item->price_discount)): ?>
                                            <span class="order-product-oldprice">
                                                <?= number_format(round($cart_item->price, 2), 2, '.', ' ') ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="order-product-cell order-product-totalcount">
                                        <i>Кол-во</i>
                                        <div class="order-product-count"><?= $cart_item->count ?> <?= $cart_item->unit ?></div>
                                    </div>
                                    <div class="order-product-cell order-product-totalvalues">
                                        <i>Сумма</i>
                                        <div class="order-product-total">
                                            <?=
                                            $cart_item->sum_discount ?
                                                number_format(round($cart_item->sum_discount, 2), 2, '.', ' ') :
                                                number_format(round($cart_item->sum, 2), 2, '.', ' ') ?>
                                        </div>
                                        <?php if (!empty($cart_item->sum_discount)): ?>
                                            <div class="order-product-oldtotal">
                                                <?= number_format(round($cart_item->sum, 2), 2, '.', ' ') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                </div>

                <div class="personal-data">
                    <label class="checkbox checked">
                        <input type="checkbox">
                        Я согласен на <a href="">обработку персональных данных</a> *
                    </label>
                </div>

                <div class="order-submit">
                    <button type="submit" class="btn">Оформить заказ</button>
                </div>
            </div>

            <div class="rightmenu">
                <div class="side-order">
                    <div class="side-order-header">
                        <div class="side-order-container">
                            <div class="side-order-left">Ваш заказ</div>
                            <div class="side-order-right"><a href="">Изменить</a></div>
                        </div>
                    </div>
                    <div class="side-order-body">
                        <div class="side-order-container">
                            <div class="side-order-left">Товаров:</div>
                            <div class="side-order-right side-order-count"><?= $this->cart['count_items'] ?> шт.</div>
                        </div>
                        <div class="side-order-container">
                            <div class="side-order-left">На сумму:</div>
                            <div class="side-order-right side-order-price"><?= $this->cart['discount_sum'] ?? $this->cart['sum'] ?> р.</div>
                        </div>
                        <?php if (!empty($this->cart['discount_sum'])): ?>
                            <div class="side-order-container">
                                <div class="side-order-left"></div>
                                <div class="side-order-right "><div class="side-order-oldprice"><?= $this->cart['sum'] ?> р.</div></div>
                            </div>
                        <?php endif; ?>
                        <div class="side-order-container">
                            <div class="side-order-left">Доставка:</div>
                            <div class="side-order-right side-order-delivery">бесплатно</div>
                        </div>
                    </div>
                    <div class="side-order-footer">
                        <div class="side-order-container">
                            <div class="side-order-left">Итого:</div>
                            <div class="side-order-right side-order-total"><?= $this->cart['discount_sum'] ?? $this->cart['sum'] ?> р.</div>
                        </div>
                        <button type="submit" class="btn">Оформить заказ</button>
                    </div>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="basket-empty">
            <p>Ваша корзина пуста</p>
            <p>Нажмите <a href="/catalog/">здесь</a>, чтобы продолжить покупки</p>
        </div>
    <?php endif; ?>
</div>

<script>
    $(function () {
        /* сворачивание/разворачивание пунктов оформления заказа */
        $('.order-item-title').on('click', function (e) {
            e.preventDefault();
            $(this).next('.order-item-container').slideToggle();
        });

        /* показ/скрытие блока с адресом доставки при выборе службы доставки */
        $('input[name=delivery]').on('change', function () {
            if ($(this).val() === '1') $(this).parents('.order-item').next().addClass('hidden');
            else $(this).parents('.order-item').next().removeClass('hidden');
        });

        /* выбор профиля доставки */
        $('#p_profile, #j_profile').on('change', function () {
            let option = $(this).find('option:selected');

            $(this).parents('.order-item-slider').find('input[name=p_name]').val(option.data('p_name'));
            $(this).parents('.order-item-slider').find('input[name=p_email]').val(option.data('p_email'));
            $(this).parents('.order-item-slider').find('input[name=p_phone]').val(option.data('p_phone'));

            $(this).parents('.order-item-slider').find('input[name=j_name]').val(option.data('j_name'));
            $(this).parents('.order-item-slider').find('input[name=j_email]').val(option.data('j_email'));
            $(this).parents('.order-item-slider').find('input[name=j_phone]').val(option.data('j_phone'));

            $(this).parents('.order-item-slider').find('input[name=company]').val(option.data('company'));
            $(this).parents('.order-item-slider').find('input[name=j_address]').val(option.data('j_address'));
            $(this).parents('.order-item-slider').find('input[name=inn]').val(option.data('inn'));
            $(this).parents('.order-item-slider').find('input[name=kpp]').val(option.data('kpp'));

            $('input[name=city_id]').val(option.data('city_id'));
            $('input[name=city]').val(option.data('city'));
            $('input[name=street_id]').val(option.data('street_id'));
            $('input[name=street]').val(option.data('street'));
            $('input[name=house]').val(option.data('house'));
            $('input[name=building]').val(option.data('building'));
            $('input[name=flat]').val(option.data('flat'));
            $('textarea[name=comment]').val(option.data('comment'));
        });

        /* поиск населенного пункта */
        $('#delivery_city').onDelay({
            action: 'input',
            interval: 1000
        }, function(){
            let $this = $(this),
                $data = $this.val();

            if ($data.length > 2) {
                $this.parent().find('.message_error').html('').hide();
                $.ajax({
                    method: "POST",
                    dataType: 'json',
                    url: "/location/findCity/",
                    data: {city: $data},
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(data){console.log(data);
                        $('#loader').hide();
                        if (data.result) {
                            $this.parent().find('.message_error').html('').hide();
                            $this.next().html(data.cities).show();
                        }
                        else {
                            $this.parent().find('.message_error').html(data.message).show();
                            $this.attr('data-id', '');
                            $this.next().html('').hide();
                        }
                    }
                });
            } else {
                $this.parent().find('.message_error').html('Введите более 2-х букв').show();
                $this.attr('data-id', '');
                $this.next().html('').hide();
            }
        });

        /* поиск улицы */
        $('#delivery_street').onDelay({
            action: 'input',
            interval: 1000
        }, function(){
            let $this = $(this),
                street = $this.val(),
                city_id = $('input[name=city_id]').val();

            if (city_id) {
                $this.parent().find('.message_error').html('').hide();

                if (street.length > 2) {
                    $.ajax({
                        method: "POST",
                        dataType: 'json',
                        url: "/location/findStreet/",
                        data: {street: street, city_id: city_id},
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        success: function(data){console.log(data);
                            $('#loader').hide();
                            if (data.result) {
                                $this.parent().find('.message_error').html('').hide();
                                $this.next().html(data.streets).show();
                            }
                            else {
                                $this.parent().find('.message_error').html(data.message).show();
                                $this.attr('data-id', '');
                                $this.next().html('').hide();
                            }
                        }
                    });
                } else {
                    $this.parent().find('.message_error').html('Введите более 2-х букв').show();
                    $this.attr('data-id', '');
                    $this.next().html('').hide();
                }
            }
            else {
                $this.next().html('').hide();
                $this.parent().find('.message_error').html('Выберите населенный пункт').show();
            }
        });

        /* выбор города и улицы */
        $('.order-item-search-result').on('click', 'a', function (e) {
            e.preventDefault();
            let ul = $(this).parent().parent(),
                input = ul.prev();

            input.val($(this).html()).attr('data-id', $(this).data('id'));
            input.prev().val($(this).data('id'));
            ul.hide();
        });

        /* оформление заказа */
        $('#order button[type=submit]').on('click', function (e) {
            e.preventDefault();
            let form = $('#order'),
                error = [];

            if ($('#order input[name=type]:checked').val() === '1') { // физические лица
                $('.order-user-physical input.required, .order-user-physical select.required').each(function () {
                    if (($(this).attr('name') === 'p_profile' && !checkUserData($(this).val(), 'numbers')) ||
                        ($(this).attr('name') === 'p_name' && !checkUserData($(this).val(), 'rus_eng')) ||
                        ($(this).attr('name') === 'p_email' && !checkUserData($(this).val(), 'email')) ||
                        ($(this).attr('name') === 'p_phone' && !checkUserData($(this).val(), 'phone')))
                    {
                        error.push(true);
                        $(this).next().html('Проверьте правильность заполнения поля').show();
                    }
                    else {
                        error.push(false);
                        $(this).next().html('').hide();
                    }
                });
            } else if ($('#order input[name=type]:checked').val() === '2') { // юридические лица
                $('.order-user-juridical input.required, .order-user-juridical select.required').each(function () {
                    if (($(this).attr('name') === 'j_profile' && !checkUserData($(this).val(), 'numbers')) ||
                        ($(this).attr('name') === 'j_name' && !checkUserData($(this).val(), 'rus_eng')) ||
                        ($(this).attr('name') === 'j_email' && !checkUserData($(this).val(), 'email')) ||
                        ($(this).attr('name') === 'j_phone' && !checkUserData($(this).val(), 'phone')) ||
                        ($(this).attr('name') === 'company' && !checkUserData($(this).val(), 'rus')) ||
                        ($(this).attr('name') === 'j_address' && !checkUserData($(this).val(), 'rus_num')) ||
                        ($(this).attr('name') === 'inn' && !checkUserData($(this).val(), 'numbers')))
                    {
                        error.push(true);
                        $(this).next().html('Проверьте правильность заполнения поля').show();
                    }
                    else {
                        error.push(false);
                        $(this).next().html('').hide();
                    }
                });
            } else error.push(true); // непонятное лицо

            if (['2', '3'].indexOf($('#order input[name=delivery]:checked').val()) !== -1) { // доставка, требующая адрес
                $('.order-item-delivery input.required').each(function () {
                    if (($(this).attr('name') === 'city_id' && !checkUserData($(this).val(), 'numbers')) ||
                        ($(this).attr('name') === 'city' && !checkUserData($(this).val(), 'rus_num')) ||
                        ($(this).attr('name') === 'street_id' && !checkUserData($(this).val(), 'numbers')) ||
                        ($(this).attr('name') === 'street' && !checkUserData($(this).val(), 'rus_num')) ||
                        ($(this).attr('name') === 'house' && !checkUserData($(this).val(), 'rus_num')))
                    {
                        error.push(true);
                        $(this).parent().find('.message_error').html('Не заполнено обязательно поле').show();
                    }
                    else {
                        error.push(false);
                        $(this).parent().find('.message_error').html('').hide();
                    }
                });
            }

            if (error.indexOf(true) === -1) form.submit();
        });
    });
</script>
