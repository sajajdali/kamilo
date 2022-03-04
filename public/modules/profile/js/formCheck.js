$(document).ready(function () {

    /* ------------ Form Steps Responsive ----------- */

    function sizeCheck() {
        if ($(window).width() >= 480) {
            $('.rejim-steps li').fadeIn();
        }
        else {
            $('.rejim-steps li').hide();
            $('.rejim-stepsActive').show();
        }
    }

    $(window).resize(function () {
        sizeCheck();
    });
    document.body.onload = function () {
        sizeCheck();
    }
    window.onscroll = function () {
        sizeCheck();
    }

    /* ----------------------- */

    $('.mainContentIn').hide();
    $('.mainContentIn:nth-of-type(1)').show();
    $('.rejim-steps li:nth-child(1)').addClass('rejim-stepsActive');

    /* ---- Next Button --- */

    var selectedRejim = '';
    var selectedRejimLevel = '';
    var selectedRejimType = '';

    $('.nextStep').click(function () {
        var getNextBtnData = $(this).attr("data-stepName");
        var NextBtnDataClass = '.' + getNextBtnData;
        var thisBtn = $(this);
        var generaValue = 'N';
        sizeCheck();

        if (getNextBtnData == 'generalInfo') {

            generalValue = 'N';
            if (
                $('#lastName').val() == null ||
                $('#lastName').val() == '' ||

                $('#name').val() == null ||
                $('#name').val() == '' ||

                $('#yearV').val() == null ||
                $('#yearV').val() == '' ||

                $('#moV').val() == null ||
                $('#moV').val() == '' ||

                $('#daV').val() == null ||
                $('#daV').val() == '' ||

                $('#mobile').val() == null ||
                $('#mobile').val() == '' ||

                isNaN($('#mobile').val())
            ) {

                if (isNaN($('#tel').val())) {
                    $('.ifIsNan1').slideDown();
                }
                else if ($('#tel').val() == null || $('#tel').val() == '') {
                    $('.ifIsNan1').slideUp();
                }
                else {
                    $('.ifIsNan1').slideUp();
                }
                if ($('#lastName').val() == null || $('#lastName').val() == '') {

                    $('#lastName').addClass('redShadow');
                    $('.generalInfo .fillAlert').slideDown();

                } else {

                    $('#lastName').removeClass('redShadow');
                }
                if ($('#name').val() == null || $('#name').val() == '') {

                    $('#name').addClass('redShadow');
                    $('.generalInfo .fillAlert').slideDown();
                } else {
                    $('#name').removeClass('redShadow');
                }
                if ($('#yearV').val() == null || $('#yearV').val() == '') {

                    $('#yearV').addClass('redShadow');
                    $('.generalInfo .fillAlert').slideDown();

                } else {
                    $('#yearV').removeClass('redShadow');
                    $('.generalInfo .fillAlert').slideDown();
                }
                if ($('#moV').val() == null || $('#moV').val() == '') {

                    $('#moV').addClass('redShadow');
                    $('.generalInfo .fillAlert').slideDown();

                } else {
                    $('#moV').removeClass('redShadow');
                }
                if ($('#daV').val() == null || $('#daV').val() == '') {

                    $('#daV').addClass('redShadow');
                    $('.generalInfo .fillAlert').slideDown();
                } else {
                    $('#daV').removeClass('redShadow');
                }
                if ($('#mobile').val() == null || $('#mobile').val() == '') {
                    $('.ifIsNan2').slideUp();
                    $('#mobile').addClass('redShadow');
                    $('.generalInfo .fillAlert').slideDown();
                } else {
                    if (isNaN($('#mobile').val())) {
                        $('.ifIsNan2').slideDown();
                    } else {
                        $('#mobile').removeClass('redShadow');
                        $('.ifIsNan2').slideUp();
                    }
                }
            } else if (isNaN($('#tel').val())) {
                $('.generalInfo .fillAlert').slideUp();
                $('input').removeClass('redShadow');
                $('.ifIsNan2').slideUp();
                if (isNaN($('#tel').val())) {
                    $('.ifIsNan').slideDown();
                }
                else if ($('#tel').val() == null || $('#tel').val() == '') {
                    $('.ifIsNan').slideUp();
                }
                else {
                    $('.ifIsNan').slideUp();
                }
            }
            else {
                generalValue = 'Y';
                $('.generalInfo .fillAlert').slideUp();
                $('.generalInfo input,.generalInfo select').removeClass('redShadow');
                $('.ifIsNan').slideUp();
                $('.ifIsNan2').slideUp();
                $('.generalInfo .inputDiv input').each(function () {
                    var resVal = $(this).val();
                    var resClass = '.' + $(this).attr('id');
                    $(resClass).text(resVal);
                });
            }
        }
        else if (getNextBtnData == 'bodyInfo') {
            generalValue = 'Y';
            $('.bodyInfo .inputDiv input.requestedNum').each(function () {
                var bodyThis = $(this);
                var resVal = $(this).val();
                if (resVal == null ||
                    resVal == ''
                ) {
                    generalValue = 'N';
                    $('.bodyInfo .fillAlert').slideDown();
                    $(bodyThis).parent().find('.ifIsNan').slideUp();
                    $(bodyThis).addClass('redShadow');
                } else {
                    if (isNaN(resVal)) {
                        generalValue = 'N';
                        $(bodyThis).removeClass('redShadow');
                        $(bodyThis).parent().find('.ifIsNan').slideDown();
                    } else {
                        $(bodyThis).removeClass('redShadow');
                        $(bodyThis).parent().find('.ifIsNan').slideUp();
                    }
                }
            });
            if (generalValue == 'Y') {
                $('.bodyInfo .fillAlert').slideUp();
                $('.bodyInfo .inputDiv input.requestedNum').removeClass('redShadow');
                $('.bodyInfo .inputDiv input').each(function () {
                    var resVal = $(this).val();
                    var resClass = '.' + $(this).attr('id');
                    $(resClass).text(resVal);
                });
            }
        }

        else if (getNextBtnData == 'chooseDiet') {
            generalValue = 'N';

            if (selectedRejim == '' || ( selectedRejimLevel == '' && selectedRejimType == 1 ) ) {
                $('.chooseDiet .fillAlert').fadeIn();
            } else {
                generalValue = 'Y'
                $('.chooseDiet .fillAlert').fadeOut();
            }
        }
        else if (getNextBtnData == 'essQuestions') {
            generalValue = 'Y';

            $('.requested').each(function () {
                var thisReq = $(this);
                var reqInput = $(this).find('input');

                if (!$(reqInput).is(':checked')) {
                    generalValue = 'N';
                    $(thisReq).parent().find('.essQuestions').find('.pleaseFill').fadeIn();
                } else {
                    $(thisReq).parent().find('.essQuestions').find('.pleaseFill').fadeOut();
                }
            });


        }
        else if (getNextBtnData == 'billing') {
            generalValue = 'N';
        }
        else {
            generalValue = 'Y';
        }
        if (generalValue == 'Y') {
            $(thisBtn).parent().hide();
            $('.rejim-steps li').removeClass('rejim-stepsActive');
            if ($(thisBtn).parent().is(':nth-of-type(1)')) {
                $('.rejim-steps li:nth-child(2)').addClass('rejim-stepsActive');
                $('.mainContentIn:nth-of-type(2)').delay(200).fadeIn(200);
            }
            if ($(thisBtn).parent().is(':nth-of-type(2)')) {
                $('.rejim-steps li:nth-child(3)').addClass('rejim-stepsActive');
                $('.mainContentIn:nth-of-type(3)').delay(200).fadeIn(200);
            }
            if ($(thisBtn).parent().is(':nth-of-type(3)')) {
                $('.rejim-steps li:nth-child(4)').addClass('rejim-stepsActive');
                $('.mainContentIn:nth-of-type(4)').delay(200).fadeIn(200);
            }
            if ($(thisBtn).parent().is(':nth-of-type(4)')) {
                $('.rejim-steps li:nth-child(5)').addClass('rejim-stepsActive');
                $('.mainContentIn:nth-of-type(5)').delay(200).fadeIn(200);
            }
            if ($(thisBtn).parent().is(':nth-of-type(5)')) {
                $('.rejim-steps li:nth-child(6)').addClass('rejim-stepsActive');
                $('.mainContentIn:nth-of-type(6)').delay(200).fadeIn(200);
            }
            if ($(thisBtn).parent().is(':nth-of-type(6)')) {
                $('.rejim-steps li:nth-child(7)').addClass('rejim-stepsActive');
                $('.mainContentIn:nth-of-type(7)').delay(200).fadeIn(200);
            }
            if ($(thisBtn).parent().is(':nth-of-type(7)')) {
                $('.rejim-steps li:nth-child(8)').addClass('rejim-stepsActive');
                $('.mainContentIn:nth-of-type(8)').delay(200).fadeIn(200);
            }
            if ($(thisBtn).parent().is(':nth-of-type(8)')) {
                $('.rejim-steps li:nth-child(9)').addClass('rejim-stepsActive');
                $('.mainContentIn:nth-of-type(9)').delay(200).fadeIn(200);
            }
            sizeCheck();
        }
    });


    $('.prevStep').click(function () {
        $(this).parent().hide();
        $('.rejim-steps li').removeClass('rejim-stepsActive');
        if ($(this).parent().is(':nth-of-type(9)')) {
            $('.rejim-steps li:nth-child(8)').addClass('rejim-stepsActive');
            $('.mainContentIn:nth-of-type(8)').delay(200).fadeIn(200);
        }
        if ($(this).parent().is(':nth-of-type(8)')) {
            $('.rejim-steps li:nth-child(7)').addClass('rejim-stepsActive');
            $('.mainContentIn:nth-of-type(7)').delay(200).fadeIn(200);
        }
        if ($(this).parent().is(':nth-of-type(7)')) {
            $('.rejim-steps li:nth-child(6)').addClass('rejim-stepsActive');
            $('.mainContentIn:nth-of-type(6)').delay(200).fadeIn(200);
        }
        if ($(this).parent().is(':nth-of-type(6)')) {
            $('.rejim-steps li:nth-child(5)').addClass('rejim-stepsActive');
            $('.mainContentIn:nth-of-type(5)').delay(200).fadeIn(200);
        }
        if ($(this).parent().is(':nth-of-type(5)')) {
            $('.rejim-steps li:nth-child(4)').addClass('rejim-stepsActive');
            $('.mainContentIn:nth-of-type(4)').delay(200).fadeIn(200);
        }
        if ($(this).parent().is(':nth-of-type(4)')) {
            $('.rejim-steps li:nth-child(3)').addClass('rejim-stepsActive');
            $('.mainContentIn:nth-of-type(3)').delay(200).fadeIn(200);
        }
        if ($(this).parent().is(':nth-of-type(3)')) {
            $('.rejim-steps li:nth-child(2)').addClass('rejim-stepsActive');
            $('.mainContentIn:nth-of-type(2)').delay(200).fadeIn(200);
        }
        if ($(this).parent().is(':nth-of-type(2)')) {
            $('.rejim-steps li:nth-child(1)').addClass('rejim-stepsActive');
            $('.mainContentIn:nth-of-type(1)').delay(200).fadeIn(200);
        }
        sizeCheck();
    });


    /* -------------- Choose Diet ----------- */


    /* --- Help Buttons --- */
    $('.helpBtn').click(function(){
        var helpId =  '.' + $(this).attr("data-HelpCode");
        
        $('.popUpHelpBg').fadeIn(200);
        $(helpId).fadeIn(200);
    });


    $('.popUpHelpBg').click(function () {
        $('.popUpHelp').fadeOut(200);
        $('.popUpHelpBg').fadeOut(200);
    });
    $('.popUpHelpClose').click(function () {
        $('.popUpHelp').fadeOut(200);
        $('.popUpHelpBg').fadeOut(200);
    });


    /* --- Choose Rejims --- */


    $('.hasChild').click(function () {
        $('.orderRejim .fa-angle-down').removeClass('RejimSlectedShow');
        $(this).find('.orderRejim .fa-angle-down').addClass('RejimSlectedShow');

        var targetDiv = '.' + $(this).attr("data-rejimCode");
        var targetDivCode = $(this).attr("data-rejimCode");
        var targetFather = '.' + $(this).attr("data-Father");
        var targetFather1th = $(this).attr("data-Father");
        var fatherOfFather = '.' + $('.hasChild[data-rejimCode=' + targetFather1th + ']').attr("data-Father");
        var fatherOfFatherCode = $('.hasChild[data-rejimCode=' + targetFather1th + ']').attr("data-Father");

        $('.hasChild[data-rejimCode=' + '"' + targetFather1th + '"],.hasChild[data-rejimCode=' + '"' + fatherOfFatherCode + '"]').find('.orderRejim .fa-angle-down').addClass('RejimSlectedShow');

        $(targetDiv).find('.slide').stop().fadeTo("0", 1);
        $(targetFather).find('.slide').stop().fadeTo("slow", 0.33);
        $(targetFather).find('.slide:has(.hasChild[data-rejimCode=' + targetDivCode + '])').stop().fadeTo("0", 1);

        $('.rejimMain:not(' + targetFather + ',' + targetDiv + ',' + fatherOfFather + ')').hide();

        $(targetFather).stop().fadeIn(300);

        $(targetDiv).stop().fadeIn(300);

        /*---- Scroll ----*/
        $('html, body').stop().delay(300).animate({
            scrollTop: $(targetDiv).offset().top - 100
        }, 300);

        $('.chooseDiet .slide.hasLevels').removeClass('hasLevels');

        $( '.rejimLevelSelect').fadeOut();
        $('.yourChoise').fadeOut();
        selectedRejim = '';
        selectedRejimLevel = '';


    });

    /* -------- Final Rejim Select --------- */
    $('.finalRejim').click(function () {
        var _self = $(this);
        selectedRejimName = $(this).attr("data-Value");
        var targetFather = '.' + $(this).attr("data-Father");
        var targetFather1th = $(this).attr("data-Father");
        var rejimType = $(this).attr("data-rejim-type");
        var fatherOfFather = '.' + $('.hasChild[data-rejimCode=' + '"' + targetFather1th + '"]').attr("data-Father");
        var fatherOfFatherCode = $('.hasChild[data-rejimCode=' + '"' + targetFather1th + '"]').attr("data-Father");
        var fatherOfFatherOfFather = '.' + $('.hasChild[data-rejimCode=' + '"' + fatherOfFatherCode + '"]').attr("data-Father");
        var fatherOfFatherOfFatherCode = $('.hasChild[data-rejimCode=' + '"' + fatherOfFatherCode + '"]').attr("data-Father");

        $('.chooseDiet .slide.hasLevels').removeClass('hasLevels');

        if ( _self.hasClass('hasRejimLevel') )
        {
            $('.orderRejim .fa-angle-down').removeClass('RejimSlectedShow');
            $('.hasChild[data-rejimCode=' + '"' + targetFather1th + '"],.hasChild[data-rejimCode=' + '"' + fatherOfFatherCode + '"],.hasChild[data-rejimCode=' + '"' + fatherOfFatherOfFatherCode + '"]').find('.orderRejim .fa-angle-down').addClass('RejimSlectedShow');

            $('.orderRejim .finalRejimCheck').removeClass('finalRejimCheckShow');
            $(this).find('.orderRejim .finalRejimCheck').addClass('finalRejimCheckShow');

            _self.parent().addClass('hasLevels');


            $(targetFather).find('.slide').stop().fadeTo("0", 1);

            $('.rejimMain:not(' + targetFather + ',' + fatherOfFatherOfFather + ',' + fatherOfFather + ')').hide();
            $('.chooseDiet .fillAlert').fadeOut();

            $('.finalSelectetResult').text(selectedRejimName);
            selectedRejim = selectedRejimName;
            selectedRejimType = rejimType;
        }
        else
        {
            $('.orderRejim .fa-angle-down').removeClass('RejimSlectedShow');
            $('.hasChild[data-rejimCode=' + '"' + targetFather1th + '"],.hasChild[data-rejimCode=' + '"' + fatherOfFatherCode + '"],.hasChild[data-rejimCode=' + '"' + fatherOfFatherOfFatherCode + '"]').find('.orderRejim .fa-angle-down').addClass('RejimSlectedShow');

            $('.orderRejim .finalRejimCheck').removeClass('finalRejimCheckShow');
            $(this).find('.orderRejim .finalRejimCheck').addClass('finalRejimCheckShow');
            $('.yourChoise').fadeIn();


            $(targetFather).find('.slide').stop().fadeTo("0", 1);

            $('.rejimMain:not(' + targetFather + ',' + fatherOfFatherOfFather + ',' + fatherOfFather + ')').hide();
            $('.chooseDiet .fillAlert').fadeOut();

            $('.finalSelectetResult').text(selectedRejimName);
            selectedRejim = selectedRejimName;
            selectedRejimType = rejimType;
        }
    });

    $( '.rejimLevelSelect a').click(function () {
        $('.yourChoise').fadeIn();
        selectedRejimLevel = $(this).attr( 'data-level' );
        $('.selected-level').removeClass('selected-level');
        $(this).addClass('selected-level');
    });

    $('.cancel').click(function () {
        selectedRejim = '';
        $('.yourChoise').fadeOut();
        $('.orderRejim .finalRejimCheck').removeClass('finalRejimCheckShow');
    });

});


