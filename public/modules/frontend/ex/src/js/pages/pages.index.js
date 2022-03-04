function init() {

  /***************
   *  //importing slick and fancybox
   *********************/
  require('slick-carousel');
  require('@fancyapps/fancybox');

  /***************
   *  //Variables
   *********************/
  var $gallery = $('.slider');
  var $articleSlider = $('.article__slider');
  var $fancy = $('data-fancybox');
  var $galleryHeading = $('.overlay__heading');
  var $newsWrap = $('.news__wrap');
  var $vidsSlider = $('.videos__slider');
  var $headerBurger = $('.header__burger');
  var $bar = $headerBurger.find('.bar');

  /*===============================================
   *  Init fancybox
   =================================================*/
  if ($fancy.length > 0) {
    $fancy.fancybox({
      buttons: [
        "zoom",
        //"share",
        "slideShow",
        "fullScreen",
        //"download",
        /*"thumbs",*/
        "close"
      ],
      hash: false,
    });
  }

  /*===============================================
   *  INTRO SECTION
   =================================================*/

  /***************
   *  //Main
   *********************/
  $gallery.slick({
    autoplay: true,
    dots: true,
    appendDots: $('.slider__controls'),
    dotsClass: 'slider__controls__dots',
    arrows: false,
    slidesToShow: 1,
    draggable: false,
    mobileFirst: true,
    // this function is for customizing slider buttons
    customPaging: function (slider, i) {
      var slideNumber = (i + 1),
        totalSlides = slider.slideCount;

      return '<a class="dot" role="button" title="' + slideNumber + ' of ' + totalSlides + '"><span class="string">' + slideNumber + '/' + totalSlides + '</span></a>';
    }
  });
  // change the overlay header based on the changing slide
  $gallery.on('init reinit afterChange', function (event, slick, currentSlide, nextSlide) {
    var $heading = $('.slick-current').find('.slider__heading').get(0).innerText;
    $galleryHeading.text($heading);
  })

  /*===============================================
   *  NEWS SECTION
   =================================================*/
  $newsWrap.slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    vertical: true,
    infinite: true,
    prevArrow: $('.news__arrowup'),
    nextArrow: $('.news__arrowdown')
  });

  /*===============================================
   *  VIDEOS SECTION
   =================================================*/
  $vidsSlider.slick({
    rtl: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplay: false,
    prevArrow: $('.videos__btn--left'),
    nextArrow: $('.videos__btn--right'),
    centerMode: true,
    responsive: [{
      breakpoint: 1150,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
      {
        breakpoint: 900,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
    ]
  });

  // same height for video cards ---no matter what!---
  var maxHeight = 0;

  $(".card_vid").each(function () {
    if ($(this).height() > maxHeight) {
      maxHeight = $(this).height();
    }
  });

  $(".card_vid").height(maxHeight);

  /*===============================================
   *  ARTICLE SECTION
   =================================================*/
  if(windowWidth <= SCREEN.SM){        
    $articleSlider.slick({
      rtl: false,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      centerMode: true,
      responsive: [
        {
          breakpoint: 900,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 321,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            centerMode: false, 
          }
        },
      ]
    });
  }      
  /*===============================================
   *  COMMENTS SECTION
   =================================================*/

  $('.comments__slider').slick({
    autoplay: true,
    appendDots: $('.comments__controls'),
    dotsClass: 'comment-dots',
    dots: true,
    arrows: false,
    slidesToShow: 1,
    draggable: false,
    mobileFirst: true,
    // this function is for customizing slider buttons
    customPaging: function (slider, i) {
      var slideNumber = (i + 1),
        totalSlides = slider.slideCount;
      return '<a class="cmdot" role="button" title="' + slideNumber + ' of ' + totalSlides + '"><span class="str">' + slideNumber + '/' + totalSlides + '</span></a>';
    }
  });


  /*===============================================
   *  HEROES SECTION
   =================================================*/
  $('.heroes__slider').slick({
    rtl: true,
    autoplay: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    draggable: true,
    prevArrow: $('.heroes__arrow--left'),
    nextArrow: $('.heroes__arrow--right'),
    responsive: [{
      breakpoint: 1051,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 1
      }
    },
      {
        breakpoint: 800,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 500,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      },
    ]
  });
}


$(function () {
  init();
});