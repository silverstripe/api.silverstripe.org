# Introduction

[![CI](https://github.com/silverstripe/api.silverstripe.org/actions/workflows/ci.yml/badge.svg)](https://github.com/silverstripe/api.silverstripe.org/actions/workflows/ci.yml)

SilverStripe API docs for the core system in different versions, generated through [Doctum](https://github.com/code-lts/doctum#readme).

 - The documentation is generated from `git cloned` repositories located in `data/`. This folder is created and populated by running the `SilverStripe\ApiDocs\Tasks\BuildDocsTask` which is usually run via the `SilverStripe\ApiDocs\Build\BuildDocsQueuedJob` queued job.
- This will generated a series of static HTML files which are located in the `static` folder in subdirectories that match the major version e.g. `static/5` is for CMS 5. These static files are generated as part of `BuildDocsTask`.
- Apache will serve these static files when requested by using rules defined in `.htaccess` files in the root, `static` and `public` folders.
- The Silverstripe composer requirements are fairly and are the to run queuedjobs, as well as ensure the site will be easily deployable to Silverstripe cloud.

> [!WARNING]
> If you are running this locally with DDEV, make sure you set your `docroot` to `.`, instead of the `public` folder:
> `ddev config docroot '.' && ddev restart`

## Add a new major version

- Make sure https://github.com/silverstripe/supported-modules has been updated with correct branch mapping and the composer.lock file in this repository has been updated i.e. run `composer update` in the root of this repository.
- Add a new major version in the `doctum-conf/doctum.json` file.

## Change the default major version

- Update the redirections in `public/.htaccess` to the latest major stable version
- Update the `DEFAULT_BRANCH` constant in `app/src/Lookup/Lookup.php` to the new stable major version.

## Symbol lookup

The project comes with a simple PHP script to convert PHP symbols (classes, methods, properties)
to their URL representations in the API docs, and redirects there.

The lookup is primarily used by [doc.silverstripe.org](https://doc.silverstripe.org/)
to drive its custom `[api:<symbol-name>]` links in Markdown, without coupling it tightly
to the used API generator URL layout.

### Parameters:

- `q`: (required) Class name, method name (`<class>::<method>()` or `<class>-><method>()`),
   as well as property name ((`<class>::$<property>` or `<class>-><property>`).
- `version`: (optional) Version of the targeted module. Should map to a folder name. Default is defined in `app/src/Lookup/Lookup.php`.
- `module`: (optional) Module name. Should map to a folder name. Defaults to framework.

### Examples:

- `/search/lookup?q=SilverStripe\ORM\DataObject`: Shows `DataObject` docs in default version of framework
- `/search/lookup?q=SilverStripe\ORM\DataObject::get()&version=4`: Shows `DataObject::get()` docs in `4` version of framework
- `/search/lookup?q=SilverStripe\ORM\DataExtension::onBeforeWrite()&version=4`: Shows `SilverStripe\ORM\DataExtension::onBeforeWrite()` docs in (4.x) version of framework

## Contributing

While SilverStripe self-hosts this project, community contributions to the code are very welcome :) Please check out our [guide to contributing code](https://docs.silverstripe.org/en/contributing/code/) on silverstripe.org
