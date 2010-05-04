(function($) {
	$(document).ready(function() {
		
		// Hide certain divs
		$('.toggle').each(function() {
			var $this = $(this);
			var id = $this.find('a').attr('href').replace(/#/, '');
			var contentDiv = $('#' + id);
			contentDiv.hide();
			$this.click(function(e) {
				contentDiv.toggle();
				return false;
			});
		});
		
		// Unobtrusive anchor links (trac style)
		$(':header').live('mouseover', function(e) {
			$(this).find('a.anchor').show();
		});
		$(':header').live('mouseout', function(e) {
			$(this).find('a.anchor').hide();
		});
	});
}(jQuery));