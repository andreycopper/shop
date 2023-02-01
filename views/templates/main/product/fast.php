<div class="product-main">
    <div class="product-photo">
        <div class="product-photo-main">
            <a href="/uploads/catalog/<?= $item->id ?>/<?= $item->detail_image ?>" data-lightbox="<?= $item->name ?>" data-title="<?= $item->name ?>">
                <img src="/uploads/catalog/<?= $item->id ?>/<?= $item->detail_image ?>" alt="">
            </a>
            <div class="product-item-stickers">
                <?php if (!empty($item->hit)): ?>
                    <span class="stickers sticker-hit">Хит</span>
                <?php endif; ?>
                <?php if (!empty($item->new)): ?>
                    <span class="stickers sticker-new">Новинка</span>
                <?php endif; ?>
                <?php if (!empty($item->action)): ?>
                    <span class="stickers sticker-action">Акция</span>
                <?php endif; ?>
                <?php if (!empty($item->recommend)): ?>
                    <span class="stickers sticker-recomend">Советуем</span>
                <?php endif; ?>
                <?php if (!empty($item->discount)): ?>
                    <span class="stickers sticker-sale">Sale</span>
                    <span class="stickers sticker-discount"><?= $item->discount ?>%</span>
                <?php endif; ?>
            </div>
            <div class="product-item-like">
                <div class="like product-item-wish" data-id="<?= $item->id ?>" title="В избранное"></div>
                <div class="like product-item-compare" data-id="<?= $item->id ?>" title="Сравнить"></div>
            </div>
            <div class="product-view"></div>
        </div>
        <div class="product-slide">
            <?php if (!empty($item->images) && is_array($item->images)):
                foreach ($item->images as $image): ?>
                    <div class="product-slide-item left">
                        <a href="/uploads/catalog/<?= $item->id ?>/<?= $image->image ?>" data-lightbox="<?= $item->name ?>" data-title="<?= $item->name ?>">
                            <img src="/uploads/catalog/<?= $item->id ?>/<?= $image->image ?>" alt="">
                        </a>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
    <div class="product-info">
        <div class="product-info-tech">
            <div class="product-rating">
                <span class="star star-active"></span>
                <span class="star star-active"></span>
                <span class="star star-active"></span>
                <span class="star star-inactive"></span>
                <span class="star star-inactive"></span>
            </div>

            <div class="product-articul">Артикул: <?= $item->articul ?></div>
            <?php if (!empty($item->vendor_image)): ?>
                <div class="product-vendor">
                    <a href="/vendors/<?=mb_strtolower($item->vendor_name)?>">
                        <img src="/uploads/vendor/<?= $item->vendor_image ?>" alt="">
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-info-text">
            <?= ('text' === $item->detail_text_type) ? ('<pre>' . $item->detail_text . '</pre>') : $item->detail_text ?>
        </div>

        <?php if (!empty($item->prices) && is_array($item->prices)): ?>
            <?php foreach ($item->prices as $price): ?>
                <?php $price_discount = round($price->price * (100 - $price->discount) / 100); ?>

                <div class="product-info-price">
                    <?php if (count($item->prices) > 1): ?>
                        <div class="product-info-price-title"><?= $price->price_type ?></div>
                    <?php endif; ?>
                    <?php if (!empty($price->discount)): ?>
                        <div class="product-oldprice <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                                    <span class="product-value">
                                        <?= number_format($price->price, 0, '.', ' ') ?>
                                    </span>
                            <span class="product-currency"><?=$price->currency?></span>
                            <span class="product-measure">/<?=$item->unit?></span>
                            <div class="product-priceline"></div>
                        </div>
                    <?php endif; ?>
                    <div class="product-price <?= $price->price_type_id !== $user->price_type_id ? 'inactive' : '' ?>">
                                <span class="product-value">
                                    <?= number_format($price_discount, 0, '.', ' ') ?>
                                </span>
                        <span class="product-currency"><?= $price->currency ?></span>
                        <span class="product-measure">/<?= $item->unit ?></span>
                        <?php if (!empty($item->tax_value)): ?>
                            <span class="product-nds">
                                        (в т.ч. <?= $item->tax_name ?>
                                <?= number_format(round($price_discount * $item->tax_value / (100 + $item->tax_value), 2), 2, '.', ' ') ?>
                                <?= $price->currency ?>)
                                    </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="product-info-count">
            <span class="icon <?= (($item->quantity > 0) ? 'ok' : 'no') ?>"></span>
            <?= (($item->quantity > 10) ? 'Много' : (($item->quantity > 0) ? 'Мало' : 'Отсутствует')) ?>
        </div>
        <div class="product-info-buy">
            <?php if ($item->quantity > 0): ?>
                <span class="product-counter">
                            <span class="product-minus"></span>
                            <input type="text" name="quantity" value="1" max="<?=$item->quantity?>" class="product-quantity">
                            <span class="product-plus"></span>
                        </span>
                <span class="product-button buy" data-id="<?= $item->id ?>">В корзину</span>
                <a class="product-altbutton order-action" data-target="qorder">Быстрый заказ</a>
            <?php else: ?>
                <span class="product-altbutton" data-id="<?=$item->id?>">Отложить</span>
            <?php endif; ?>
        </div>
        <div class="product-info-message">
            <p>Цена действительна только для интернет-магазина и может отличаться от цен в розничных магазинах</p>
        </div>
    </div>
