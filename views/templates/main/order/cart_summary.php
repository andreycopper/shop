<?php if (!empty($this->cart)): ?>
    <div class="basket-summary">
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
                                <?= $this->cart['economy'] > 0 ?
                                    number_format($this->cart['sum_discount'], 0, '.', ' ') :
                                    number_format($this->cart['sum'], 0, '.', ' ') ?>
                            </span> <?= CURRENCY ?>
                    </div>
                    <? if ($this->cart['economy'] > 0): ?>
                        <div class="basket-order-oldprice">
                            <b><span><?= number_format($this->cart['sum'], 0, '.', ' ') ?></span> <?= CURRENCY ?></b>
                        </div>
                        <div class="basket-order-economy">
                            Экономия <b><span><?= number_format($this->cart['economy'], 0, '.', ' ') ?></span> <?= CURRENCY ?></b>
                        </div>
                    <? endif; ?>
                    <? if ($this->cart['sum_nds'] || $this->cart['sum_discount_nds']): ?>
                        <div class="basket-order-nds">
                            В т.ч. НДС:
                            <span>
                                    <?= $this->cart['sum_discount_nds'] ?
                                        number_format($this->cart['sum_discount_nds'], 2, '.', ' ') :
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