<div class="container">
    <div class="main-header">
        <div class="breadcrumbs">
            <a href="">Главная</a><span class="breadcrumbs-separator"></span>
            <span>Корзина</span>
        </div>

        <h1>Корзина</h1>
    </div>

    <? if (!empty($this->cart) || !empty($this->favorite)):
        if (!empty($this->cart)): ?>
            <div class="basket-header">
                <div class="basket-coupon">
                    <div class="basket-coupon-title">Введите код купона для скидки:</div>
                    <form action="" method="get" class="basket-coupon-form">
                        <label for="">
                            <input type="text" name="coupon" value="<?= \System\Request::get('coupon') ?? '' ?>">
                        </label>
                        <input type="submit" value="">
                    </form>
                    <div class="message_error <?= $this->coupon_error ? 'block' : '' ?>">
                        Введен неверный, неактуальный или уже использованный купон
                    </div>
                    <? if (!empty($this->coupon) && empty($this->coupon_error)): ?>
                        <div class="message_success block">Использован купон "<?= $this->coupon->name ?>" -<?= $this->coupon->discount ?>%</div>
                    <? endif; ?>
                </div>
                <div class="basket-order">
                    <div class="basket-order-values">
                        <div class="basket-order-text">
                            <div class="basket-order-total">
                                В корзине товаров: <span class="basket-order-total-count"><?= $this->cart['count_items'] ?></span>
                            </div>
                            <div class="basket-order-clear">
                                <a href="" class="btn-gray">Очистить</a>
                            </div>
                        </div>
                        <div class="basket-order-prices">
                            <div class="basket-order-total">Итого:</div>
                            <div class="basket-order-price">
                                <span>
                                    <?= $this->cart['discount_sum'] ?
                                        number_format($this->cart['discount_sum'], 0, '.', ' ') :
                                        number_format($this->cart['sum'], 0, '.', ' ') ?>
                                </span> <?= CURRENCY ?>
                            </div>
                            <? if (!empty($this->cart['discount_sum'])): ?>
                                <div class="basket-order-oldprice">
                                    <b><span><?= number_format($this->cart['sum'], 0, '.', ' ') ?></span> <?= CURRENCY ?></b>
                                </div>
                                <div class="basket-order-economy">
                                    Экономия <b><span><?= number_format($this->cart['economy'], 0, '.', ' ') ?></span> <?= CURRENCY ?></b>
                                </div>
                            <? endif; ?>
                            <? if ($this->cart['sum_nds'] || $this->cart['discount_sum_nds']): ?>
                                <div class="basket-order-nds">
                                    В т.ч. НДС:
                                    <span>
                                        <?= $this->cart['discount_sum_nds'] ?
                                            number_format($this->cart['discount_sum_nds'], 2, '.', ' ') :
                                            number_format($this->cart['sum_nds'], 2, '.', ' ') ?>
                                    </span> <?= CURRENCY ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                    <div class="basket-order-buttons">
                        <a href="/orders/" class="btn-alt">Оформить заказ</a>
                        <a href="" class="btn order-action" data-target="qorder">Быстрый заказ</a>
                    </div>
                </div>
            </div>
        <? endif; ?>

        <div class="basket-message <?= $this->cart['message'] ? 'block' : '' ?>"><?= $this->cart['message'] ?></div>

        <div class="basket-container">
            <div class="basket-tabs">
                <a href="" class="active" data-target="basket">
                    В корзине
                    <? if (intval($this->cart['count_items']) > 0): ?>
                        <span class="cart-count"><?= $this->cart['count_items'] ?></span>
                    <? endif; ?>
                </a>
                <? if (intval($this->cart['count_absent']) > 0): ?>
                    <a href="" data-target="absent">
                        Нет в наличии
                        <span class="absent-count"><?= $this->cart['count_absent'] ?></span>
                    </a>
                <? endif; ?>
                <a href="" data-target="favorite">
                    Отложено
                </a>
            </div>

            <div id="basket" class="tab basket-items active">
                <? if (!empty($this->cart['items']) && is_array($this->cart['items'])):
                    foreach ($this->cart['items'] as $cart_item): ?>
                        <div class="basket-item">
                            <div class="basket-item-image">
                                <a href=""><img src="/uploads/catalog/<?= $cart_item->product_id ?>/<?= $cart_item->preview_image ?>" alt=""></a>
                            </div>
                            <div class="basket-item-desc">
                                <div class="basket-item-title">
                                    <div class="basket-item-name"><a href=""><?= $cart_item->name ?></a></div>
                                    <? if (!empty($cart_item->discount)): ?>
                                        <div class="basket-item-discount">
                                            Скидка: <span><?= $cart_item->discount ?>%</span>
                                            (купон "Тест")
                                        </div>
                                    <? endif; ?>
                                    <div class="basket-item-type">Тип цены: <span><?= $cart_item->price_type ?></span></div>
                                    <div class="basket-item-links">
                                        <a href="" class="basket-item-favorite">Отложить</a>
                                        <a href="" class="basket-item-del" data-id="<?= $cart_item->product_id ?>">Удалить</a>
                                    </div>
                                </div>
                                <div class="basket-item-values">
                                    <div class="basket-item-prices">
                                        <div class="basket-item-price">
                                            <span>
                                                <?= $cart_item->discount_price ?
                                                        number_format($cart_item->discount_price, 0, '.', ' ') :
                                                        number_format($cart_item->price, 0, '.', ' ') ?>
                                            </span> <?= CURRENCY ?>
                                        </div>
                                        <? if (!empty($cart_item->discount_price)): ?>
                                            <div class="basket-item-oldprice">
                                                <span><?= number_format($cart_item->price, 0, '.', ' ') ?></span> <?= CURRENCY ?>
                                            </div>
                                        <? endif; ?>
                                        <div class="basket-item-measure">цена за 1 <?= $cart_item->unit ?></div>
                                    </div>

                                    <div class="basket-item-count">
                                        <div class="basket-item-countblock">
                                            <div class="basket-item-minus"></div>
                                            <input type="text"
                                                   class="basket-item-quantity"
                                                   data-id="<?= $cart_item->product_id ?>"
                                                   value="<?= $cart_item->count ?>"
                                                   max="<?= $cart_item->quantity ?>">
                                            <div class="basket-item-plus"></div>
                                        </div>
                                        <div class="basket-item-measure"><?= $cart_item->unit ?></div>
                                    </div>
                                    <div class="basket-item-total">
                                        <div class="basket-item-totalprice">
                                            <span>
                                                <?= $cart_item->discount_sum ?
                                                        number_format($cart_item->discount_sum, 0, '.', ' ') :
                                                        number_format($cart_item->sum, 0, '.', ' ') ?>
                                            </span> <?= CURRENCY ?>
                                        </div>
                                        <? if (!empty($cart_item->discount_price)): ?>
                                            <div class="basket-item-oldtotalprice">
                                                <span><?= number_format($cart_item->sum, 0, '.', ' ') ?></span> <?= CURRENCY ?>
                                            </div>
                                            <div class="basket-item-economy">
                                                Экономия<br>
                                                <b>
                                                    <span>
                                                        <?= number_format($cart_item->sum_economy, 0, '.', ' ') ?>
                                                    </span> <?= CURRENCY ?>
                                                </b>
                                            </div>
                                        <? endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? endforeach;
                else: ?>
                    <div class="basket-empty">
                        <p>Ваша корзина пуста</p>
                        <p>Нажмите <a href="/catalog/">здесь</a>, чтобы продолжить покупки</p>
                    </div>
                <? endif; ?>
            </div>

            <? if (!empty($this->cart['absent']) && is_array($this->cart['absent'])): ?>
                <div id="absent" class="tab basket-items">
                    <? foreach ($this->cart['absent'] as $absent_item): ?>
                        <div class="basket-item">
                            <div class="basket-item-image">
                                <a href=""><img src="/uploads/catalog/<?= $absent_item->product_id ?>/<?= $absent_item->preview_image ?>" alt=""></a>
                            </div>
                            <div class="basket-item-desc">
                                <div class="basket-item-title">
                                    <div class="basket-item-name"><a href=""><?= $absent_item->name ?></a></div>
                                    <div class="basket-item-type">Тип цены: <span><?= $absent_item->price_type ?></span></div>
                                    <div class="basket-item-links">
                                        <a href="" class="basket-item-favorite">Отложить</a>
                                        <a href="" class="basket-item-del">Удалить</a>
                                    </div>
                                </div>
                                <div class="basket-item-values">
                                    <div class="basket-item-prices">
                                        <div class="basket-item-price">
                                            <?= $absent_item->discount_price ?
                                                number_format($absent_item->discount_price, 0, '.', ' ')  :
                                                number_format($absent_item->price, 0, '.', ' ') ?> <?= CURRENCY ?>
                                        </div>
                                        <? if (!empty($absent_item->discount_price)): ?>
                                            <div><span class="basket-item-oldprice"><?= number_format($absent_item->price, 0, '.', ' ') ?> <?= CURRENCY ?></span></div>
                                        <? endif; ?>
                                        <div class="basket-item-measure">цена за 1 <?= $absent_item->unit ?></div>
                                    </div>

                                    <div class="basket-item-count">
                                        <div class="basket-item-countblock">
                                            <div class="basket-item-minus"></div>
                                            <div class="basket-item-input">
                                                <input type="text" value="<?= $absent_item->count ?>">
                                            </div>
                                            <div class="basket-item-plus"></div>
                                        </div>
                                        <div class="basket-item-measure"><?= $absent_item->unit ?></div>
                                    </div>
                                    <div class="basket-item-total">
                                        <div class="basket-item-totalprice">
                                            <?= $absent_item->discount_sum ?
                                                number_format($absent_item->discount_sum, 0, '.', ' ') :
                                                number_format($absent_item->sum, 0, '.', ' ') ?> <?= CURRENCY ?>
                                        </div>
                                        <? if (!empty($absent_item->discount_price)): ?>
                                            <div>
                                                <span class="basket-item-oldtotalprice"><?= number_format($absent_item->sum, 0, '.', ' ') ?> <?= CURRENCY ?></span>
                                            </div>
                                            <div class="basket-item-economy">
                                                Экономия<br>
                                                <span><?= number_format($absent_item->sum - $absent_item->discount_sum, 0, '.', ' ') ?> <?= CURRENCY ?></span>
                                            </div>
                                        <? endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            <? endif; ?>

            <div id="favorite" class="tab basket-items">
                <div class="basket-item">
                    <div class="basket-item-image">
                        <a href=""><img src="/uploads/basket/3.jpg" alt=""></a>
                    </div>
                    <div class="basket-item-desc">
                        <div class="basket-item-title">
                            <div class="basket-item-name"><a href="">Cobra RU 860</a></div>
                            <div class="basket-item-discount">Скидка: <span>100%</span></div>
                            <div class="basket-item-type">Тип цены: <span>Розничная цена</span></div>
                            <div class="basket-item-links">
                                <a href="" class="basket-item-favorite">В корзину</a>
                                <a href="" class="basket-item-del">Удалить</a>
                            </div>
                        </div>
                        <div class="basket-item-values">
                            <div class="basket-item-prices">
                                <div class="basket-item-price">0 р.</div>
                                <div><span class="basket-item-oldprice">8 800 <?= CURRENCY ?></span></div>
                                <div class="basket-item-measure">цена за 1 шт</div>
                            </div>
                            <div class="basket-item-count">
                                <div class="basket-item-countblock">
                                    <div class="basket-item-minus"></div>
                                    <div class="basket-item-input">
                                        <input type="text" value="1">
                                    </div>
                                    <div class="basket-item-plus"></div>
                                </div>
                                <div class="basket-item-measure">шт</div>
                            </div>
                            <div class="basket-item-total">
                                <div class="basket-item-totalprice">0 <?= CURRENCY ?></div>
                                <div><span class="basket-item-oldtotalprice">8 800 <?= CURRENCY ?></span></div>
                                <div class="basket-item-economy">Экономия <span>8 800 <?= CURRENCY ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <? if (!empty($this->cart)): ?>
            <div class="basket-footer">
                <div class="basket-coupon">
                    <div class="basket-coupon-title">Введите код купона для скидки:</div>
                    <form action="" method="get" class="basket-coupon-form">
                        <label for="">
                            <input type="text" name="coupon" value="<?= \System\Request::get('coupon') ?? '' ?>">
                        </label>
                        <input type="submit" value="">
                    </form>
                    <div class="message_error <?= $this->coupon_error ? 'block' : '' ?>">
                        Введен неверный, неактуальный или уже использованный купон
                    </div>
                    <? if (!empty($this->coupon) && empty($this->coupon_error)): ?>
                        <div class="message_success block">Использован купон "<?= $this->coupon->name ?>" -<?= $this->coupon->discount ?>%</div>
                    <? endif; ?>
                </div>
                <div class="basket-order">
                    <div class="basket-order-values">
                        <div class="basket-order-text">
                            <div class="basket-order-total">
                                В корзине товаров: <span class="basket-order-total-count"><?= $this->cart['count_items'] ?></span>
                            </div>
                            <div class="basket-order-clear">
                                <a href="" class="btn-gray">Очистить</a>
                            </div>
                        </div>
                        <div class="basket-order-prices">
                            <div class="basket-order-total">Итого:</div>
                            <div class="basket-order-price">
                                <span>
                                    <?= $this->cart['discount_sum'] ?
                                        number_format($this->cart['discount_sum'], 0, '.', ' ') :
                                        number_format($this->cart['sum'], 0, '.', ' ') ?>
                                </span> <?= CURRENCY ?>
                            </div>
                            <? if (!empty($this->cart['discount_sum'])): ?>
                                <div class="basket-order-oldprice">
                                    <b><span><?= number_format($this->cart['sum'], 0, '.', ' ') ?></span> <?= CURRENCY ?></b>
                                </div>
                                <div class="basket-order-economy">
                                    Экономия <b><span><?= number_format($this->cart['economy'], 0, '.', ' ') ?></span> <?= CURRENCY ?></b>
                                </div>
                            <? endif; ?>
                            <? if ($this->cart['sum_nds'] || $this->cart['discount_sum_nds']): ?>
                                <div class="basket-order-nds">
                                    В т.ч. НДС:
                                    <span>
                                        <?= $this->cart['discount_sum_nds'] ?
                                            number_format($this->cart['discount_sum_nds'], 2, '.', ' ') :
                                            number_format($this->cart['sum_nds'], 2, '.', ' ') ?>
                                    </span> <?= CURRENCY ?>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                    <div class="basket-order-buttons">
                        <a href="/orders/" class="btn-alt">Оформить заказ</a>
                        <a href="" class="btn order-action" data-target="qorder">Быстрый заказ</a>
                    </div>
                </div>
            </div>
        <? endif; ?>
    <? else: ?>
        <div class="basket-empty">
            <p>Ваша корзина пуста</p>
            <p>Нажмите <a href="/catalog/">здесь</a>, чтобы продолжить покупки</p>
        </div>
    <? endif; ?>
</div>
