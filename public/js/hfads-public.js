(function( $ ) {
	'use strict';

	function resizeBanner() {

		let height = 0,
			margin = 0;

		if($('body').hasClass('admin-bar')) {
			margin = 32;
		}

		if(0 < $('.hfads-header').length) {
			height = parseFloat($('.hfads-header').height());

			$('.hfads-header').css({
				top: margin + 'px'
			});

			$('body.hfads-header-enable').css({
				'margin-top' : ( height + margin ) + 'px'
			})
		}

		if(0 < $('.hfads-footer').length) {
			height = parseFloat($('.hfads-footer').height());

			$('body.hfads-footer-enable').css({
				'margin-bottom' : height + 'px'
			})
		}
	}



	$('body').on('click', '.hfads-closing', function(){

		let target = $(this).data('target');

		$('.hfads-' + target).fadeOut('fast');

		if(target == 'header') {
			$('body.hfads-header-enable').css({
				'margin-top' : '0px',
			})
		}

		if(target == 'bottom') {

			$('body.hfads-footer-enable').css({
				'margin-bottom' : '0px'
			})
		}

		$.ajax({
			url: hfads.url,
			type: 'POST',
			data: {
				target: target
			}
		})
	});

	$(document).on('ready', function(){
		resizeBanner()
	});

	window.addEventListener('resize', function(){
		resizeBanner()
	})

})( jQuery );
