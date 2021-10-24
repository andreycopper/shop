<div id="basket" class="tab basket-items active">
    <? if (!empty($cart['items']) && is_array($cart['items'])): ?>
        <? foreach ($cart['items'] as $cart_item): ?>
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
                                    <?= $cart_item->price_discount ?
                                            number_format($cart_item->price_discount, 0, '.', ' ') :
                                            number_format($cart_item->price, 0, '.', ' ') ?>
                                </span> <?= CURRENCY ?>
                            </div>
                            <? if (!empty($cart_item->price_discount)): ?>
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
                                       data-value="<?= $cart_item->count ?>"
                                       max="<?= $cart_item->quantity ?>">
                                <div class="basket-item-plus"></div>
                            </div>
                            <div class="basket-item-measure"><?= $cart_item->unit ?></div>
                        </div>
                        <div class="basket-item-total">
                            <div class="basket-item-totalprice">
                                <span>
                                    <?= $cart_item->sum_discount ?
                                            number_format($cart_item->sum_discount, 0, '.', ' ') :
                                            number_format($cart_item->sum, 0, '.', ' ') ?>
                                </span> <?= CURRENCY ?>
                            </div>
                            <? if (!empty($cart_item->price_discount)): ?>
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
        <? endforeach; ?>
    <? else: ?>
        <div class="basket-empty">
            <p>Ваша корзина пуста</p>
            <p>Нажмите <a href="/catalog/">здесь</a>, чтобы продолжить покупки</p>
        </div>
    <? endif; ?>
</div>
