


/*-----------------------------------
-------== If any kind of IE ==-------
---------------------------------- */

$(document).ready(function() {
	if (navigator.appName == 'Microsoft Internet Explorer' ||  !!(navigator.userAgent.match(/Trident/) || navigator.userAgent.match(/rv:11/)) || (typeof $.browser !== "undefined" && $.browser.msie == 1))
	{

	}else{

    }
});
/* Disable Img Select */
$(document).ready(function () {
    $('img').mousedown(function () {
        return false;
    });
});

/* ----------------------------------
-------------== Color ==-------------
---------------------------------- */

$(document).ready(function () {
    $('.select-rejim-level-2 li a').each(function () {
        var bgColor = $(this).attr('data-color');
        $(this).css({'background-color':bgColor});
    });

    /* ----------------------------------
    ----------== Select Rejim ==---------
    ---------------------------------- */

    $(document).on("click",".select-rejim-level-1 a",function(event) {
        var cat = $(this).attr('data-category');
        $('#rejim-hardness').val('');
        $('#rejim-code').val('');
        if(!$(this).parent().hasClass('is-activated')){
            $('.select-rejim-level-1 li').removeClass('is-activated');
            $(this).parent().addClass('is-activated');
        }
        $('.select-rejim-level-2 li,.select-rejim-level-3 li,.select-rejim-level-4 li').removeClass('is-activated');
        $('.select-rejim-level-4,.sub-rejims-l2-main').hide();
        $('.next-back-btns .go-to-next').removeClass('active').attr('disabled','disabled').velocity("stop", true).velocity("fadeOut", { duration: 300 });
        $('.sub-rejims-main').each(function () {
            if(cat == $(this).attr('data-category') && !$(this).is(':visible')) {
                $(this).show();
                if($(this).find('.select-rejim-level-2 li').css('opacity') == 0){
                    $(this).find('.select-rejim-level-2 li').velocity("stop").velocity({opacity:1}, {duration: 800});
                }
            }
            if(cat != $(this).attr('data-category')){
                $(this).hide();
                if($(this).find('.select-rejim-level-2 li.is-activated').length == 0){
                    $(this).find('.select-rejim-level-2 li').velocity("stop").velocity({opacity: 0}, {duration: 0});
                }
            }
        });
    });
    $(document).on("click",".select-rejim-level-2 .sub-cat-select",function(event) {
        var cat = $(this).attr('data-category');
        var bgColor = $(this).attr('data-color');
        $('#rejim-hardness').val('');
        $('#rejim-code').val('');
        var rejimCode = $(this).attr('data-rejim-code');
        $('#rejim-code').val(rejimCode);
        if(!$(this).parent().hasClass('is-activated')){
            $('.select-rejim-level-2 li,.select-rejim-level-4 li').removeClass('is-activated');
            $(this).parent().addClass('is-activated');
            $('.next-back-btns .go-to-next').removeClass('active').attr('disabled','disabled').velocity("stop", true).velocity("fadeOut", { duration: 300 });
        }
        $('.sub-rejims-main:visible .sub-rejims-l2-main .select-rejim-level-3').each(function () {
            if(cat == $(this).attr('data-category') && !$(this).is(':visible')) {
                $(this).parent().css({'background':bgColor});
                if(!$('.sub-rejims-l2-main').is(':visible')){
                    $('.sub-rejims-l2-main').velocity("fadeIn", { duration: 300 });
                }
                var myThis = $(this);
                var query = Modernizr.mq('(max-width: 1600px)');
                if(query){
                    setTimeout(function () {
                        $('html, body').stop().animate({
                            scrollTop: myThis.offset().top
                        }, 300);
                    },400);
                }
                if(!$(this).is(':visible')){
                    $(this).velocity("stop", true).velocity({opacity: 1}, {display: "block"}, {duration: 300});
                    $('.select-rejim-level-3 li,.select-rejim-level-4 li').removeClass('is-activated');
                    $('.next-back-btns .go-to-next').removeClass('active').attr('disabled','disabled').velocity("stop", true).velocity("fadeOut", { duration: 300 });
                    $('#rejim-code').val('');
                }
            }
            if(cat != $(this).attr('data-category')){
                $(this).css({'opacity': '0','display':'none'});
            }
        });
        if(!$(this).attr('data-category')){
            if($(this).attr('data-hashardness') !== typeof undefined && $(this).attr('data-hashardness') !== false){
                if(!$('.select-rejim-level-4').is(':visible')){
                    $('.select-rejim-level-4').velocity("fadeIn", { duration: 300 });
                    $('.next-back-btns .go-to-next').removeClass('active').attr('disabled','disabled').velocity("stop", true).velocity("fadeOut", { duration: 300 });
                }
                var element = $('.select-rejim-level-4');
                var query = Modernizr.mq('(max-width: 1600px)');
                if(query){
                    setTimeout(function () {
                        $('html, body').stop().animate({
                            scrollTop: element.offset().top
                        }, 300);
                    },400);
                }
            }else{
                var rejimCode = $(this).attr('data-rejim-code');
                $('#rejim-code').val(rejimCode);
                $('#rejim-hardness').val('');
                if(!$('.next-back-btns .go-to-next').is(':visible')){
                    $('.next-back-btns .go-to-next').addClass('active').removeAttr('disabled').velocity("stop", true).velocity("fadeIn", { duration: 300 });
                }
                if($('.select-rejim-level-4').is(':visible')){
                    $('.select-rejim-level-4').velocity("fadeOut", { duration: 300 });
                }
                var element = $('.next-back-btns');
                setTimeout(function () {
                    $('html, body').stop().animate({
                        scrollTop: element.offset().top
                    }, 300);
                },400);
            }
        }else {
            if($('.select-rejim-level-4').is(':visible')){
                $('.select-rejim-level-4').velocity("fadeOut", { duration: 300 });
            }
        }
    });
    $(document).on("click",".select-rejim-level-3 .sub-cat-select",function(event) {
        var attr = $(this).attr('data-hashardness');
        var rejimCode = $(this).attr('data-rejim-code');
        $('#rejim-code').val(rejimCode);
        $('#rejim-hardness').val('');
        if(typeof attr !== typeof undefined && attr !== false){
            if(!$(this).parent().hasClass('is-activated')){
                $('.next-back-btns .go-to-next').removeClass('active').attr('disabled','disabled').velocity("stop", true).velocity("fadeOut", { duration: 300 });
            }
            if(!$('.select-rejim-level-4').is(':visible')){
                $('.select-rejim-level-4').velocity("fadeIn", { duration: 300 });
                $('.next-back-btns .go-to-next').removeClass('active').attr('disabled','disabled').velocity("stop", true).velocity("fadeOut", { duration: 300 });
            }
            var element = $('.select-rejim-level-4');
            var query = Modernizr.mq('(max-width: 1600px)');
            if(query){
                setTimeout(function () {
                    $('html, body').stop().animate({
                        scrollTop: element.offset().top
                    }, 300);
                },400);
            }
        }else{
            if(!$('.next-back-btns .go-to-next').is(':visible')){
                $('.next-back-btns .go-to-next').addClass('active').removeAttr('disabled').velocity("stop", true).velocity("fadeIn", { duration: 300 });
            }
            if($('.select-rejim-level-4').is(':visible')){
                $('.select-rejim-level-4').velocity("fadeOut", { duration: 300 });
            }
            var element = $('.next-back-btns');
            setTimeout(function () {
                $('html, body').stop().animate({
                    scrollTop: element.offset().top
                }, 300);
            },400);
        }
        if(!$(this).parent().hasClass('is-activated')){
            $('.select-rejim-level-3 li').removeClass('is-activated');
            $(this).parent().addClass('is-activated');
            $('.select-rejim-level-4 li').removeClass('is-activated');
            var rejimCode = $(this).attr('data-rejim-code');
            $('#rejim-code').val(rejimCode);
        }
    });
    $(document).on("click",".select-rejim-level-4 a",function(event) {
        var hardness = $(this).attr('data-hardness');
        $('#rejim-hardness').val(hardness);
        if(!$('.next-back-btns .go-to-next').is(':visible')) {
            $('.next-back-btns .go-to-next').addClass('active').removeAttr('disabled').velocity("stop", true).velocity("fadeIn", {duration: 300});
            var element = $('.next-back-btns');
            setTimeout(function () {
                $('html, body').stop().animate({
                    scrollTop: element.offset().top
                }, 300);
            },400);
        }
        if(!$(this).parent().hasClass('is-activated')){
            $('.select-rejim-level-4 li').removeClass('is-activated');
            $(this).parent().addClass('is-activated');
        }
    });
});