/* ----------- Owls ----------- */


$(window).load(function () {
    $(".owl-carousel").each(function () {
        $(this).data('owlCarousel').updateVars();
    });
});


$(document).ready(function () {


    $(".next").click(function () {
        $(".owl-sliders").trigger('owl.next');
    });
    $(".prev").click(function () {
        $(".owl-sliders").trigger('owl.prev');
    });

    $(".owl-sliders").owlCarousel({
        lazyLoad: false,
        items: 3,
        itemsDesktop: [1200, 3],
        itemsDesktopSmall: [992, 2],
        itemsTablet: [768, 2],
        itemsMobile: [480, 1],
        afterInit: function (elm) {

            if (elm.find('.owl-controls.clickable').is(':visible')) {

                elm.parent('.posR').find('.next, .prev').show();
            } else {
                elm.parent('.posR').find('.next, .prev').hide();
            }
        },
        afterUpdate: function (elm) {
            if (elm.find('.owl-controls.clickable').is(':visible')) {

                elm.parent('.posR').find('.next, .prev').show();
            } else {
                elm.parent('.posR').find('.next, .prev').hide();
            }
        }
    });

    $("#owl-2").owlCarousel({
        lazyLoad: false,
        items: 3,
        itemsDesktop: [1200, 3],
        itemsDesktopSmall: [992, 2],
        itemsTablet: [768, 2],
        itemsMobile: [480, 1],
        afterInit: function () {

            if ($('#owl-2').find('.owl-controls.clickable').is(':visible')) {

                $('.next, .prev').show();
            } else {
                $('.next, .prev').hide();
            }
        },
        afterUpdate: function () {
            if ($('#owl-2').find('.owl-controls.clickable').is(':visible')) {

                $('.next1, .prev1').show();
            } else {
                $('.next1, .prev1').hide();
            }
        }
    });
    $(".next1").click(function () {
        $("#owl-2").trigger('owl.next');
    })
    $(".prev1").click(function () {
        $("#owl-2").trigger('owl.prev');
    })

    $("#owl-3").owlCarousel({
        lazyLoad: false,
        items: 3,
        itemsDesktop: [1200, 3],
        itemsDesktopSmall: [992, 2],
        itemsTablet: [768, 2],
        itemsMobile: [480, 1],
        afterInit: function () {

            if ($('#owl-3').find('.owl-controls.clickable').is(':visible')) {

                $('.next3, .prev3').show();
            } else {
                $('.next3, .prev3').hide();
            }
        },
        afterUpdate: function () {
            if ($('#owl-3').find('.owl-controls.clickable').is(':visible')) {

                $('.next3, .prev3').show();
            } else {
                $('.next3, .prev3').hide();
            }
        }
    });
    $(".next3").click(function () {
        $("#owl-3").trigger('owl.next');
    })
    $(".prev3").click(function () {
        $("#owl-3").trigger('owl.prev');
    })

    $("#owl-4").owlCarousel({
        lazyLoad: false,
        items: 3,
        itemsDesktop: [1200, 3],
        itemsDesktopSmall: [992, 2],
        itemsTablet: [768, 2],
        itemsMobile: [480, 1],
        afterInit: function () {

            if ($('#owl-4').find('.owl-controls.clickable').is(':visible')) {

                $('.next4, .prev4').show();
            } else {
                $('.next4, .prev4').hide();
            }
        },
        afterUpdate: function () {
            if ($('#owl-4').find('.owl-controls.clickable').is(':visible')) {

                $('.next4, .prev4').show();
            } else {
                $('.next4, .prev4').hide();
            }
        }
    });
    $(".next4").click(function () {
        $("#owl-4").trigger('owl.next');
    })
    $(".prev4").click(function () {
        $("#owl-4").trigger('owl.prev');
    })

    $("#owl-5").owlCarousel({
        lazyLoad: false,
        items: 3,
        itemsDesktop: [1200, 3],
        itemsDesktopSmall: [992, 2],
        itemsTablet: [768, 2],
        itemsMobile: [480, 1],
        afterInit: function () {

            if ($('#owl-5').find('.owl-controls.clickable').is(':visible')) {

                $('.next5, .prev5').show();
            } else {
                $('.next5, .prev5').hide();
            }
        },
        afterUpdate: function () {
            if ($('#owl-5').find('.owl-controls.clickable').is(':visible')) {

                $('.next5, .prev5').show();
            } else {
                $('.next5, .prev5').hide();
            }
        }
    });
    $(".next5").click(function () {
        $("#owl-5").trigger('owl.next');
    })
    $(".prev4").click(function () {
        $("#owl-5").trigger('owl.prev');
    })

    $("#owl-6").owlCarousel({
        lazyLoad: false,
        items: 3,
        itemsDesktop: [1200, 3],
        itemsDesktopSmall: [992, 2],
        itemsTablet: [768, 2],
        itemsMobile: [480, 1],
        afterInit: function () {

            if ($('#owl-6').find('.owl-controls.clickable').is(':visible')) {

                $('.next6, .prev6').show();
            } else {
                $('.next6, .prev6').hide();
            }
        },
        afterUpdate: function () {
            if ($('#owl-6').find('.owl-controls.clickable').is(':visible')) {

                $('.next6, .prev6').show();
            } else {
                $('.next6, .prev6').hide();
            }
        }
    });
    $(".next6").click(function () {
        $("#owl-6").trigger('owl.next');
    })
    $(".prev6").click(function () {
        $("#owl-6").trigger('owl.prev');
    })


});

