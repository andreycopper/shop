(function($){
    $.fn.onDelay = function(params, func){
        var defaults = {
            action: 'click',
            interval: 200
        };

        var settings = $.extend(defaults, params);

        $(this).each(function(){
            var timer;

            $(this).on(settings.action, function(e){
                clearInterval(timer);
                var target = $(this);

                timer = setTimeout(function(){
                    func.call(target, e);
                }, settings.interval);
            });

        });

        return this;
    }
})(jQuery);
