# SS API Search

## Introduction

Makes a PHP API searchable through the OpenSearch specification.
Doesn't actually generate any documentation output, it relies
on being fed with external content URLs, which it simply links to in search results.

Currently the only way to import APIs is through the GotAPI XML format
explained at http://www.gotapi.com/contribute/index.html.
These files are ge

We originally planned to use this service particularly to avoid building our own
search solution, but it turned out to be a dead and unresponsive project.

## Setup

The module uses Sphinx search, which needs its own cron job:

	sapphire/sake Sphinx/reindex

## Usage: Import

Import through CLI: 

	php sapphire/cli-script.php SSAPIGotApiImporterController file="<absolute-path>/index.xml" version=2.4

## Maintainer

 * Ingo Schommer (ingo at silverstripe dot com)