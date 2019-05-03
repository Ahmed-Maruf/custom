;(function($) {
	$(document).ready(function() {
		$('#toggle1').minitoggle();
		var current_value = $('#pqrc_toggle').val();
		if (current_value == "1") {
			console.log(1);
			$('#toggle1 .toggle-handle').attr('style','transform: translate3d(36px, 0, 0)');
			$('#toggle1 .minitoggle').addClass('active');
		}
		$('#toggle1').on('toggle', function(e) {
			if (e.isActive) {
				$('#pqrc_toggle').val(1);
			} else {
				$('#pqrc_toggle').val(0);
			}
		});
	});
})(jQuery);
