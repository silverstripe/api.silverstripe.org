$(function() {
	RegExp.escape= function(s) {
    return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&')
	};
	// Set current version title based on path (easier than generating version-specific templates in ApiGen)
	var path = document.location.pathname.replace(/\/$/, ''), title;
	$('#versions .dropdown-menu a').each(function() {
		var matcher = new RegExp('^' + RegExp.escape(path));
		if(matcher.test($(this).attr('href'))) {
			title = $(this).text();
		} 
	});
	if(title) $('#versions .dropdown-toggle .version').text(title);
});