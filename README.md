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

# Add a new versoin

 * Add command to `makedoc.sh`
 * Check if any new folders/files need to be added to the `--ignore` parameter
 * Add a link to `index.html`
 * Make a new Google Custom Searcn engine (see below)
 * Add a link to `templates/Converters/HTML/Smarty/templates/PHP/templates/header.tpl` (in `<ul class="releases">`)
 * If you're using `publishsite`, add the new folder to the `.publishinfo` excluded folders list
   `--excluded-folders=`
 * Run `makedoc.sh` and confirm the phpdoc command running through properly
 * Note: Don't commit the generated files, they dont need to be versioned

# Google Custom Search Engines

They are a free and easy way to generate search results, phpDocumentor doesnt provide this by default.
The searches work on URL matching, with the assumption that all releases have a toplevel folder under
http://api.silverstripe.com, e.g. http://api.silverstripe.com/2.4 for the 2.4 release, and all searchable
URLs are within this folder.

1. Sign up for http://www.google.com/cse
2. Create a new search engine
3. Go to "Sites" settings and change "Included sites" to: `http://api.silverstripe.org/2.2/*` (adapt to your version).
4. Select "Include just this specific page or URL pattern I have entered"
5. Save
6. Go to "Basics" and copy the "Search engine unique ID" into the `header.tpl template`