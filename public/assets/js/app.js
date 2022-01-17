var app = {

    init: function () {
        console.log('init');

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        document.querySelector('.submit-subscribe').addEventListener('click', app.subscribeToPosts)

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
}

document.addEventListener('DOMContentLoaded', app.init)