</div>

<!--<div class="product-description">-->
<!--    <div class="product-tabs">-->
<!--        <div class="product-tab active" data-target="desc">Описание</div>-->
<!--        <div class="product-tab" data-target="props">Характеристики</div>-->
<!--        <div class="product-tab" data-target="videos">Видео (1)</div>-->
<!--        <div class="product-tab" data-target="reviews">Отзывы (2)</div>-->
<!--        <div class="product-tab" data-target="quantities">Наличие</div>-->
<!--    </div>-->
<!--    <div id="desc" class="product-content active">-->
<!--        --><?//= $this->render('product/desc') ?>
<!--    </div>-->
<!--    <div id="props" class="product-content">-->
<!--        --><?//= $this->render('product/props') ?>
<!--    </div>-->
<!--    <div id="videos" class="product-content">-->
<!--        --><?//= $this->render('product/videos') ?>
<!--    </div>-->
<!--    <div id="reviews" class="product-content">-->
<!--        --><?//= $this->render('product/reviews') ?>
<!--    </div>-->
<!--    <div id="quantities" class="product-content">-->
<!--        --><?//= $this->render('product/quantities') ?>
<!--    </div>-->
<!--</div>-->

<script>
    $(function () {
        /* переключение табов на карточке товара */
        $('.product-tab').on('click', function (e) {
            e.preventDefault();
            $('.product-content, .product-tab').removeClass('active');
            $(this).addClass('active');
            $(' #' + $(this).data('target')).addClass('active');
        });

        /* кнопка минус количества товаров */
        $('.modal .product-minus').on('click', function (e) {
            e.preventDefault();
            let val = Number($(this).next().val()),
                id = Number($(this).next().data('id'));

            if (val > 1) {
                $(this).next().val(val - 1);
                $('#qorder input[name=count]').val(val - 1);
            }
        });

        /* кнопка плюс количества товаров */
        $('.modal .product-plus').on('click', function (e) {
            e.preventDefault();
            let val = Number($(this).prev().val()),
                id = Number($(this).prev().data('id')),
                max = Number($(this).prev().attr('max'));

            if (val < max) {
                $(this).prev().val(val + 1);
                $('#qorder input[name=count]').val(val + 1);
            }
        });

        /* счетчик количества товаров */
        $('.modal .product-quantity').on('blur', function (e) {
            let val = Number($(this).val()),
                id = Number($(this).data('id')),
                max = Number($(this).attr('max'));

            if (val > max) {
                $(this).val(max);
                $('#qorder input[name=count]').val(max);
            }
            else if (!/^[\d]+$/.test(val) || val < 1) {
                $(this).val(1);
                $('#qorder input[name=count]').val(1);
            }
            else $('#qorder input[name=count]').val(val);
        });

        /* добавление товара в корзину */
        $('.buy').on('click', function () {
            let count = $('.header-cart-count, .menu-mobile-basket .menu-mobile-count'),
                $data = {
                    id:    $(this).data('id'),
                    count: $(this).parent().find('input[name=quantity]').val()
                };

            $.ajax({
                method: "POST",
                dataType: 'json',
                url: "/catalog/addToCart/",
                data: $data,
                beforeSend: function() {
                    $('#loader').show();
                },
                success: function(data){console.log(data);
                    $('#loader').hide();

                    if (!data.result) {
                        $('#notification').html(data.message).addClass('active');
                        removeNotification();
                    } else {
                        if (Number(data.message) > 0) count.removeClass('empty').html(data.message);
                        else count.addClass('empty').html(data.message);
                    }
                }
            });
        });

        /* открытие модального окна по клику */
        $('.order-action').on('click', function (e) {
            e.preventDefault();
            $('body').addClass('overflow');
            $('.menu-mobile').hide();
            $('#' + $(this).data('target')).show();
            $('.overlay').show();
        });

        /* быстрый заказ из быстрого просмотра */
        $('.modal .order-action').on('click', function () {
            let id = $('.modal .buy').data('id'),
                val = $('.modal input[name=quantity]').val();

            $('#qorder input[name=id]').val(id);
            $('#qorder input[name=count]').val(val);
        });
    });
</script>
