<?php
class Page extends SiteTree {

	public static $db = array(
	);

	public static $has_one = array(
	);

}
class Page_Controller extends ContentController {

	public function init() {
		parent::init();

		Requirements::themedCSS('layout'); 
		Requirements::themedCSS('typography'); 
		Requirements::themedCSS('form'); 
		
		// Add link elements
		$opensearchLinkTemplate = '<link rel="search" type="application/opensearchdescription+xml" href="' . Director::absoluteBaseUrl() . 'opensearch/description/?version=%s" title="%s" />';
		$versions = SSAPIProperty::get_versions();
		if($versions) foreach($versions as $version) {
			Requirements::insertHeadTags(sprintf(
				$opensearchLinkTemplate, 
				Convert::raw2att($version),
				'SilverStripe API ' . Convert::raw2att($version)
			));
		}
	}
}