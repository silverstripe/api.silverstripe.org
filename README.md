# Introduction

SilverStripe API docs for the core system in different versions,
generated through [APIGen](http://apigen.org/).

The documentation is generated from working copies located in `src/`.
This folder is initially empty, the working copies are created through `makedocs.sh`.
The PHP code does not have to be accessible through the website, all documents are static HTML files. 
All generated content should be stored in the `htdocs/` subfolder.

# Requirements

 * Git
 * [http://www.phpdoc.org/](PHPDocumentor binaries) (install through PEAR: `pear install PhpDocumentor`)

# Installation and Usage

 * [Install APIGen](http://apigen.org/##installation)
 * Ensure `makedoc.sh` is executable by the webserver user
 * Run the `makedoc.sh` script as a cronjob, usually a nightly run at 3am is fine:
	`0 3 * * * /sites/ss2api/www/makedoc.sh`
 * Configure the Google CSE and Analytics keys in `conf/apigen/apigen.neon`

# Add a new version

 * Add command to `makedoc.sh`
 * Check if any new folders/files need to be added to the `--ignore` parameter
 * Add a link to `htdocs/index.html`
 * TODO Describe where to add a link to templates
 * If you're using `publishsite`, add the new folder to the `.publishinfo` excluded folders list `--excluded-folders=`
 * Run `makedoc.sh` and confirm the generation runs through properly
 * If the release is our (new) stable release, change the "/current" redirection in `htdocs/.htaccess` and `htdocs/.htaccess_live`
 * Note: Don't commit the generated files, they dont need to be versioned