<? if (!empty($cart['absent']) && is_array($cart['absent'])): ?>
    <div id="absent" class="tab basket-items">
        <? foreach ($cart['absent'] as $absent_item): ?>
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
                                <?= $absent_item->price_discount ?
                                    number_format($absent_item->price_discount, 0, '.', ' ')  :
                                    number_format($absent_item->price, 0, '.', ' ') ?> <?= CURRENCY ?>
                            </div>
                            <? if (!empty($absent_item->price_discount)): ?>
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
                                <?= $absent_item->sum_discount ?
                                    number_format($absent_item->sum_discount, 0, '.', ' ') :
                                    number_format($absent_item->sum, 0, '.', ' ') ?> <?= CURRENCY ?>
                            </div>
                            <? if (!empty($absent_item->price_discount)): ?>
                                <div>
                                    <span class="basket-item-oldtotalprice"><?= number_format($absent_item->sum, 0, '.', ' ') ?> <?= CURRENCY ?></span>
                                </div>
                                <div class="basket-item-economy">
                                    Экономия<br>
                                    <span><?= number_format($absent_item->sum - $absent_item->sum_discount, 0, '.', ' ') ?> <?= CURRENCY ?></span>
                                </div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </div>
<? endif; ?>