function selectRejim(code,hardness){
    $('#rejim-code').val(code);
    $('.select-rejim-level-3 .sub-cat-select,.select-rejim-level-2 .sub-cat-select').each(function () {
        if($(this).attr('data-rejim-code') == code ) {
            $(this).parent().addClass('is-activated');
            /*----------*/
            var attr = $(this).attr('data-hashardness');
            if(typeof attr !== typeof undefined && attr !== false){
                $('.select-rejim-level-4').show();
                $('.next-back-btns .go-to-next').removeClass('active').attr('disabled','disabled').velocity("stop", true).velocity("fadeOut", { duration: 300 });
                if(hardness){
                    $('.select-rejim-level-4 li a').each(function () {
                        if($(this).attr('data-hardness') == hardness){
                            $(this).parent().addClass('is-activated');
                            $('#rejim-hardness').val(hardness);
                            if(!$('.next-back-btns .go-to-next').is(':visible')) {
                                $('.next-back-btns .go-to-next').addClass('active').removeAttr('disabled').velocity("stop", true).velocity("fadeIn", {duration: 300});
                                var element = $('.next-back-btns');
                                setTimeout(function () {
                                    $('html, body').stop().animate({
                                        scrollTop: element.offset().top
                                    }, 300);
                                },400);
                            }
                        }
                    });
                }
            }else{
                if(!$('.next-back-btns .go-to-next').is(':visible')) {
                    $('.next-back-btns .go-to-next').addClass('active').removeAttr('disabled').velocity("stop", true).velocity("fadeIn", {duration: 300});
                    var element = $('.next-back-btns');
                    setTimeout(function () {
                        $('html, body').stop().animate({
                            scrollTop: element.offset().top
                        }, 300);
                    },400);
                }
                $('.select-rejim-level-4').hide();
            }
            /*-----------*/
            $(this).parents('.select-rejim-level-3').show();
            $(this).parents('.select-rejim-level-3').css({'opacity':'1'});
            var cat = $(this).parents('.select-rejim-level-3').attr('data-category');
            $(this).parents('.sub-rejims-main').show();
            var level1 = $(this).parents('.sub-rejims-main').attr('data-category');
            $('.select-rejim-level-1 a').each(function () {
                if($(this).attr('data-category') == level1){
                    $(this).parent().addClass('is-activated');
                }
            });
            $(this).parents('.sub-rejims-l2-main').show();
            var bgColor ='';
            $(this).parents('.sub-rejims-main').find('.select-rejim-level-2 li .sub-cat-select').each(function () {
                if(cat == $(this).attr('data-category')) {
                    bgColor = $(this).attr('data-color');
                    $(this).parents('.select-rejim-level-2').show();
                }
            });
            $(this).parents('.sub-rejims-l2-main').css({'background-color':bgColor});
        }
    });
    var element = $('.next-back-btns');
    setTimeout(function () {
        $('html, body').stop().animate({
            scrollTop: element.offset().top
        }, 300);
    },300);

    console.log('Code: ' + $('#rejim-code').val() + ' Hardness: ' + $('#rejim-hardness').val());
}

