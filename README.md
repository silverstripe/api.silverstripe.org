# Introduction

SilverStripe API docs for the core system in different versions,
generated through *PHPDocumentor*.

The documentation is generated from svn working copies located in `src/`.
This folder is initially empty, the working copies are created through `makedocs.sh`.
The PHP code does not have to be accessible through the website, all documents are static HTML files. 
All generated content should be stored in the `htdocs/` subfolder.

# Requirements

 * Subversion
 * [http://www.phpdoc.org/](PHPDocumentor binaries) (install through PEAR: `pear install PhpDocumentor`)

# Installation and Usage

 * Set `memory_limit = 1024M` in your PhpDocumentor.ini (see [http://manual.phpdoc.org/HTMLframesConverter/phpedit/phpDocumentor/tutorial_phpDocumentor.howto.pkg.html#using.phpdocumentorini](phpDoc.org)).
 * Ensure `makedoc.sh` is executable by the webserver user
 * Ensure the webroot is writeable and executable by the webserver user (executable is a requirement by `realpath()` used in Smarty...)
 * Run the `makedoc.sh` script as a cronjob, usually a nightly run at 3am is fine:
	`0 3 * * * /sites/ss2api/www/makedoc.sh`
 * If used on a domain different from http://api.silverstripe.org, please set `$base_domain` in `templates/Converters/XML/GotAPI/templates/default/XMLGotAPIConverter.inc`

# Add a new version

 * Add command to `makedoc.sh`
 * Check if any new folders/files need to be added to the `--ignore` parameter
 * Add a link to `htdocs/index.html`
 * Add a link to `templates/Converters/HTML/Smarty/templates/PHP/templates/header.tpl` (in `<ul class="releases">`)
 * If you're using `publishsite`, add the new folder to the `.publishinfo` excluded folders list
   `--excluded-folders=`
 * Run `makedoc.sh` and confirm the phpdoc command running through properly
 * If the release is our (new) stable release, change the "/current" redirection in `htdocs/.htaccess` and `htdocs/.htaccess_live`
 * Note: Don't commit the generated files, they dont need to be versioned

# Search

The API can be searched through a special SilverStripe module in `htdocs/search`.
It is based around a specialized XML output template in phpDocumentor,
which gets imported into a SilverStripe database record. See `htdocs/search/README.md` for setup instructions.