$(function () {
    let loader  = $('#loader'),
        citiesArray = $.map(citiesList, function (value, key) {
            return { value: value, data: key };
        }),
        cityKey = Math.floor( Math.random() * (Object.keys(citiesArray).length));

    setCheckboxCondition($('.catalog-left-check > input'));
    checkFilterState();
    // setLeftSubMenuWidth(); // установка ширины подменю каталога

    /* Маска для ввода телефона */
    $('input[name=phone]').inputmask({'mask': '+7 (999) 999-99-99'});

    /* открытие таба по хэшу в адресной строке */
    if (window.location.hash) {
        let target = '#' + window.location.hash.slice(1),
            elem = $(target);

        if (elem.length) {
            $('.tab, .basket-tabs a').removeClass('active');
            $('a[data-target=' + window.location.hash.slice(1)).addClass('active');
            elem.addClass('active');
        }
    }

    // действия с задержкой при изменении размера экрана
    $(window).onDelay({
        action: 'resize',
        interval: 1000
    }, function(){
        //setLeftSubMenuWidth(); // пересчет ширины подменю каталога
    });

    /* фиксирование меню при прокрутке */
    $(document).scroll(function() {
        if($(document).scrollTop() > 200) {
            $('.header').addClass("fixed");
        } else if($(document).scrollTop() < 200) {
            $('.header').removeClass("fixed");
        }
    });

    /* скрытие-появление подсказки в поле ввода email */
    $('.footer-subscribe-input').on('input', function () {
        if ($('.footer-subscribe-input').val()) {
            $('.footer-subscribe-label').addClass('focus');
        } else {
            $('.footer-subscribe-label').removeClass('focus');
        }
    });

    /* переключение стилизованных чекбоксов */
    $('.checkbox').on('click', function (e) {
        e.preventDefault();
        $(this).hasClass('checked') ?
            $(this).removeClass('checked').find('input[type=checkbox]').prop('checked', false) :
            $(this).toggleClass('checked').find('input[type=checkbox]').prop('checked', true);
    });

    /* переключение стилизованных радиокнопок */
    $('input[type=radio]').on('change', function () {
        $(this).parents('.radio-container').find('label.radio').removeClass('checked');
        $(this).parent('label.radio').addClass('checked');

        let target = $(this).attr('data-target');
        $(this).parents('.order-item').find('.order-item-slider').hide();
        $(this).parents('.order-item').find('.' + target).show();
    });

    /* раскрытие/закрытие описания вакансии */
    $('.vacancy-header').on('click', function (e) {
        e.preventDefault();
        $(this).next().slideToggle();
        $(this).parent().toggleClass('active');
    });

    /* закрытие подсказки по клику */
    $('label').on('click', function () {
        $(this).children('input').removeClass('error')
        $(this).children('.tooltip').removeClass('active');
    });

    /**************************** MENU ****************************/
    $('.nav-catalog-dropdown').on('mouseover', function () {
        $('.nav-catalog-menu').show();
    });

    $('.nav-catalog-dropdown').on('mouseout', function () {
        $('.nav-catalog-menu').hide();
    });

    /* открытие/закрытие меню-гамбургера */
    $('.hamburger').on('click', function (e) {
        e.preventDefault();
        $(this).addClass('is-active');
        $('.overlay').show();
        $('.menu-mobile').css('left', 0);
    });

    $('.nav-wrap').on('mouseover', function () {
        $(this).children('.dropdown-menu').show();
    });

    $('.nav-wrap').on('mouseout', function () {
        $(this).children('.dropdown-menu').hide();
    });
    /**************************** !MENU ****************************/
    /**************************** MODAL ****************************/
    /* открытие модального окна по клике в шапке */
    $('.header-action').on('click', function (e) {
        e.preventDefault();
        $('.menu-mobile').hide();
        $('#' + $(this).data('target')).show();
        $('.overlay').show();
    });

    /* открытие модального окна по клике в мобильном меню */
    $('.mobile-action').on('click', function (e) {
        e.preventDefault();
        $('#' + $(this).data('target')).show();
        $('.overlay').show();
        $('.hamburger').removeClass('is-active');
        $('.menu-mobile').css('left', '-320px');
    });

    $('.overlay').on('click', function () {
        $('.menu-mobile').css('left', '-320px');
        $('.overlay').hide();
        $('.modal').hide();
        $('.hamburger').removeClass('is-active');
    });

    /* закрытие модального окна по клику на крестик */
    $('.close').on('click', function () {
        $(this).parent('.modal').hide();
        $('.overlay').hide();
    });
    /**************************** !MODAL ****************************/
    /**************************** CATALOG ****************************/
    /* проверка всех чекбоксов при загрузке и установка их оформления */
    // $('input[type=radio]').each(function () {
    //     if ($(this).prop('checked')) $(this).parent('label').addClass('checked');
    // });

    /* переключение стилизованных чекбоксов в фильтре каталога */
    $('.catalog-left-check label').on('click', function () {
        let input = $(this).prev(),
            span = $(this).find('span');
        input.prop('checked') ? span.removeClass('checked') : span.addClass('checked');
    });

    /* переключение стилизованных радиокнопок в фильтре каталога */
    $('.catalog-left-radio label').on('click', function () {
        $(this).parent('.catalog-left-radio').find('span').removeClass('checked');
        $(this).find('span').addClass('checked');
    });

    /* разворачивание/сворачивание пунктов в фильтре товаров */
    $('.catalog-left-filter-title').on('click', function (e) {
        e.preventDefault();
        let next = $(this).next();
        $(this).toggleClass('active');
        next.slideToggle();
    });

    /* смена режима просмотра каталога */
    $('.product-view a').on('click', function (e) {
        $('.product-view a').removeClass('active');
        $(this).addClass('active');
        $.cookie('view', $(this).attr('data-view'), {expires: 1, path: '/'});
    });

    /* кнопка минус количества товаров */
    $('.product-item-minus, .product-itemlist-minus, .product-minus, .basket-item-minus').on('click', function (e) {
        e.preventDefault();
        let val = Number($(this).next().val()),
            id = Number($(this).next().data('id')),
            price_type = Number($(this).next().data('price-type-id'));

        if (val > 1) $(this).next().val(val - 1);

        if ('basket-item-minus' === e.target.className && val > 1) recalcProduct(id, (val - 1), price_type, $(this).parents('.basket-item'));
    });

    /* кнопка плюс количества товаров */
    $('.product-item-plus, .product-itemlist-plus, .product-plus, .basket-item-plus').on('click', function (e) {
        e.preventDefault();
        let val = Number($(this).prev().val()),
            id = Number($(this).prev().data('id')),
            max = Number($(this).prev().attr('max')),
            price_type = Number($(this).prev().data('price-type-id'));

        if (val < max) $(this).prev().val(val + 1);

        if ('basket-item-plus' === e.target.className && val < max) recalcProduct(id, (val + 1), price_type, $(this).parents('.basket-item'));
    });

    /* счетчик количества товаров */
    $('.product-item-quantity, .product-quantity, .basket-item-quantity').on('blur', function (e) {
        let val = Number($(this).val()),
            id = Number($(this).data('id')),
            max = Number($(this).attr('max')),
            price_type = Number($(this).data('price-type-id'));

        if (val > max) $(this).val(max);
        if (!/^[\d]+$/.test(val) || val < 1) $(this).val(1);

        if ('basket-item-quantity' === e.target.className) recalcProduct(id, $(this).val(), price_type, $(this).parents('.basket-item'));
    });

    /* переключение табов на карточке товара */
    $('.product-tab').on('click', function (e) {
        e.preventDefault();
        $('.product-content, .product-tab').removeClass('active');
        $(this).addClass('active');
        $(' #' + $(this).data('target')).addClass('active');
    });

    /* раскрытие характеристик товара в каталоге при отображении списком */
    $('.product-itemlist-moreprops a').on('click', function (e){
        e.preventDefault();
        $(this).toggleClass('active').parents('.product-itemlist-moreprops').next().slideToggle();

    });
    /**************************** !CATALOG ****************************/
    /**************************** CART ****************************/
    /* удаление товара из корзины */
    $('.basket-item-del').on('click', function (e) {
        e.preventDefault();
        let product_id = $(this).data('id'),
            price_type = $(this).data('price-type-id'),
            elem = $(this).parents('.basket-item');

        $.ajax({
            method: "POST",
            dataType: 'json',
            url: "/cart/deleteProduct/",
            data: {
                id: product_id,
                price_type: price_type
            },
            beforeSend: function() {
                loader.show();
            },
            success: function(data){console.log(data);
                loader.hide();

                if (!data.result) {
                    $('#notification').html(data.message).addClass('active');
                    removeNotification();
                } else {
                    elem.remove();

                    $('.basket-order-total span').html(data.count);
                    $('.basket-order-price span').html(data.cart_discount_sum ? data.cart_discount_sum : data.cart_sum);
                    $('.basket-order-oldprice span').html(data.cart_discount_sum ? data.cart_sum : '');
                    $('.basket-order-economy span').html(data.cart_economy ? data.cart_economy : '');

                    data.message ? $('.basket-message').html(data.message).addClass('block') : $('.basket-message').html('').removeClass('block');
                }
            }
        });
    });

    /* очистка корзины */
    $('.basket-order-clear a').on('click', function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            dataType: 'json',
            url: "/cart/clear/",
            beforeSend: function() {
                loader.show();
            },
            success: function(data){console.log(data);
                if (!data.result) {
                    loader.hide();
                    $('#notification').html(data.message).addClass('active');
                    removeNotification();
                } else {
                    document.location.reload(true);
                }
            }
        });
    });

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
                loader.show();
                message_error.html('').hide();
            },
            success: function(data){console.log(data);
                loader.hide();
                if (!data.result) {
                    message_success.html('').hide();
                    message_error.html(data.message).show();
                } else {
                    form.submit();
                }
            }
        });
    });
    /**************************** !CART ****************************/
    /**************************** ORDER ****************************/
    /* добавление товара в корзину */
    $('.buy').on('click', function () {
        let count = $('.header-cart-count, .menu-mobile-basket .menu-mobile-count'),
            $data = {
                id:    $(this).data('id'),
                count: $(this).parent().find('input[name=quantity]').val(),
                price_type: $(this).data('price-type-id')
            };

        $.ajax({
            method: "POST",
            dataType: 'json',
            url: "/catalog/addToCart/",
            data: $data,
            beforeSend: function() {
                loader.show();
            },
            success: function(data){console.log(data);
                loader.hide();

                if (!data.result) {
                    $('#notification').html(data.message).addClass('active');
                    removeNotification();
                } else {
                    if (Number(data.count) > 0) count.removeClass('empty').html(data.count);
                    else count.addClass('empty').html(data.count);
                }
            }
        });
    });

    /* сворачивание/разворачивание пунктов оформления заказа */
    $('.order-item-title').on('click', function (e) {
        e.preventDefault();
        $(this).next('.order-item-container').slideToggle();
    });

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

    /* сворачивание/разворачивание подробного описания заказа в истории заказов */
    $('.personal-orders-title').on('click', function (e) {
        e.preventDefault();
        $('.personal-orders-container').not($(this).next()).slideUp();
        $(this).next().slideToggle();
    });

    $('#p_profile, #j_profile').on('change', function () {
        let option = $(this).find('option:selected');

        $(this).parents('.order-item-slider').find('input[name=name]').val(option.data('name'));
        $(this).parents('.order-item-slider').find('input[name=email]').val(option.data('email'));
        $(this).parents('.order-item-slider').find('input[name=phone]').val(option.data('phone'));

        $(this).parents('.order-item-slider').find('input[name=firm]').val(option.data('firm'));
        $(this).parents('.order-item-slider').find('input[name=address_legal]').val(option.data('address_legal'));
        $(this).parents('.order-item-slider').find('input[name=inn]').val(option.data('inn'));
        $(this).parents('.order-item-slider').find('input[name=kpp]').val(option.data('kpp'));

        $('input[name=index]').val(option.data('index'));
        $('input[name=city]').val(option.data('city'));
        $('input[name=address]').val(option.data('address'));
        $('textarea[name=comment]').val(option.data('comment'));
    });
    /**************************** !ORDER ****************************/
    /**************************** PERSONAL ****************************/
    /* выбор суммы пополнения баланса */
    $('.personal-bill-add > span').on('click', function (e) {
        $('.personal-bill-add input').val($(this).html());
    });

    /* изменение пароля */
    $('#change').on('submit', function (e) {
        e.preventDefault();
        let form = $(this),
            input_password = form.find('input[name=password'),
            input_password_confirm = form.find('input[name=password_confirm'),
            password = input_password.val(),
            password_confirm = input_password_confirm.val(),
            message_error = form.find('.message_error'),
            error = [];

        form.find('input.required').each(function () {
            if (!$(this).val()) {
                error.push(true);
                $(this).addClass('error');
                $(this).parent().find('.tooltip').html('Введите пароль').addClass('active');
            }
            else if (!checkUserData($(this).val(), 'pass')) {
                error.push(true);
                $(this).addClass('error');
                $(this).parent().find('.tooltip').html('Пароль должен содержать 1 цифру, 1 заглавную и строчные буквы').addClass('active');
            }
            else if (password !== password_confirm) {
                error.push(true);
                input_password.addClass('error');
                input_password_confirm.addClass('error');
                input_password_confirm.parent().find('.tooltip').html('Пароль и его подтверждение не совпадают').addClass('active');
            }
            else {
                error.push(false);
                $(this).removeClass('error');
            }
        });

        if (-1 === error.indexOf(true)) {
            message_error.html('').hide();
            $('.tooltip').html('').removeClass('active');

            let $data = {
                password: password,
                password_confirm: password_confirm,
                hash: form.find('input[name=hash').val()
            };

            $.ajax({
                method: "POST",
                dataType: 'json',
                url: "/personal/change/",
                data: $data,
                beforeSend: function() {
                    loader.show();
                },
                success: function(data){console.log(data);
                    loader.hide();

                    if (!data.result) {
                        if (1 === data.error) input_password.parent().find('.tooltip').html(data.message).addClass('active');
                        else if (2 === data.error) input_password_confirm.parent().find('.tooltip').html(data.message).addClass('active');
                        else message_error.html(data.message).show();
                    } else {
                        form.hide();
                        $('.success_message').html('Пароль успешно изменен').show();
                    }
                }
            });
        }
    });
    /**************************** !PERSONAL ****************************/
    /**************************** AUTH ****************************/
    /* авторизация */
    $('#auth form, .auth form').on('submit', function (e) {
        e.preventDefault();
        let form = $(this),
            tooltip = form.find('.tooltip'),
            message_error = form.find('.message_error'),
            error = [];
        tooltip.removeClass('active');

        form.find('input.required').each(function () {
            if ($(this).val()) {
                if (('login' === $(this).attr('name') && !checkUserData($(this).val(), 'phone')) &&
                    ('login' === $(this).attr('name') && !checkUserData($(this).val(), 'email')))
                {
                    error.push(true);
                    $(this).addClass('error');
                    $(this).parent().find('.tooltip').html('Проверьте введенный логин').addClass('active');
                }
                else if ('password' === $(this).attr('name') && !checkUserData($(this).val(), 'pass')) {
                    error.push(true);
                    $(this).addClass('error');
                    $(this).parent().find('.tooltip').html('Проверьте введенный пароль').addClass('active');
                }
                else if ('checkbox' === $(this).attr('type') && !$(this).prop('checked')) {
                    error.push(true);
                    $(this).parent().addClass('error');
                    $(this).parent().find('.tooltip').html('Не получено согласие на обработку персональных данных').addClass('active');
                }
                else {
                    error.push(false);
                    $(this).removeClass('error');
                }
            } else {
                error.push(true);
                $(this).addClass('error');
            }
        });

        if (-1 !== error.indexOf(true)) {
            message_error.html('Проверьте введенные данные').show();
        } else {
            tooltip.removeClass('active');
            message_error.html('').hide();

            let $data = {
                login:         form.find('input[name=login]').val(),
                password:      form.find('input[name=password]').val(),
                personal_data: form.find('input[name=personal_data]').prop('checked') ? 1 : 0,
                remember:      form.find('input[name=remember]').prop('checked') ? 1 : 0,
            };

            $.ajax({
                method: "POST",
                dataType: 'json',
                url: "/auth/",
                data: $data,
                beforeSend: function() {
                    loader.show();
                },
                success: function(data){console.log(data);
                    if (!data.result) {
                        loader.hide();
                        if (1 === data.error) form.find('input[name=login]').parent().find('.tooltip').html(data.message).addClass('active');
                        else if (2 === data.error) form.find('input[name=password]').parent().find('.tooltip').html(data.message).addClass('active');
                        else message_error.html(data.message).show();
                    } else {
                        (-1 !== window.location.href.indexOf('/auth/')) ?
                            window.location.href = '/' :
                            window.location.reload(false);
                    }
                }
            });
        }
    });
    /*************************** !AUTH ****************************/
    /*************************** REGISTER ****************************/
    $('#register input[type=submit]').on('click', function (e) {
        e.preventDefault();
        let form = $(this).parent('form'),
            tooltip = form.find('.tooltip'),
            message_error = form.find('.message_error'),
            error = [];
        tooltip.removeClass('active');

        form.find('input.required').each(function () {
            if ($(this).val()) {
                if (-1 !== $(this).attr('name').indexOf('name') && !checkUserData($(this).val(), 'rus_eng')) {
                    error.push(true);
                    $(this).addClass('error');
                    $(this).parent().find('.tooltip').html('Проверьте введенные данные').addClass('active');
                }
                else if ('email' === $(this).attr('name') && !checkUserData($(this).val(), 'email')) {
                    error.push(true);
                    $(this).addClass('error');
                    $(this).parent().find('.tooltip').html('Проверьте введенный email').addClass('active');
                }
                else if ('phone' === $(this).attr('name') && !checkUserData($(this).val(), 'phone')) {
                    error.push(true);
                    $(this).addClass('error');
                    $(this).parent().find('.tooltip').html('Проверьте введенный телефон').addClass('active');
                }
                else if (('password' === $(this).attr('name') || 'password_confirm' === $(this).attr('name')) && !checkUserData($(this).val(), 'pass')) {
                    error.push(true);
                    $(this).addClass('error');
                    $(this).parent().find('.tooltip').html('Недостаточная сложность пароля').addClass('active');
                }
                else if ('checkbox' === $(this).attr('type') && !$(this).prop('checked')) {
                    error.push(true);
                    $(this).parent().addClass('error');
                    $(this).parent().find('.tooltip').html('Не получено согласие на обработку персональных данных').addClass('active');
                }
                else {
                    error.push(false);
                    $(this).removeClass('error');
                }
            } else {
                error.push(true);
                $(this).addClass('error');
            }
        });

        if (-1 === error.indexOf(true)) {
            let $data = {
                last_name:        form.find('input[name=last_name]').val(),
                name:             form.find('input[name=name]').val(),
                second_name:      form.find('input[name=second_name]').val(),
                email:            form.find('input[name=email]').val(),
                phone:            form.find('input[name=phone]').val(),
                password:         form.find('input[name=password]').val(),
                password_confirm: form.find('input[name=password_confirm]').val(),
                personal_data:    form.find('input[name=personal_data]').prop('checked') ? 1 : 0,
            };

            $.ajax({
                method: "POST",
                dataType: 'json',
                url: "/auth/registration/",
                data: $data,
                beforeSend: function() {
                    message_error.html('').hide();
                    loader.show();
                },
                success: function(data){console.log(data);
                    loader.hide();

                    if (!data.result) {
                        message_error.html(data.message).show();
                    } else {
                        form.hide();
                        $('.success_message').show();
                    }
                }
            });
        }
    })
    /*************************** !REGISTER ****************************/
    /*************************** RESTORE ****************************/
    $('.restore form').on('submit', function (e) {
        e.preventDefault();
        let form = $(this),
            input = form.find('input[name=login]'),
            login = input.val(),
            tooltip = form.find('.tooltip');
        tooltip.removeClass('active');

        if (login && (checkUserData(login, 'phone') || checkUserData(login, 'email'))) {
            $.ajax({
                method: "POST",
                dataType: 'json',
                url: "/auth/restore/",
                data: {'login': login},
                beforeSend: function() {
                    loader.show();
                },
                success: function(data){console.log(data);
                    loader.hide();

                    if (!data.result) {
                        if (data.message) {
                            input.addClass('error');
                            tooltip.html(data.message).addClass('active');
                        } else {
                            form.hide();
                            $('.error_message').show();
                        }
                    } else {
                        form.hide();
                        $('.success_message').show();
                    }
                }
            });
        }
        else {
            input.addClass('error');
            tooltip.html('Введите номер телефона или e-mail').addClass('active');
        }
    });
    /*************************** !RESTORE ****************************/
    /*************************** LOCATION ****************************/
    /* очистка списка выбранного округа и региона */
    $('#location .reset').on('click', function () {
        $('.district, .district li, .region, .city').show();
        $('.region ul').html('');
        $('.city ul').html('');
    });

    /* подстановка рандомного города в пример строки поиска */
    $('#location .example a').html(function(index, html){
        $(this).attr('href', citiesArray[cityKey].data);
        return html + citiesArray[cityKey].value;
    });

    /* выбор рандомного города */
    $('#location .example').on('click', 'a', function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            dataType: 'json',
            url: "/location/setCity/",
            data: {'city': $(this).html().replace('Например, ', '')},
            beforeSend: function() {
            },
            success: function(data){console.log(data);
                $('.header-city-link').html(data.city);
                $('#location .reset').trigger('click');
                $('#location .close').trigger('click');
            }
        });
    });

    /* автозаполнение городов и подставновка контроллера */
    $('#city').autocomplete({
        lookup: citiesArray,
        autoSelectFirst: true,
        minChars: 2,
        lookupLimit: 5,
        minHeight: 40,
        maxHeight: 202,
        width: 200,
        showNoSuggestionNotice: true,
        noSuggestionNotice: 'Не найдено',
        onSearchStart: function () {
        },
        onSelect: function (suggestion) {
            $(this).val('');
            $('.header-city-link').html(suggestion.value);
            $('#location .reset').trigger('click');
            $('#location .close').trigger('click');
        }
    });

    /* выбор округа */
    $('.district').on('click', 'li', function (e) {console.log(document.body.clientWidth);
        e.preventDefault();
        $('.district li').not($(this)).hide();

        $.ajax({
            method: "POST",
            dataType: 'json',
            url: "/location/regions/",
            data: {'district': $(this).children().data('id')},
            beforeSend: function() {
            },
            success: function(data){
                console.log(data);

                if (data) {
                    $('.region ul').html(data);

                    if (document.body.clientWidth < 681) $('.district').hide();
                }
            }
        });
    });

    /* выбор региона */
    $('.region').on('click', 'li', function (e) {
        e.preventDefault();
        $('.region li').not($(this)).hide();

        $.ajax({
            method: "POST",
            dataType: 'json',
            url: "/location/cities/",
            data: {'region': $(this).children().data('id')},
            beforeSend: function() {
            },
            success: function(data){
                console.log(data);

                if (data) {
                    $('.city ul').html(data);

                    if (document.body.clientWidth < 501) $('.region').hide();
                }
            }
        });
    });

    /* выбор города */
    $('.city').on('click', 'li', function (e) {
        e.preventDefault();

        $.ajax({
            method: "POST",
            dataType: 'json',
            url: "/location/setCity/",
            data: {'city': $(this).children().html()},
            beforeSend: function() {
            },
            success: function(data){console.log(data);
                $('.header-city-link, .menu-mobile-location span').html(data.city);
                $('#location .reset').trigger('click');
                $('#location .close').trigger('click');
            }
        });
    });
    /*************************** !LOCATION ****************************/
    /**************************** PRICE SLIDER ****************************/
    /* слайдер цены в фильтре товаров */
    $('.handle-right').on('click', function (e) {
        e.preventDefault();
    });

    let handler;

    $('.handle-right').on('mousedown', function (e) {
        console.log(1);

        let x = $(this).offset().left,
            y = $(this).offset().top;

        console.log($(this).offset());

        handler = $("body").on('mousemove', function (pos) {
            let position = x - pos.pageX + 8;
            $('.handle-right').css('right', (pos.pageX - 8 < x ? position : 0) + "px");
        });
    });

    $('.handle-right').on('mouseup', function (e) {
        console.log(2);
        $("body").unbind('mousemove');
    });
    /**************************** !PRICE SLIDER ****************************/
});
