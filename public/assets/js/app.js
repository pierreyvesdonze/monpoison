var app = {

    
    init: function () {

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        console.log('init');
        
        
        /*Scroll to top button*/
        var btn = $('#toTop');

        $(window).scroll(function () {
            if ($(window).scrollTop() > 300) {
                btn.addClass('show');
            } else {
                btn.removeClass('show');
            }
        });

        btn.on('click', function (e) {
            e.preventDefault();
            $('html, body').animate({ scrollTop: 0 }, '300');
        });
    },

}

document.addEventListener('DOMContentLoaded', app.init)