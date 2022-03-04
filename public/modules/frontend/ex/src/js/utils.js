global.$ = require('jquery');
global.jQuery = require('jquery');

global.windowWidth = window.innerWidth
  || document.documentElement.clientWidth //IE <= 8
  || document.body.clientWidth;

global.windowHeight = window.innerHeight
  || document.documentElement.clientHeight //IE <= 8
  || document.body.clientHeight;

global.requestAnimationFrame = window.requestAnimationFrame
  || window.mozRequestAnimationFrame
  || window.webkitRequestAnimationFrame
  || window.msRequestAnimationFrame;

global.cancelAnimationFrame = window.cancelAnimationFrame
  || window.mozCancelAnimationFrame;

// media query breakpoints
global.SCREEN = {
  XXS: 375,
  XS: 500,
  SM: 800,
  MD: 1050,
  XMD: 1200,
  LG: 1400,
};

global.$window = $(window);
global.$document = $(document);
global.$body = $('body');
global.$page = $('.page');

if(window.pageYOffset){
  global._getScrollTop = function() {
    return window.pageYOffset || 0;
  }
}else{
  global._getScrollTop = function() {
    return document.documentElement.scrollTop || document.body.scrollTop || 0;
  }
}

$(window).resize(function(){
  global.windowWidth = window.innerWidth
    || document.documentElement.clientWidth //IE <= 8
    || document.body.clientWidth;

  global.windowHeight = window.innerHeight
    || document.documentElement.clientHeight //IE <= 8
    || document.body.clientHeight;
});

// jquery easing
$.easing['jswing'] = $.easing['swing'];

$.extend( $.easing,
  {
    def: 'easeInOutExpo',
    easeInOutExpo: function (x, t, b, c, d) {
      if (t==0) return b;
      if (t==d) return b+c;
      if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
      return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
    }
  });