# Introduction

SilverStripe API docs for the core system in different versions,
generated through [APIGen](http://apigen.org/).

The documentation is generated from working copies located in `src/`.
This folder is initially empty, the working copies are created through `makedocs.sh`.
The PHP code does not have to be accessible through the website, all documents are static HTML files. 
All generated content should be stored in the `htdocs/` subfolder.

## Requirements

 * Git
 * [APIGen](http://apigen.org/)
 * PHP 5 (for the symbol lookup only)

## Installation

 * [Install APIGen](http://apigen.org/##installation)
 * Ensure `makedoc.sh` is executable by the webserver user

## Usage

### Generate the Docs

 * Run the `makedoc.sh` script as a cronjob, usually a nightly run at 3am is fine:
	`0 3 * * * /sites/ss2api/www/makedoc.sh`
 * Configure the Google CSE and Analytics keys in `conf/apigen/apigen.neon`

### Add a New Version

 * Add command to `makedoc.sh`
 * Check if any new folders/files need to be added to the `--ignore` parameter
 * Add a link to `htdocs/index.html`
 * TODO Describe where to add a link to templates
 * If you're using `publishsite`, add the new folder to the `.publishinfo` excluded folders list `--excluded-folders=`
 * Run `makedoc.sh` and confirm the generation runs through properly
 * If the release is our (new) stable release, change the "/current" redirection in `htdocs/.htaccess` and `htdocs/.htaccess_live`
 * Note: Don't commit the generated files, they dont need to be versioned

### Symbol Lookup

The project comes with a simple PHP script to convert PHP symbols (classes, methods, properties)
to their URL representations in the API docs, and redirects there.
The lookup is primarily used by [doc.silverstripe.org](http://doc.silverstripe.org)
to drive its custom `[api:<symbol-name>]` links in Markdown, without coupling it tightly
to the used API generator URL layout.

Parameters:

 * `q`: (required) Class name, method name (`<class>::<method>()` or <class>-><method>()`),
   as well as property name ((`<class>::$<property>` or <class>-><property>`).
 * `version`: (optional) Version of the targeted module. Should map to a folder name. Defaults to trunk.
 * `module`: (optional) Module name. Should map to a folder name. Defaults to framework.

Examples:

 * `/search/lookup.php?q=DataObject`: Shows `DataObject` docs in `trunk` version of framework
 * `/search/lookup.php?q=DataObject::get()&version=3.0`: Shows `DataObject::get()` docs in `3.0` version of framework
 * `/search/lookup.php?q=DPSPayment&module=payemtn`: Shows `DPSPayment` class docs in the `ecommerce` module