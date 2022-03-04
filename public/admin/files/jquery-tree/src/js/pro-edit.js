$(function () {
    $('#tree ul:first').tree({
        collapseUiIcon: 'fa fa-folder-o',
        expandUiIcon: 'fa fa-folder-open-o',
    });
    setTimeout(function(){
        $('#tree > ul > .expanded > ul .expanded ul').hide();
        $('#tree > ul > .expanded > ul .expanded .fa-folder-open-o').removeClass('fa-folder-open-o').addClass('fa-folder-o');
        $('#tree > ul > .expanded > ul .expanded').removeClass('expanded').addClass('collapsed');
        
    },300);
    
    $('#switch').click(function () {
        
        if ($(this).is(':checked')) {
            $('#treeRow').slideUp({ duration: 300, easing: "easeInOutQuart" });
        }
        else {
            $('#treeRow').slideDown({ duration: 300, easing: "easeInOutQuart" });
        }
        
    });
    if ($('#switch').is(':checked')) {
        $('#switch').trigger('click');
    }
    
    $(window).load(function () {
        $('body').on( 'change', '#treeRow .expanded input[type="checkbox"]', function () {
            var _self = $(this);
            setTimeout(function () {
                if ( _self.parent().find('.leaf input[type="radio"]').length )
                {
                    if ( _self.is(':checked') )
                    {
                        _self.parent().find('.leaf:first-of-type input[type="radio"]').prop('checked', true);
                    }
                    else
                    {
                        _self.parent().find('.leaf input[type="radio"]').prop('checked', false);
                    }
                }
            }, 50 )
        })
        $('body').on( 'click', '#treeRow .leaf input[type="radio"]', function () {
            var _self = $(this);
            var _parent = _self.parent().parent().parent().find('> input[type="checkbox"]');
            if ( ! _parent.is(':checked') )
                _parent.click();
        })
        // $('body').on( 'click', '#treeRow li.collapsed input[type="checkbox"], #treeRow li.collapsed .daredevel-tree-label', function () {
        //     var _self = $(this);
        //     if ( _self.hasClass('daredevel-tree-label') )
        //     {
        //         _self.prev().prev().click();
        //         _self.prev().click();
        //     }else {
        //         _self.prev().click();
        //     }
        // })
        $('body').on(
            'click',
            '#treeRow li.expanded input[type="checkbox"],' +
            '#treeRow li.expanded .daredevel-tree-label,' +
            '#treeRow li.collapsed input[type="checkbox"], ' +
            '#treeRow li.collapsed .daredevel-tree-label',
            function () {
            var _self = $(this);
            
            if ( _self.hasClass('daredevel-tree-label') )
                _self.prev().click();
    
            setTimeout(function () {
                if ( _self.parent().find('> input[type="checkbox"]').is(':checked') )
                {
                    if ( _self.parent().hasClass('collapsed') )
                    {
                        if ( _self.hasClass('daredevel-tree-label') )
                        {
                            _self.prev().prev().click();
                        }else {
                            _self.prev().click();
                        }
                    }
                }
                else
                {
                    if ( ! _self.parent().hasClass('collapsed') )
                    {
                        if ( _self.hasClass('daredevel-tree-label') )
                        {
                            _self.prev().prev().click();
                        }else {
                            _self.prev().click();
                        }
                    }
                }
            }, 50 )
        })
    })
});