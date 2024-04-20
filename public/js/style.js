$(document).ready(function () {
    let width = $(window).width();
    let responsive_mode = $('.nav-item.responsive-mode > .nav-link');

    if (width <= 768) {
        responsive_mode.attr({
            'data-widget': 'pushmenu',
            'href': '#',
            'role': 'button'
        });
    }
});

