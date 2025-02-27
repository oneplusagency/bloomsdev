jQuery(document).ready(function($) {
    checkCookie();
    $(document).on('click', '.cookies-content .close-btn', function() {
        $('.cookie-hint-wrapper').attr('data-cookie-show', false);
        cookieValue = 'false';
        setCookie("Cookie", cookieValue, 365);
    });

    $(document).on('click', '.cookies-content .accept-btn', function() {
        $('.cookie-hint-wrapper').attr('data-cookie-show', false);
        cookieValue = 'false';
        setCookie("Cookie", cookieValue, 365);
    });



    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
        }
        return "";
    }

    function checkCookie() {
        var cookieValue = getCookie("Cookie");

        // alert(cookieValue)
        if (cookieValue == '') {
            $('.cookie-hint-wrapper').attr('data-cookie-show', true);
        } else {
            if (cookieValue == 'false') {
                $('.cookie-hint-wrapper').attr('data-cookie-show', false);
            } else {
                $('.cookie-hint-wrapper').attr('data-cookie-show', true);
            }
        }
    }

});