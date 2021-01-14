<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator"></span>
            <span>Оформление заказа</span>
        </div>

        <h1>Оформление заказа</h1>
    </div>

    <form action="/orders/finish/" method="post" class="catalog-container">
        <div class="main-section">
            <div class="order-item">
                <a href="" class="order-item-title order-user">Покупатель</a>

                <div class="order-item-container radio-container">
                    <div class="order-user-type">
                        <label class="radio checked">
                            <input type="radio" name="type" value="1" data-target="order-user-physical" checked>
                            <span class="order-item-name">Физическое лицо</span>
                        </label>

                        <label class="radio">
                            <input type="radio" name="type" value="2" data-target="order-user-juridical">
                            <span class="order-item-name">Юридическое лицо</span>
                        </label>
                    </div>

                    <div class="order-item-slider order-user-physical">
                        <label for="p_profile">Профиль доставки</label>
                        <select name="p_profile" id="p_profile">
                            <option value="0" selected>Новый профиль</option>
                            <? if (!empty($this->profiles) && is_array($this->profiles)):
                                foreach ($this->profiles as $profile):
                                    if ($profile->user_type_id !== '1') continue; ?>
                                    <option value="<?= $profile->id ?>"
                                            data-user_type_id="<?= $profile->user_type_id ?>"
                                            data-name="<?= $profile->name ?>"
                                            data-email="<?= $profile->email ?>"
                                            data-phone="<?= $profile->phone ?>"
                                            data-index=""
                                            data-city="<?= $profile->city ?>"
                                            data-address="<?= $profile->address ?>"
                                            data-comment="<?= $profile->comment ?>"
                                    >
                                        <?= $profile->city . ', ' . $profile->address ?>
                                    </option>
                                <? endforeach;
                            endif; ?>
                        </select>

                        <label for="p_name">Контактное лицо <span class="required">*</span></label>
                        <input type="text" name="p_name" id="p_name" value="<?= $this->user ? ($this->user['last_name'] . ' ' . $this->user['name']) : '' ?>">

                        <label for="p_email">E-mail <span class="required">*</span></label>
                        <input type="text" name="p_email" id="p_email" value="<?= $this->user ? $this->user['email'] : '' ?>">

                        <label for="p_phone">Телефон <span class="required">*</span></label>
                        <input type="text" name="p_phone" id="p_phone" value="<?= $this->user ? $this->user['phone'] : '' ?>">
                    </div>

                    <div class="order-item-slider order-user-juridical">
                        <label for="j_profile">Профиль доставки</label>
                        <select name="j_profile" id="j_profile">
                            <option value="0" selected>Новый профиль</option>
                            <? if (!empty($this->profiles) && is_array($this->profiles)):
                                foreach ($this->profiles as $profile):
                                    if ($profile->user_type_id !== '2') continue; ?>
                                    <option value="<?= $profile->id ?>"
                                            data-user_type_id="<?= $profile->user_type_id ?>"
                                            data-name="<?= $profile->name ?>"
                                            data-email="<?= $profile->email ?>"
                                            data-phone="<?= $profile->phone ?>"
                                            data-index=""
                                            data-city="<?= $profile->city ?>"
                                            data-address="<?= $profile->address ?>"
                                            data-comment="<?= $profile->comment ?>"
                                            data-company='<?= $profile->company ?>'
                                            data-address_legal="<?= $profile->address_legal ?>"
                                            data-inn="<?= $profile->inn ?>"
                                            data-kpp="<?= $profile->kpp ?>"
                                    >
                                        <?= $profile->company . ', ' . $profile->city . ', ' . $profile->address ?>
                                    </option>
                                <? endforeach;
                            endif; ?>
                        </select>

                        <label for="j_name">Контактное лицо <span class="required">*</span></label>
                        <input type="text" name="j_name" id="j_name" value="<?= $this->user ? ($this->user['last_name'] . ' ' . $this->user['name']) : '' ?>">

                        <label for="j_email">E-mail <span class="required">*</span></label>
                        <input type="text" name="j_email" id="j_email" value="<?= $this->user ? $this->user['email'] : '' ?>">

                        <label for="j_phone">Телефон <span class="required">*</span></label>
                        <input type="text" name="j_phone" id="j_phone" value="<?= $this->user ? $this->user['phone'] : '' ?>">

                        <label for="company">Название компании <span class="required">*</span></label>
                        <input type="text" name="company" id="company">

                        <label for="j_address">Юридический адрес <span class="required">*</span></label>
                        <input type="text" name="j_address" id="j_address">

                        <label for="inn">ИНН <span class="required">*</span></label>
                        <input type="text" name="inn" id="inn">

                        <label for="kpp">КПП</label>
                        <input type="text" name="kpp" id="kpp">
                    </div>
                </div>
            </div>

            <div class="order-item">
                <a href="" class="order-item-title order-region">Адрес доставки</a>

                <div class="order-item-container">
                    <div class="relative visible">
                        <label for="delivery_city">Населенный пункт <span class="required">*</span></label>
                        <input type="text" name="city" id="delivery_city">
                        <ul class="order-item-search-result"></ul>
                    </div>

                    <div class="relative">
                        <label for="delivery_address">Адрес доставки <span class="required">*</span></label>
                        <input type="text" name="address" id="delivery_address">
                    </div>

                    <label for="delivery_comment">Комментарий к заказу</label>
                    <textarea name="comment" id="delivery_comment"></textarea>

                    <div class="order-item-comment">
                        Выберите профиль доставки или введите свой город и адрес.
                    </div>
                </div>
            </div>

            <div class="order-item">
                <a href="" class="order-item-title order-delivery">Доставка</a>

                <div class="order-item-container">
                    <? if (!empty($this->deliveries) && is_array($this->deliveries)): ?>
                        <? foreach ($this->deliveries as $delivery): ?>
                            <label class="radio <?= $delivery->id === '1' ? 'checked' : '' ?>">
                                <input type="radio" name="delivery" value="<?= $delivery->id ?>" <?= $delivery->id === '1' ? 'checked' : '' ?>>
                                <span class="order-item-name"><?= $delivery->name ?></span>
                                <span class="order-item-price">
                                    Стоимость:
                                    <? if (!empty($delivery->price)): ?>
                                        <span><?= $delivery->price ?></span> р.
                                    <? elseif (!empty($delivery->price_from) || !empty($delivery->price_to)): ?>
                                        <span>
                                            <?=
                                            ($delivery->price_from ? ('от ' . $delivery->price_from . ' ') : '') .
                                            ($delivery->price_to ? ('до ' . $delivery->price_to) : '')
                                            ?>
                                        </span> р.
                                    <? else: ?>
                                        бесплатно
                                    <? endif; ?>
                                </span>
                                <span class="order-item-time">Срок доставки: <span><?= $delivery->time ?></span></span>
                                <span class="order-item-desc"><?= $delivery->description ?></span>
                            </label>
                        <? endforeach; ?>
                    <? endif; ?>
                </div>
            </div>

            <div class="order-item">
                <a href="" class="order-item-title order-payment">Оплата</a>

                <div class="order-item-container radio-container">
                    <? if (!empty($this->payments) && is_array($this->payments)): ?>
                        <? foreach ($this->payments as $payment): ?>
                            <label class="radio <?= $payment->id === '1' ? 'checked' : '' ?>">
                                <input type="radio" name="payment" value="<?= $payment->id ?>" <?= $payment->id === '1' ? 'checked' : '' ?>>
                                <span class="order-item-name"><?= $payment->name ?></span>
                            </label>
                        <? endforeach; ?>
                    <? endif; ?>
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

                    <? if (!empty($this->cart['items']) && is_array($this->cart['items'])):
                        foreach ($this->cart['items'] as $cart_item): ?>
                            <div class="order-product">
                                <div class="order-product-desc">
                                    <div class="order-product-cell order-product-image">
                                        <a href=""><img src="/uploads/catalog/<?= $cart_item->product_id ?>/<?= $cart_item->preview_image ?>" alt=""></a>
                                    </div>
                                    <div class="order-product-cell order-product-title">
                                        <a href=""><?= $cart_item->name ?></a>
                                        <span>Производитель <?= $cart_item->vendor ?></span>
                                    </div>
                                    <div class="order-product-cell order-product-weight">
                                        <i>Вес</i>
                                        0 кг
                                    </div>
                                    <div class="order-product-cell order-product-dicsount">
                                        <i>Скидка</i>
                                        <?= $cart_item->discount ?>%
                                    </div>
                                </div>
                                <div class="order-product-val">
                                    <div class="order-product-cell order-product-values">
                                        <i>Цена</i>
                                        <div class="order-product-price">
                                            <?= $cart_item->discount_price ?? $cart_item->price ?> <?= $cart_item->currency ?>
                                        </div>
                                        <? if (!empty($cart_item->discount_price)): ?>
                                            <span class="order-product-oldprice"><?= $cart_item->price ?> <?= $cart_item->currency ?></span>
                                        <? endif; ?>
                                    </div>
                                    <div class="order-product-cell order-product-totalcount">
                                        <i>Кол-во</i>
                                        <div class="order-product-count"><?= $cart_item->count ?> <?= $cart_item->unit ?></div>
                                    </div>
                                    <div class="order-product-cell order-product-totalvalues">
                                        <i>Сумма</i>
                                        <div class="order-product-total">
                                            <?= $cart_item->discount_sum ?? $cart_item->sum ?> <?= $cart_item->currency ?>
                                        </div>
                                        <? if (!empty($cart_item->discount_sum)): ?>
                                            <div class="order-product-oldtotal"><?= $cart_item->sum ?> <?= $cart_item->currency ?></div>
                                        <? endif; ?>
                                    </div>
                                </div>
                            </div>
                        <? endforeach;
                    endif; ?>
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
                    <? if (!empty($this->cart['discount_sum'])): ?>
                        <div class="side-order-container">
                            <div class="side-order-left"></div>
                            <div class="side-order-right "><div class="side-order-oldprice"><?= $this->cart['sum'] ?> р.</div></div>
                        </div>
                    <? endif; ?>
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
</div>
