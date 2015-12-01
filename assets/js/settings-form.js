(function($) {
    var target = '';
    $(function() {
        $('.setting-field > input').on('focus', function(e) {
            target = $(this).attr('data-target');
        });

        $('.setting-field > input').on('blur', function(e) {
            target = '';
            $('.prettyprint.prettyprinted > span.bg-info').removeClass('bg-info');
        });
        
        window.setInterval(function() {
            var targets = $(target + '.bg-info');
            if(targets.length > 0){
                $(target + '.bg-info').removeClass('bg-info');
            } else {
                $(target).addClass('bg-info');
            }
        }, 1000);
    });
})(jQuery);
