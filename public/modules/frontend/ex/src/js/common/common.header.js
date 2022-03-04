function init() {
  var $header = $('.header');
  var $headerNav = $('.header__nav');
  var headerHeight = $header.height();
  var scrollTop;
  var timeout;
  var _prevScrollTop = 0;

  function onScroll() {
    scrollTop = _getScrollTop();

    if(scrollTop > headerHeight + 200){
      $header.addClass('header--fixed');
    }else{
      $header.removeClass('header--trans');
    }

    if(scrollTop < headerHeight + 10){
      $header.removeClass('header--fixed');
    }

    if(scrollTop > windowHeight){
      $header.addClass('header--trans');
      if(scrollTop > _prevScrollTop){ // scrolling down
        $header.removeClass('header--show');
      }else if(scrollTop < _prevScrollTop){ // scrolling up
        $header.addClass('header--show');
      }
    }else{
      $header.removeClass('header--show');
    }

    _prevScrollTop = scrollTop;
  }

  window.addEventListener('scroll', onScroll);
}

function mobileMenu() {
	var $wrapMenu = $('.header__nav');
  var $headerMenu = $('.header__nav');
  var $childMenu = $('.haschild >a');
  var $mobileBtn = $('#navBtn');

  /**
   * Show Menu
   */
  $mobileBtn.on('click',function () {
    if( $body.hasClass('menu-open') ){
      $childMenu.each(function () {
        $(this).parent().find('ul').slideUp('fast');
      });
    }
    $wrapMenu.toggleClass('show-wrap');
    $body.toggleClass('menu-open');
    $(this).toggleClass('close');
  });

  /**
   * Close Menu When Click Outside
   */
  $document.on('click',function(event){
    if( $wrapMenu.hasClass('show-wrap') ){
      if (!$(event.target).closest('.header__nav,#navBtn').length) {
        $mobileBtn.removeClass('close');
        $childMenu.each(function () {
          $(this).find('.header .has-child ul').slideUp('fast');
        });
        setTimeout(function () {
          $wrapMenu.removeClass('show-wrap');
          $body.removeClass('menu-open');
        },250);
      }
    }
  });

  /**
   * Open Child Menu On Click
   */
  $childMenu.each(function () {
    var $this = $(this);
    $this.on('click', function (e) {
      if( $body.hasClass('menu-open') ) {
      	e.preventDefault();
        $this.parent().find('>ul').stop().slideToggle('fast');
      }
    });
  });

  $window.resize(function () {
    if( windowWidth <= SCREEN.XMD && !$body.hasClass('menu-open') ){
      $wrapMenu.css('display','none');
      setTimeout(function () {
        $wrapMenu.css('display','flex');
      },500);
    }
  });
}

$(function() {
  init();
  mobileMenu()
});
