function init() {
	
	if ($('.tab').length < 1) return;
	
	$('.tab').each(function() {
		var $tab = $(this);
		var $navItems = $tab.find('[data-toggle]');
		var $navItemsFirst = $tab.find('.tab-nav ul li:first-of-type');
		var $paneItems = $tab.find('.tab-pane');
		var $tabSelect = $tab.find('.tab-select select');		

    	$navItemsFirst.addClass('active');

		$navItems.on('click', function(e) {
			e.preventDefault();
			var $paneItemId = $(this).attr('href');
			var $paneItem = $tab.find($paneItemId);

			$navItems.parent().removeClass('active');
			$(this).parent().addClass('active');
			
			$paneItems.removeClass('active fade-in');
			$paneItem.addClass('active');

			if(windowWidth > SCREEN.SM){								
				setTimeout(function() {
					$paneItem.addClass('fade-in');
				},200);
			}
			if(windowWidth <= SCREEN.SM){				
				$paneItem.addClass('fade-in');
			}
		});


	});
}

$(function() {
	init();
});