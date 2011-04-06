(function($, undefined) {

	$(document).ready(function() {
		$("#logout").click(function() {
			window.location = SITE_URL + "/index.php?action=logout";
		});
		
		$('.color_selector div').css('backgroundColor', $("#say_color").val());
		$('.color_selector').ColorPicker({
			color: $("#say_color").val(),
			onShow: function(colorpicker) {
				$(colorpicker).fadeIn(500);
				return false;
			},
			onHide: function(colorpicker) {
				$(colorpicker).fadeOut(500);
				return false;
			},
			onChange: function(hsb, hex, rgb) {
				$("#say_color").val("#"+hex);
				$('.color_selector div').css('backgroundColor', '#' + hex);
			}
		});
		
		$('.choose').click(function() {
			var selector = $("input[name='item_id[]']");
			selector.attr("checked", !selector.is(':checked'));
		});
	});

})(jQuery)