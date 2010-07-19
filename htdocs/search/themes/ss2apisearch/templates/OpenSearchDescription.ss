<?xml version="1.0" encoding="UTF-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
	<ShortName>SilverStripe API<% if Version %> $Version<% end_if %></ShortName>
	<Description>SilverStripe API Documentation - search classes, methods and properties on http://api.silverstripe.org</Description>
	<Tags>development web api</Tags>
	<Query role="example" searchTerms="DataObject" />
	<Url type="application/atom+xml" template="$BaseHref/opensearch/doSearch?q={searchTerms}&amp;offset={startIndex?}&amp;limit={count?}&amp;format=atom<% if Version %>version=$Version&amp;<% end_if %>"/>
</OpenSearchDescription>