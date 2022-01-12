var app = {

    init: function () {

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        console.log('init');

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
}

document.addEventListener('DOMContentLoaded', app.init)