/* ---------------- Body Images Upload --------------- */

$(document).ready(function () {
    var deleteuploadObj = $("#fileuploader").uploadFile({
        url: base_url + "/profile/uploadFile",
        dragDrop: true,
        multiple: true,
        fileName: "myfile",
        returnType: "json",
        showDelete: true,
        statusBarWidth: 200,
        showPreview: true,
        previewHeight: "100px",
        previewWidth: "100px",
        deleteCallback: function (data, pd) {
            $.post(base_url + "/profile/deleteFile", {op: "delete", name: data},
                function (resp, textStatus, jqXHR) {


                });
            pd.statusbar.hide(); //You choice.
        }
    });

});

/* --------- Resid Page -------- */

$(document).ready(function () {

    $('.residPage .rejim-steps li').removeClass('rejim-stepsActive');
    $('.residPage .rejim-steps li a').addClass('rejim-steps-disabled');
    $('.residPage .rejim-steps li:last-child a').removeClass('rejim-steps-disabled');
    $('.residPage .rejim-steps li:last-child').addClass('rejim-stepsActive');

});












/*---------- Rejimplus -----------*/
$(document).ready(function(){
    $('.radio-div').click(function () {
        var outputIs = $(this).attr('data-select');
        $(this).parent().find('.default-radio-img').show();
        $(this).parent().find('.selected-radio-img').hide();
        $(this).find('.selected-radio-img').show();
        $(this).parent().find('.radio-div').removeClass('is-on');
        $(this).addClass('is-on');
        $(this).parent().find('input').val('');
        $(this).parent().find('input').val(outputIs);
    });

    /**
     * pmw body shake select
     */
    $('.pmw-body-shaking-select .pmw-radio-div').click(function () {
        var self = $(this);
        var data = self.attr('data-select');

        $('.pmw-body-shaking-select .pmw-radio-div').removeClass('selected-body');
        self.addClass('selected-body').parent().prev().val( data );
    })

    /**
     * pmw sickness select
     */
    $('.pmw-sickness-select .pmw-radio-div > .this-body').click(function () {
        var self = $(this).parent();
        var data = self.attr('data-select');

        // $('.pmw-sickness-select .pmw-radio-div').removeClass('pmw-selected');
        if ( self.hasClass('pmw-selected') )
        {
            self.removeClass('pmw-selected').find('input[type="hidden"]').val( 'false' );
        }
        else
        {
            self.addClass('pmw-selected').find('input[type="hidden"]').val( 'true' );
        }
    });

    $('.gender-select-box .radio-div').click(function () {
        var _this = $(this);
        var _data = _this.attr('data-select');

        if ( _data == 1 )
        {
            $('.wrist-little').attr( 'data-select', '15' );
            $('.wrist-med').attr( 'data-select', '17' );
            $('.wrist-large').attr( 'data-select', '19' );
        }

        if ( _data == 2 )
        {
            $('.wrist-little').attr( 'data-select', '14' );
            $('.wrist-med').attr( 'data-select', '16' );
            $('.wrist-large').attr( 'data-select', '18' );
        }
    })


    /**
     * pmw next step validations
     */
    $('.pmw-next-step').click(function () {
        // var self = $(this);
        // var has_error = false;
        //
        // self.prev().prev().find( '.necessary' ).each(function () {
        //     var _self = $(this);
        //     var _self_val = _self.find( 'input,select' ).val();
        //
        //     if ( _self_val == '' || ! _self_val )
        //     {
        //         _self.addClass( 'warninger' );
        //         if ( has_error === false )
        //             has_error = true;
        //     }
        // });
        // if ( has_error === true )
        //     return false;


        var generalValue = 'N';
        var self = $(this);

        self.parent().prev().prev().find( '.necessary' ).each(function () {
            var _self = $(this);

            _self.find( 'input,select' ).each(function () {
                var __self = $(this);
                var _self_val = __self.val();
                var _self_int = parseInt(_self_val);
                var _self_max = parseInt( __self.attr('max') );
                var _self_min = parseInt( __self.attr('min') );

                if (
                    _self_val == '' ||
                    ! _self_val ||
                    ( __self.hasClass('most_number') && ! Number.isInteger( _self_int ) ) ||
                    ( _self_int > _self_max ) ||
                    ( _self_int < _self_min )
                )
                {
                    _self.addClass( 'warninger' );
                    generalValue = 'NN';
                }
                else
                {
                    _self.removeClass( 'warninger' );
                }
            });
        });
        if ( generalValue == 'NN')
        {
            pmw_show_error_box();
            return false;
        } else{
            pmw_hide_error_box();
        }
    })

    /**
     * target weight slider
     */
    if ( $('.weight-slider').length )
    {
        var _min = parseInt( $('.set-target-weight').attr('data-min') );
        var _max = parseInt( $('.set-target-weight').attr('data-max') );
        var _current = parseInt( $('.set-target-weight').attr('data-current') );

        $('.weight-slider').slider({
            min: _min,
            max: _max,
            value: _current,
            step: 1,
            slide: function( event, ui ) {
                $('.set-target-weight h4 span').text( ui.value );
                $('.target-weight-input').val( ui.value );
            }
        });

        $('.set-weight-slider-box .max-weight').click(function () {
            var _current = parseInt( $('.set-target-weight h4 span').text() );
            var _new = ( _current >= _max ) ? _current : _current + 1;
            $( ".weight-slider" ).slider( "value", _new );
            $('.set-target-weight h4 span').text( _new );
            $('.target-weight-input').val( _new );
        })

        $('.set-weight-slider-box .min-weight').click(function () {
            var _current = parseInt( $('.set-target-weight h4 span').text() );
            var _new = ( _current <= _min ) ? _current : _current - 1;
            $( ".weight-slider" ).slider( "value", _new );
            $('.set-target-weight h4 span').text( _new );
            $('.target-weight-input').val( _new );
        })

        var _min_weight_interval = null;
        $('.set-weight-slider-box .min-weight').mousedown( function () {
            _this = $(this);
            _min_weight_interval = setInterval(function () {
                _this.click();
            }, 150)
        })
        $('.set-weight-slider-box .min-weight').mouseup( function () {
            clearInterval( _min_weight_interval );
        })

        var _max_weight_interval = null;
        $('.set-weight-slider-box .max-weight').mousedown( function () {
            _this = $(this);
            _max_weight_interval = setInterval(function () {
                _this.click();
            }, 150)
        })
        $('.set-weight-slider-box .max-weight').mouseup( function () {
            clearInterval( _max_weight_interval );
        })
    }

    /**
     * prevent image select
     */
    $('img').mousedown(function () {
        return false;
    })
});


