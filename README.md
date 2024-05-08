# Introduction

[![CI](https://github.com/silverstripe/api.silverstripe.org/actions/workflows/ci.yml/badge.svg)](https://github.com/silverstripe/api.silverstripe.org/actions/workflows/ci.yml)

SilverStripe API docs for the core system in different versions,
generated through [Doctum](https://github.com/code-lts/doctum#readme).

 - The documentation is generated from working copies located in `src/`. This folder is initially empty, the working copies are created through `makedocs.sh`.
 - The PHP code does not have to be accessible through the website, all documents are static HTML files. 
 - All generated content to be viewed publicly should be stored in the `htdocs/` subfolder.
 - Set up your vhost to serve from `htdocs` folder.

## Requirements

 * Git
 * Composer
 * PHP 8.1 or newer

## Installation

 1. Clone the repo to your local development environment
 2. Run `composer install` which will install Doctum
 3. Run `makedoc.sh` to build the static API docs (will take some time and generates ~900Mb new files)

## Usage

### Generate the Docs

 * Run the `makedoc.sh` script as a cronjob, usually a nightly run at 3am is fine:
	`0 3 * * * /sites/api/www/makedoc.sh`

### Add a new major ersion

 * Add a new version in the `'versions'` key in `conf/doctum.json`
 * Make sure https://github.com/silverstripe/supported-modules has been updated with correct branch mapping
 * Run `makedoc.sh` and confirm the generation runs through properly
 * Make a commit of the updated `conf/doctum.json`
 * Update the redirections in `.htaccess` to the stable version number (if releasing a new stable major version)
 * Make a separate commit with the redirection (explained in deployment below)

**Please note:** If you are changing the default version (i.e. a making a new stable major release), update the default version in `src/Lookup.php`.

### Deployment to production

This is now hosted on SilverStripe Platform, you can deploy from the dashboard. `makedoc.sh` is run on a nightly cron as defined in `platform.yml`.

### Symbol Lookup

The project comes with a simple PHP script to convert PHP symbols (classes, methods, properties)
to their URL representations in the API docs, and redirects there.
The lookup is primarily used by [doc.silverstripe.org](https://doc.silverstripe.org/)
to drive its custom `[api:<symbol-name>]` links in Markdown, without coupling it tightly
to the used API generator URL layout.

Parameters:

 * `q`: (required) Class name, method name (`<class>::<method>()` or `<class>-><method>()`),
   as well as property name ((`<class>::$<property>` or `<class>-><property>`).
 * `version`: (optional) Version of the targeted module. Should map to a folder name. Default is defined in `src/Lookup.php`.
 * `module`: (optional) Module name. Should map to a folder name. Defaults to framework.

Examples:

 * `/search/lookup.php?q=DataObject`: Shows `DataObject` docs in default version of framework
 * `/search/lookup.php?q=DataObject::get()&version=3.0`: Shows `DataObject::get()` docs in `3.0` version of framework
 * `/search/lookup.php?q=DataObject::get()&version=3.0`: Shows `DataObject::get()` docs in `3.6` version of framework (or whatever is the latest stable minor version)
 * `/search/lookup.php?q=DPSPayment&module=payment`: Shows `DPSPayment` class docs in the `ecommerce` module
 * `/search/lookup.php?q=SilverStripe\ORM\DataExtension::onBeforeWrite()&version=4`: Shows `SilverStripe\ORM\DataExtension::onBeforeWrite()` docs in (4.x) version of framework

## Contributing

While SilverStripe self-hosts this project, community contributions to the code are very welcome :) Please check out our [guide to contributing code](https://docs.silverstripe.org/en/contributing/code/) on silverstripe.org
