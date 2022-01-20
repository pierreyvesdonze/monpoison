var app = {

    init: function () {

        /**
       * *****************************
       * PUSH NOTIFS
       * *****************************
       */
        app.mainPush();
  

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        if (window.location.href.indexOf("article/voir") > -1) {
            document.querySelector('.submit-subscribe').addEventListener('click', app.subscribeToPosts);
        }

        if (window.location.href.indexOf('public/') > -1) {
            app.displayWelcomeModal();
        }

        // Fadeout flash message
        if ($('.flash-container').find('.alert').length !== 0) {
            setTimeout(function () {
                 $('.alert').fadeOut('fast')
             }, 1500);
        }

        /**Push Notification btn */
        $('#push-permission .custom-btn').on('click', app.askPermission)

        document.querySelector('.navbar-toggler').addEventListener('click', app.disableSocialLinks)

        /**
        * *****************************
        * SCROLL TO TOP BUTTON
        * *****************************
        */
        const showButton = () => document.querySelector("#toTop").classList.add("show");
        const hideButton = () => document.querySelector("#toTop").classList.remove("show");
        document.addEventListener("scroll", (e) => window.scrollY < 200 ? hideButton() : showButton());

        document.querySelector('#toTop').addEventListener('click', function (e) {
            e.preventDefault();
            window.scroll({ top: 0, left: 0, behavior: 'smooth' });
        });
    },

    /**
     * Subscribe to posts
     */
    subscribeToPosts: function (e) {
        e.preventDefault();
        var emailSubscriber = document.querySelector('.subscribe-mail-input').value;
        console.log($('.container').data('isSubscribed'));
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailSubscriber)) {

            $.ajax(
                {
                    url: Routing.generate('subscribe_posts', { 'emailSubscriber': emailSubscriber }),
                    method: "POST",
                }).done(function (response) {
                    e.preventDefault();
                    if (null !== response) {
                        document.querySelector('.modal-subscribe-text').textContent = response
                        $('.btn-subscribe').remove()
                    } else {
                        console.log('Problème');
                    }
                }).fail(function (jqXHR, textStatus, error) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(error);
                });
        } else {
            console.log('mail pas ok')
            document.querySelector('.modal-subscribe-text').textContent = 'Veuillez rentrer une adresse email valide';
        }
    },

    disableSocialLinks: function (params) {
        let $socialIcons = $('.social-icons')
        $('.navbar-toggler').hasClass('collapsed') ? $socialIcons.fadeIn('slow') : $socialIcons.fadeOut('fast');
    },

    displayWelcomeModal: function (params) {
        $('#btnWelcomeModal').trigger('click');
        setTimeout(function () {
            $('.close-welcome-modal').trigger('click')
        }, 1500);

    },

    mainPush: function () {
        const permission = document.getElementById('push-permission');
        if (
            (!permission &&
            (!'notification' in window) &&
                (!'serviceWorker' in navigator))
            || Notification.permission != 'default'
        ) {
            return;
        }
    },

    askPermission: async function () {
        const permission = await Notification.requestPermission()
        if (permission == 'granted') {
            alert('Notifications activées')
            app.registerServiceWorker()
        }
        console.log(permission)
    },

    registerServiceWorker: async function () {
        const registration = await navigator.serviceWorker.register('../../sw.js');
        const subscription = await registration.pushManager.getSubscription();
        console.log(subscription)
    }
}

document.addEventListener('DOMContentLoaded', app.init)