/**
 * payam weber function
 */
function pmw_show_error_box() {
    var form_result = $('.form-result');
    var _less_size = 60;

    if ( $(window).width() < 780 )
        _less_size = 100;

    console.log( $(window).width() );

    if ( form_result.length )
    {
        form_result.find('.error-box').fadeIn();
        $('html,body').animate({
            scrollTop : form_result.offset().top - _less_size
        });
    }
}
function pmw_hide_error_box() {
    var form_result = $('.form-result');

    if ( form_result.length )
    {
        form_result.find('.error-box').fadeOut();
    }
}







/* fancy box for guide in rejim select */

$(document).ready(function () {
    $('.selectArea').on("click",function () {
        var attr = $(this).attr('data-rejimcode');
        var hasTarget = "#"+$(this).attr('data-rejimcode');
        hasTarget = $(hasTarget).parent();
        if(typeof attr !== typeof undefined && attr !== false){
            $('html, body').stop().delay(300).animate({
                scrollTop: $(hasTarget).offset().top - 100
            },300);
        }
    });
    var url = window.location.href;
    if(url.indexOf("data-rejim") >= 0){
        forceSelectDiet();
    }
});

function forceSelectDiet(){
    var url = window.location.href;

    var type = url.split('?data-rejim=');
    var hash = '';
    if(type.length > 1)
        hash = type[1];

    var rejimId = hash;
    rejimId ='#rejimNumber-'+rejimId;
    var finalR = $(rejimId);
    var firstParent  = finalR.parents('.finalRejim').attr('data-father');
    var secondParent;
    var thirdParent;
    setTimeout(function () {
        finalR.parent().trigger('click');
    },1200);
    $('.selectArea').each(function () {
        var myThis = $(this);
        if($(this).attr('data-rejimcode')==firstParent){
            secondParent = $(this).attr('data-father');
            setTimeout(function () {
                $(myThis).trigger('click');
            },800);
        }
    });
    $('.selectArea').each(function () {
        var myThis2 = $(this);
        if($(this).attr('data-rejimcode')==secondParent){
            thirdParent = $(this).attr('data-father');
            setTimeout(function () {
                $(myThis2).trigger('click');
            },400);
        }
    });
    $('.selectArea').each(function () {
        var myThis3 = $(this);
        if($(this).attr('data-rejimcode')==thirdParent){
            $(myThis3).parents('owl-item').trigger('click');
            $(myThis3).trigger('click');
        }
    });
}



