(function($) {
	$('input[type="checkbox"]').on('change', function() {
		if ($(this).prop('checked')) {
			$('button[type="submit"]').prop('disabled', false)
		} else {
			$('button[type="submit"]').prop('disabled', true)
		}
	})
})(jQuery);