/*--- Weight Step ----*/

$(document).ready(function () {
    $('.scale-number input').keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ($('.scale-number input').val() >= 200) {
            e.preventDefault();
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    $('.scale-number input').keyup(function (e) {
        checkWeight()
    });
    if($('.scale-number input').length > 0){
        checkWeight()
        setTimeout(function () {
            $('.scale-number input').focus();
        },300);

    }
});

function checkWeight() {
    if ($('.scale-number input').val() && $('.scale-number input').val() > 30 && $('.scale-number input').val() < 200) {
        if(!$('.next-back-btns .go-to-next').is(':visible')) {
            $('.next-back-btns .go-to-next').addClass('active').removeAttr('disabled').velocity("stop", true).velocity("fadeIn", {duration: 300});
        }
        if($('.notice-main.weight').is(':visible')){
            $('.notice-main.weight').velocity("slideUp", { duration: 300 });
        }
    }else{
        $('.next-back-btns .go-to-next').removeClass('active').attr('disabled','disabled').velocity("stop", true).velocity("fadeOut", { duration: 300 });
        if ($('.scale-number input').val()){
            if(!$('.notice-main.weight').is(':visible')){
                $('.notice-main.weight').velocity("slideDown", { duration: 300 });
            }
        }
    }
}

/* Select Offer */
var waitFor = 1;
$('.select-offer').on("click", function () {
    if(waitFor==0){
        return false;
    }
    waitFor=0;
    setTimeout(function () {
        waitFor = 1;
    },400);
    if(!$(this).find('.fa-check').is(':visible')){
        $(this).find('.fa-check').velocity("stop", true).velocity("fadeIn", { duration: 300 });
        $('.jalase-num').text(10);
        $('.total-col.red.offer').velocity("stop", true).velocity("slideDown", { duration: 300 });
        $(this).find('input').val($(this).attr('data-input-val'));
        $('#must_pay').text("245.000");
        $('#total_shop').text("350.000");
    }else{
        $(this).find('.fa-check').velocity("stop", true).velocity("fadeOut", { duration: 300 });
        $('.jalase-num').text(1);
        $('.total-col.red.offer').velocity("stop", true).velocity("slideUp", { duration: 300 });
        $(this).find('input').val('');
        $('#must_pay').text($('#total_price_final').data('price'));
        $('#total_shop').text($('#total_price_final').data('price'));
    }
});


/*----- Responsive Table -----*/

function responsiveTable (table){
    $(table).each(function(){
        var RowNum = 1;
        $(this).find('tr:gt(0)').each(function(){				// select all table rows except table header row
            // append the header for row number to the small table
            $(this).parent().parent().parent().find('.table-xs').append('<div class="row-header"> <h5></h5> </div>');
            $(this).find('td').each(function(){// select all table data
                var myThis = $(this);
                // for each td get it's equivalent header and append header text to the small table header div
                $(myThis).parent().parent().parent().parent().find('.table-xs').append('<div class="col-xs-6 header">'+$(myThis).parent().parent().find('th').eq($(myThis).index()).html()+'</div>');
                // for each td get it's text and append it to the small table data div
                $(myThis).parent().parent().parent().parent().find('.table-xs').append('<div class="col-xs-6 data">'+$(myThis).html()+'</div>');
            });
            RowNum++;
        });
    });
    $(table).parent().find('input').keyup(function () {
        var inputName = $(this).attr('name');
        var inputVal = $(this).val();
        if(!$('.break1200').is(":visible")){
            $(this).parent().parent().parent().find('input').each(function () {
                var myThis = $(this);
                if($(myThis).attr('name') == inputName){
                    $(myThis).val(inputVal);
                }
            });
        }else{
            $(this).parent().parent().parent().parent().parent().find('input').each(function () {
                var myThis = $(this);
                if($(myThis).attr('name') == inputName){
                    $(myThis).val(inputVal);
                }
            });
        }

    });
}
balanceSize();
// IMPORTANT on resize fun will be excessive load on your processor if you have many rows
$(window).on('resize load', function() {
    if(!$('.break992').is(":visible")){					// to work when shown the small table
        balanceSize();				// balance the size on resize
    }
});
// balance size will make all headers height equal data height !!! Just to fill the BG color ><
function balanceSize(){
    var i = 0 ;
    $('.header').each(function(){

        $(this).height($('.data').eq(i).height()-2);

        i++;
    });
}
responsiveTable ('.table-res-jq');
balanceSize();	// on load get balance on size if the browser has small screen

/* Select diet */
$(document).ready(function () {
    /* Use This line if there is "no" rejim from landing page */
    $('.select-rejim-level-1 li:first-of-type a').trigger('click');
});

/* Use This line if there is a rejim from landing page */
//selectRejim(36,2);