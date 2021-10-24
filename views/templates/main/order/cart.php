<div id="cart">
    <?php if (!empty($cart) || !empty($favorite)): ?>
        <?php

        if (defined('CART_SUMMARY_HEADER') && !empty(CART_SUMMARY_HEADER)): ?>
            <?= $this->render('order/cart_summary'); ?>
        <?php endif; ?>

        <div class="basket-message <?= $cart['message'] ? 'block' : '' ?>"><?= $cart['message'] ?></div>

        <div class="basket-container">
            <div class="basket-tabs">
                <a href="" class="active" data-target="basket">
                    В корзине
                    <?php if (!empty($cart) && $cart['count_items'] > 0): ?>
                        <span class="cart-count"><?= $cart['count_items'] ?></span>
                    <?php endif; ?>
                </a>
                <?php if (!empty($cart) && $cart['count_absent'] > 0): ?>
                    <a href="" data-target="absent">
                        Нет в наличии
                        <span class="absent-count"><?= $cart['count_absent'] ?></span>
                    </a>
                <?php endif; ?>
                <?php if (!empty($favorite) && count($favorite) > 0): ?>
                    <a href="" data-target="favorite">
                        Отложено
                    </a>
                <?php endif; ?>
            </div>

            <?= $this->render('order/cart_products'); ?>

            <?= $this->render('order/cart_absent'); ?>

            <?= $this->render('order/cart_favorite'); ?>
        </div>

        <?php if (defined('CART_SUMMARY_FOOTER') && !empty(CART_SUMMARY_FOOTER)): ?>
            <?= $this->render('order/cart_summary'); ?>
        <?php endif; ?>

    <?php else: ?>
        <div class="basket-empty">
            <p>Ваша корзина пуста</p>
            <p>Нажмите <a href="/catalog/">здесь</a>, чтобы продолжить покупки</p>
        </div>
    <?php endif; ?>
</div>

<script>
    $(function () {
        /* клик по табу в корзине */
        $('.basket-tabs a').on('click', function (e) {
            e.preventDefault();
            let target = $(this).attr('data-target');
            location.hash = '#' + target;

            $('.basket-tabs a').removeClass('active');
            $(this).addClass('active');

            $('.basket-items').removeClass('active');
            $('#' + target).addClass('active');
        });

        /* удаление товара из корзины */
        $('.basket-item-del').on('click', function (e) {
            e.preventDefault();
            let product_id = $(this).data('id');

            if (confirm('Вы уверены, что хотите удалить товар из корзины?')) {
                $.ajax({
                    method: "POST",
                    dataType: 'html',
                    url: "/cart/delete/",
                    data: {id: product_id},
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(data){console.log(data);
                        $('#loader').hide();
                        $('#cart').html(data);
                    }
                });
            }
        });

        /* очистка корзины */
        $('.basket-order-clear a').on('click', function (e) {
            e.preventDefault();

            if (confirm('Вы уверены, что хотите очистить корзину?')) {
                $.ajax({
                    method: "POST",
                    dataType: 'html',
                    url: "/cart/clear/",
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(data){console.log(data);
                        $('#loader').hide();
                        $('#cart').html(data);
                    }
                });
            }
        });

        /* применение купона */
        $('.basket-coupon-form input[type=submit]').on('click', function (e) {
            e.preventDefault();
            let form = $('.basket-coupon-form'),
                value = $('.basket-coupon-form input[name=coupon]').val(),
                message_error = $('.basket-coupon .message_error'),
                message_success = $('.basket-coupon .message_success');

            $.ajax({
                method: "POST",
                dataType: 'json',
                url: "/cart/checkCoupon/",
                data: {
                    coupon: value
                },
                beforeSend: function() {
                    $('#loader').show();
                    message_error.html('').hide();
                },
                success: function(data){console.log(data);
                    $('#loader').hide();
                    if (!data.result) {
                        message_success.html('').hide();
                        message_error.html(data.message).show();
                    } else {
                        form.submit();
                    }
                }
            });
        });

        /* кнопка минус количества товаров */
        $('.basket-item-minus').on('click', function (e) {
            e.preventDefault();
            let val = Number($(this).next().val()),
                id = Number($(this).next().data('id'));

            if (val > 1) {
                $.ajax({
                    method: "POST",
                    dataType: 'html',
                    url: "/cart/recalc/",
                    data: {
                        id: id,
                        count: val - 1
                    },
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(data){console.log(data);
                        $('#loader').hide();
                        $('#cart').html(data);
                    }
                });
            }
        });

        /* кнопка плюс количества товаров */
        $('.basket-item-plus').on('click', function (e) {
            e.preventDefault();
            let val = Number($(this).prev().val()),
                id = Number($(this).prev().data('id')),
                max = Number($(this).prev().attr('max'));

            if (val < max) {
                $.ajax({
                    method: "POST",
                    dataType: 'html',
                    url: "/cart/recalc/",
                    data: {
                        id: id,
                        count: val + 1
                    },
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(data){console.log(data);
                        $('#loader').hide();
                        $('#cart').html(data);
                    }
                });
            }
        });

        /* счетчик количества товаров */
        $('.basket-item-quantity').on('blur', function (e) {
            let val = Number($(this).val()),
                old_val = Number($(this).data('value')),
                id = Number($(this).data('id')),
                max = Number($(this).attr('max'));

            if (val > max) val = max;
            else if (!/^[\d]+$/.test(val) || val < 1) val = 1;

            $(this).val(val);

            if (val <= max && val >= 1 && val !== old_val) {
                $.ajax({
                    method: "POST",
                    dataType: 'html',
                    url: "/cart/recalc/",
                    data: {
                        id: id,
                        count: val
                    },
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(data){console.log(data);
                        $('#loader').hide();
                        $('#cart').html(data);
                    }
                });
            }
        });
    });
</script>
