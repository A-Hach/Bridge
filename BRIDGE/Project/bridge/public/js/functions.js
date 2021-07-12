// Fixe to show scroll only whene height > max-height
function fixeMaxScrollHeight() {
    for (let i = 0; i < $('.c-scrollable').length; i++)
        $('.c-scrollable').eq(i).css({
            'visibility': 'hidden',
            'display': 'block'
        }).css({
            'overflow-y': $('.c-scrollable').eq(i).innerHeight() < $('.c-scrollable')[i].scrollHeight ? 'scroll' : 'hidden'
        }).css({
            'display': 'none',
            'visibility': 'visible'
        });

    for (let i = 0; i < $('.scrollable-list').length; i++)
        $('.scrollable-list').eq(i).css({
            'overflow-y': $('.scrollable-list').eq(i).innerHeight() < $('.scrollable-list')[i].scrollHeight ? 'scroll' : 'hidden'
        });
}

// Set sides radius
function setSidesRadius() {
    for (let i = 0; i < $('.sides-radius').length; i++)
        $('.sides-radius').eq(i).css('border-radius', $('.sides-radius').eq(i).innerHeight() / 2 + 'px');
}

// Fixe margin top of main section bottom of nav bar
function fixeMainMarginTop() {
    $('#main-page-content').css('margin-top', $('.fixed-nav-bar').outerHeight(true) + 'px');
}

// Return sum width(0) or height(1) of node list
function dimNodeListSum(list, type = 'h', padding = false, border = false, margin = false) {
    let sum = 0;
    if (type == 'h')
        for (let i = 0; i < list.length; i++)
            sum += padding && border && margin ? list.eq(i).outerHeight(true) : padding && border ? list.eq(i).outerHeight() : padding ? list.eq(i).innerHeight() : list.eq(i).height();
    else
        for (let i = 0; i < list.length; i++)
            sum += padding && border && margin ? list.eq(i).outerWidth(true) : padding && border ? list.eq(i).outerWidth() : padding ? list.eq(i).innerWidth() : list.eq(i).width();

    return sum;
}

// Fixe sticky
function fixeSticky() {
    if (document.querySelector('.sticky') != undefined) {
        // Screen - offsetTop
        let availHeight = $(window).height() - $('.sticky').eq(0).offset().top;

        for (let i = 0; i < $('.sticky').length; i++) {
            $('.sticky').eq(i).css({
                'top': $('.sticky').eq(i).outerHeight() - availHeight > 0 ? $(window).height() - $('.sticky').eq(i).outerHeight(true) + 'px' : $('.fixed-nav-bar').outerHeight(true) + 10 + 'px'
            });
        }
    }
}

// To get random string
function getRandomString(number) {
    const characters = 'n7bv5c6x2wml8azke9jhgrtyuf4d3s1i_q0po';
    let random = '';

    for (let i = 0; i < number; i++)
        random += characters[Math.floor(Math.random() * characters.length)];
    return random;
}

// Search an element in array
function isInArray(array, element) {
    for (let i = 0; i < array.length; i++)
        if (array[i] == element)
            return true;

    return false;
}