;(function($){
	$(document).ready(function () {
		/* body... */

		var currentValue  = $('#pqc_toggle').val();
		$('#toggle1').minitoggle();

		if (currentValue == 1) {
			$("#toggle1 .minitoggle").addClass('active');
			$('.minitoggle.active .toggle-handle').attr('style','transform: translate3d(40px,0,0)');
		}
		$('#toggle1').on("toggle",function (argument) {
			/* body... */
			if (argument.isActive) {
				$('#pqc_toggle').val(1);
			}else{
				$('#pqc_toggle').val(0);
			}
		});
	});
})( jQuery );