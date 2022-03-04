$(function () {
	/**
	 * @file notific8.js
	 * @author Will Steinmetz
	 */

	 if (!window.WS) {
	 	window.WS = {};
	 }

	 if (!WS.hasOwnProperty('jQuery')) {
	 	WS.jQuery = {};
	 }

	 WS.jQuery.notific8 = {
	 	init: function() {
	 		$('#loginNotify').click(function(event) {
	 			var settings = {
	 				life: 3000,
	 				theme: 'ruby',
	 				sticky: false,
	 				verticalEdge: 'center',
	 				horizontalEdge: 'top',
	 				zindex: 1100
	 			},
	 			$button = $(this);


	 			$.notific8($.trim("موارد خالی را پر کنید."), settings);

	 			$button.attr('disabled', 'disabled');

	 			setTimeout(function() {
	 				$button.removeAttr('disabled');
	 			}, 1000);
	 		});
	 	}
	 };

	 $(function() {
	 	WS.jQuery.notific8.init();
	 });
	});
