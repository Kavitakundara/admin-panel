// loader
"use strict";

$(document).ready(function () {
    setTimeout(function () {
        $('#preloader').fadeOut('slow', function () {
            $('#main-content').fadeIn('slow');
        });
    }, 3000);
});
