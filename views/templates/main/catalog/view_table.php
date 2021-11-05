<table class="product-tablecontainer">
    <?php foreach ($this->items as $item): ?>
        <tr class="product-itemtable">
            <td class="product-itemtable-image">
                <a href="<?= $item->id ?>/"><img src="/uploads/catalog/<?=$item->id?>/<?=$item->preview_image?>" alt=""></a>
                <a class="product-itemtable-fastview info" data-target="fast" data-url="/catalog/fastView/"></a>
            </td>
            <td class="product-itemtable-description">
                <div class="product-item-title">
                    <a href="<?= $item->id ?>/"><?= $item->name ?></a>
                </div>
                <div class="product-itemtable-rating">
                    <span class="star star-active"></span>
                    <span class="star star-active"></span>
                    <span class="star star-active"></span>
                    <span class="star star-inactive"></span>
                    <span class="star star-inactive"></span>
                </div>
                <div class="product-itemtable-count">
                    <span class="icon <?= (($item->quantity > 0) ? 'ok' : 'no') ?>"></span>
                    <?= (($item->quantity > 10) ? 'Много' : (($item->quantity > 0) ? 'Мало' : 'Отсутствует')) ?>
                    <span class="product-itemtable-articul">Артикул: <?= $item->articul ?></span>
                </div>
                <div class="product-itemtable-text">
                    <?= ($item->preview_text ?? mb_substr(strip_tags($item->detail_text), 0, mb_strpos(strip_tags($item->detail_text), '.', 150) + 1)) ?>
                </div>
            </td>
            <td class="product-itemtable-prices">
                <?php if (!empty($item->prices) && is_array($item->prices)): ?>
                    <?php foreach ($item->prices as $price): ?>
                        <?php if (count($item->prices) > 1): ?>
                            <div class="product-price-title"><?= $price->price_type ?></div>
                        <?php endif; ?>

                        <div class="product-itemtable-priceblock <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                            <div class="product-itemtable-price">
                                <span class="product-itemtable-value">
                                    <?= number_format(round($price->price * (100 - $price->discount) / 100), 0, '.', ' ') ?>
                                </span>
                                <span class="product-itemtable-currency"><?= $price->currency ?></span>
                                <span class="product-itemtable-measure">/<?= $item->unit ?></span>
                            </div>

                            <?php if (!empty($price->discount)): ?>
                                <div class="product-itemtable-oldprice <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                                    <span class="product-itemtable-value"><?= number_format($price->price, 0, '.', ' ') ?></span>
                                    <span class="product-itemtable-currency"><?= $price->currency ?></span>
                                    <span class="product-itemtable-measure">/<?= $item->unit ?></span>
                                    <div class="product-itemtable-priceline"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
            <td class="product-itemtable-buy">
                <div class="product-itemtable-buygroup">
                    <div class="product-itemtable-counter">
                        <span class="product-itemtable-minus"></span>
                        <input type="text" name="quantity" value="1" max="<?= $item->quantity ?>" class="product-itemtable-quantity">
                        <span class="product-itemtable-plus"></span>
                    </div>
                    <div class="product-itemtable-button buy" data-id="<?= $item->id ?>">В корзину</div>
                    <div class="product-itemtable-like">
                        <a href="" class="product-itemtable-wish" data-id="<?= $item->id ?>" title="Отложить"></a>
                        <a href="" class="product-itemtable-compare" data-id="<?= $item->id ?>" title="Сравнить"></a>
                    </div>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
