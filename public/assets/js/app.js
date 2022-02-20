var app = {

    init: function () {

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

        //Set goals achievement
        $('.set-achievement').on('click', app.setGoalAchievement);

        // Push Notification btn
        $('#push-permission .custom-btn').on('click', app.askPermission)

        document.querySelector('.navbar-toggler').addEventListener('click', app.disableSocialLinks)

        // Search bar for posts
        $('#search-post-btn').on('click', app.searchPost)
        $('.search-post').on('keyup', app.searchPost)

        /**
          * *****************************
          * TITLES ANIMATIONS
          * *****************************
          */
        // MAIN TITLE ANIMATION
        $(document).ready(function () {
            let url = (location.href.split('/'));
            let lastPartUrl = url.pop() || url.pop()
            if ('public' === lastPartUrl) {
                anime({
                    targets: '.big-title-wrapper .el',
                    scale: [
                        { value: .1, easing: 'easeOutSine', duration: 300 },
                        { value: 1, easing: 'easeInOutQuad', duration: 1000 }
                    ],
                    delay: anime.stagger(200, { grid: [14, 5], from: 'center' })
                });
            } 
        });


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

        /**
       * *****************************
       * PWA INSTALLER
       * *****************************
       */
        let deferredPrompt; // Allows to show the install prompt
        const installButton = document.getElementById("buttonInstallPwa");

        if (window.matchMedia('(display-mode: standalone)').matches) {
            app.installButton.style.display = "none";
        }

        window.addEventListener('appinstalled', () => {
            app.installButton.display = 'none';
        });

        // installButton.addEventListener("click", app.installApp);
        window.addEventListener("beforeinstallprompt", e => {
            console.log("beforeinstallprompt fired");
            // Prevent Chrome 76 and earlier from automatically showing a prompt
            e.preventDefault();
            // Stash the event so it can be triggered later.
            app.deferredPrompt = e;
            // Show the install button
            installButton.hidden = false;
        });
    },

    /**
     * Subscribe to posts
     */
    subscribeToPosts: function (e) {
        e.preventDefault();
        var emailSubscriber = document.querySelector('.subscribe-mail-input').value;
        console.log($('.container').data('isSubscribed'));
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/.test(emailSubscriber)) {

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

    displayWelcomeModal: function () {
        let showModal = localStorage.getItem('showModal')
        if (showModal != 'showModalTrue') {

            $('#btnWelcomeModal').trigger('click');
            setTimeout(function () {
                $('.close-welcome-modal').trigger('click')
            }, 1500);
            localStorage.setItem('showModal', 'showModalTrue');
        }
    },

    installApp: function () {
        console.log('installing PWA')
        app.deferredPrompt.prompt();
        installButton.disabled = true;

        // Wait for the user to respond to the prompt
        deferredPrompt.userChoice.then(choiceResult => {
            if (choiceResult.outcome === "accepted") {
                console.log("PWA setup accepted");
                installButton.hidden = true;
            } else {
                console.log("PWA setup rejected");
            }
            installButton.disabled = false;
            deferredPrompt = null;
        });
    },

    searchPost: function (e) {

        e.preventDefault();
        let userInput = $('.search-post input').val();

        $('.container-post').hide();
        $('.container-post:contains("' + userInput + '")').show()

        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    },

    setGoalAchievement: function (e) {
        e.preventDefault();
        let achievementStatus = $(this).parent().prev().find('.goalAchievement');
        achievementStatus.toggleClass('dangerScore').toggleClass('greenScore');

        let goalId = achievementStatus.data('id');

        $.ajax(
            {
                url: Routing.generate('set-achievement', { 'goalId': goalId }),
                method: "POST",
            }).done(function (response) {
                e.preventDefault();
                if (null !== response) {
                    return;
                } else {
                    console.log('Problème');
                }
            }).fail(function (jqXHR, textStatus, error) {
                console.log(jqXHR);
                console.log(textStatus);
                console.log(error);
            });
    }
}

document.addEventListener('DOMContentLoaded', app.init)