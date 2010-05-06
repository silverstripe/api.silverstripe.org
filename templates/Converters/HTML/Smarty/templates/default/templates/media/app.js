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
		
		// Highlight currently selected anchor
		// TODO This should be replaced by a "h3:target" css rule
		// once we have valid HTML ids (anchor links currently contain $ and underscores)
		if(document.location.href.match(/#(.*)/)) {
			var anchor = RegExp.$1;
			// Assumption: header element is on same level as anchor
			if(anchor) $('a[name=' + anchor + ']').siblings(':header').addClass('anchor-highlight');
			$('a[href^=#]').live('click', function(e) {
				$('.anchor-highlight').removeClass('anchor-highlight');
			});
		}
	});
}(jQuery));