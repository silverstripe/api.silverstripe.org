# Introduction

SilverStripe API docs for the core system in different versions,
generated through [APIGen](http://apigen.org/).

 - The documentation is generated from working copies located in `src/`. This folder is initially empty, the working copies are created through `makedocs.sh`.
 - The PHP code does not have to be accessible through the website, all documents are static HTML files. 
 - All generated content to be viewed publicly should be stored in the `htdocs/` subfolder.
 - Set up your vhost to serve from `htdocs` folder.

## Requirements

 * Git
 * Composer
 * PHP 5 (for the symbol lookup only)

## Installation

 1. Clone the repo to your local development environment
 2. Run `composer install` which will install apigen
 3. Run `makedoc.sh` to build the static API docs (will take some time and generates ~900Mb new files)

## Usage

### Generate the Docs

 * Run the `makedoc.sh` script as a cronjob, usually a nightly run at 3am is fine:
	`0 3 * * * /sites/ss2api/www/makedoc.sh`
 * Configure the Google CSE and Analytics keys in `conf/apigen/apigen.neon`

### Add a New Version
 * Copy a version section block in `makedoc.sh` and update the version number
 * Update the .gitignore to ignore any new files generated in `htdocs` (you don't want to commit the static generated files!)
 * Run `makedoc.sh` and confirm the generation runs through properly
 * Make a commit of the updated `makedoc.sh`
 * Update the redirections in `htdocs/.htaccess` to the stable version number
 * Make a separate commit with the redirection (explained in deployment below)

**Please note:** Often the `master` branch will be representing an unstable major version (currently 4). When this needs
to be updated, please edit `htdocs/search/lookup.php`.

### Deployment to production
 1. Raise a ticket with ops team (they have to run a script after deployment)
 2. Login to SilverStripe Platform (you'll need to ensure you have "api" stack permissions)
 3. Deploy the commit that contains the update to `makedoc.sh`
 4. Ops will run this script to generate the new static files.
 5. Once this is complete, deploy the commit with the redirects.


### Symbol Lookup

The project comes with a simple PHP script to convert PHP symbols (classes, methods, properties)
to their URL representations in the API docs, and redirects there.
The lookup is primarily used by [doc.silverstripe.org](http://doc.silverstripe.org)
to drive its custom `[api:<symbol-name>]` links in Markdown, without coupling it tightly
to the used API generator URL layout.

Parameters:

 * `q`: (required) Class name, method name (`<class>::<method>()` or <class>-><method>()`),
   as well as property name ((`<class>::$<property>` or <class>-><property>`).
 * `version`: (optional) Version of the targeted module. Should map to a folder name. Defaults to trunk. Will switch current unstable major version (e.g. 4) to "master".
 * `module`: (optional) Module name. Should map to a folder name. Defaults to framework.

Examples:

 * `/search/lookup.php?q=DataObject`: Shows `DataObject` docs in `trunk` version of framework
 * `/search/lookup.php?q=DataObject::get()&version=3.0`: Shows `DataObject::get()` docs in `3.0` version of framework
 * `/search/lookup.php?q=DataObject::get()&version=3.0`: Shows `DataObject::get()` docs in `3.6` version of framework (or whatever is the latest stable minor version)
 * `/search/lookup.php?q=DPSPayment&module=payemnt`: Shows `DPSPayment` class docs in the `ecommerce` module
 * `/search/lookup.php?q=SilverStripe\ORM\DataExtension::onBeforeWrite()&version=4`: Shows `SilverStripe\ORM\DataExtension::onBeforeWrite()` docs in `master` (4.x) version of framework
