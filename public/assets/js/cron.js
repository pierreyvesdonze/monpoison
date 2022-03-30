const cronAutoSober = require('node-cron');

cronAutoSober.schedule('* * * * *', function () {
    $.ajax(
        {
            url: Routing.generate('sober_add_auto'),
            method: "POST",
        }).done(function (response) {
            if (null !== response) {
                console.log("autoSober activated")
            } else {
                console.log('Problème');
            }
        }).fail(function (jqXHR, textStatus, error) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(error);
        });
});

cronAutoSober.start();