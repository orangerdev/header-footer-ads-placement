(function( $ ) {
	'use strict';

	$(document).on('ready', function(){
		$('body').on('click', '.hfads-closing', function(){
			let target = $(this).data('target');

			$('body').removeClass('hfads-' + target + '-enable');
			$('.hfads-' + target).fadeOut('fast');

			$.ajax({
				url: hfads.url,
				type: 'POST',
				data: {
					target: target
				}
			})
		});
	});

})( jQuery );
