# Introduction

[![Build Status](https://travis-ci.org/silverstripe/api.silverstripe.org.svg?branch=master)](https://travis-ci.org/silverstripe/api.silverstripe.org)

SilverStripe API docs for the core system in different versions,
generated through [Sami](https://github.com/FriendsOfPHP/Sami).

 - The documentation is generated from working copies located in `src/`. This folder is initially empty, the working copies are created through `makedocs.sh`.
 - The PHP code does not have to be accessible through the website, all documents are static HTML files. 
 - All generated content to be viewed publicly should be stored in the `htdocs/` subfolder.
 - Set up your vhost to serve from `htdocs` folder.

## Requirements

 * Git
 * Composer
 * PHP 7.0 or newer
 * Node 12 or newer (if running a Netlify development server)

## Installation

 1. Clone the repo to your local development environment
 2. Run `composer install` which will install Sami
 3. Run `makedoc.sh` to build the static API docs (will take some time and generates ~900Mb new files)

## Usage

### Generate the Docs

 * Run the `makedoc.sh` script as a cronjob, usually a nightly run at 3am is fine:
	`0 3 * * * /sites/api/www/makedoc.sh`

### Add a New Version

 * Copy a version section block in `sami.json` and ensure you use the appropriate value for `versionmap` depending
   on whether the module started its life at version 1 or version 4 (for SilverStripe 4)
 * Run `makedoc.sh` and confirm the generation runs through properly
 * Make a commit of the updated `sami.json`
 * Update the redirections in `netlify.toml` to the stable version number (if changing major versions)
 * Make a separate commit with the redirection (explained in deployment below)

**Please note:** Often the `master` branch will be representing an unstable major version. When this needs
to be updated, please edit `_functions/lookup.js`.

### Deployment to production

This is hosted on Netlify. Can be rebuilt manually through their UI, or via a build hook. Automatic
builds from git commits are not yet implemented.

### Symbol Lookup

The project comes with a simple Lambda function to convert PHP symbols (classes, methods, properties)
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

 * `/.netlify/functions/lookup?q=DataObject`: Shows `DataObject` docs in `trunk` version of framework
 * `/.netlify/functions/lookup?q=DataObject::get()&version=3.0`: Shows `DataObject::get()` docs in `3.0` version of framework
 * `/.netlify/functions/lookup?q=DataObject::get()&version=3.0`: Shows `DataObject::get()` docs in `3.6` version of framework (or whatever is the latest stable minor version)
 * `/.netlify/functions/lookup?q=DPSPayment&module=payment`: Shows `DPSPayment` class docs in the `ecommerce` module
 * `/.netlify/functions/lookup?q=SilverStripe\ORM\DataExtension::onBeforeWrite()&version=4`: Shows `SilverStripe\ORM\DataExtension::onBeforeWrite()` docs in `master` (4.x) version of framework

## Contributing

While SilverStripe self-hosts this project, community contributions to the code are very welcome :) Please check out our [guide to contributing code](https://github.com/silverstripe/silverstripe-framework/blob/4.0/docs/en/05_Contributing/01_Code.md) on silverstripe.org
