# Introduction

SilverStripe API docs for the core system in different versions,
generated through *PHPDocumentor*.

The documentation is generated from svn working copies located in `svn/`.
This folder is initially empty, the working copies are created through `makedocs.sh`.
The PHP code does not have to be accessible through the website, all documents are static HTML files. 


# Requirements

 * Subversion
 * [http://www.phpdoc.org/](PHPDocumentor binaries) (install through PEAR: `pear install PhpDocumentor`)

# Installation and Usage

 * Ensure `makedoc.sh` is executable by the webserver user
 * Ensure the webroot is writeable by the webserver user
 * Run the `makedoc.sh` script as a cronjob, usually a nightly run at 3am is fine:
	`0 3 * * * /sites/ss2api/www/makedoc.sh`

# Add a new version

 * Add command to `makedoc.sh`
 * Check if any new folders/files need to be added to the `--ignore` parameter
 * Add a link to `index.html`
 * Add a link to `templates/Converters/HTML/Smarty/templates/PHP/templates/header.tpl` (in `<ul class="releases">`)
 * If you're using `publishsite`, add the new folder to the `.publishinfo` excluded folders list
   `--excluded-folders=`
 * Run `makedoc.sh` and confirm the phpdoc command running through properly
 * If the release is our (new) stable release, change the "/current" redirection in `.htaccess` and `.htaccess_live`
 * Note: Don't commit the generated files, they dont need to be versioned
