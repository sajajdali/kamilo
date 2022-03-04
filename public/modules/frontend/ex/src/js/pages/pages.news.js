function init() {
    console.log('init function works');

    // variables
    var $newsSlider = $('.advertisement__slider');

    // news section slider
    $newsSlider.slick({
        autoplay: true,
        dots: true,
        appendDots: $('.adwrapslider__controls'),
        dotsClass: 'slider__controls__dots',
        arrows: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        draggable: false,
        mobileFirst: true,

        // this function is for customizing slider buttons

        customPaging: function (slider, i) {
            var slideNumber = (i + 1),
                totalSlides = slider.slideCount;

            
            return '<a class="dot" role="button" title="' + slideNumber + ' of ' + totalSlides + '"><span class="string">' + slideNumber + '/' + totalSlides + '</span></a>';
        }
    });
    
}

$(function () {
    init();
});