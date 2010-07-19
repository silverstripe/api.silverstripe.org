<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
	<title>SilverStripe API Search</title> 
	<link href="http://api.silverstripe.org"/>
	<updated>$LastUpdated</updated>
	<author> 
		<name>SilverStripe Ltd.</name>
	</author> 
	<id>urn:uuid:$Version</id>
	<opensearch:totalResults>$Results.TotalItems</opensearch:totalResults>
	<opensearch:startIndex>$Offset</opensearch:startIndex>
	<opensearch:itemsPerPage>$Limit</opensearch:itemsPerPage>
	<opensearch:Query role="request" searchTerms="$Query" startIndex="$Offset" count="$Limit" />
	<link rel="self" href="http://example.com/New+York+History?pw=3&amp;format=atom" type="application/atom+xml"/>
	<link rel="first" href="http://example.com/New+York+History?pw=1&amp;format=atom" type="application/atom+xml"/>
	<link rel="previous" href="$Results.PrevLink" type="application/atom+xml"/>
	<link rel="next" href="$Results.NextLink" type="application/atom+xml"/>
	<link rel="search" type="application/opensearchdescription+xml" href="http://example.com/opensearchdescription.xml"/>
	<% control Results %>
	<entry>
		<title>$Title</title>
		<link href="$URL"/>
		<id>urn:uuid:$ID</id>
		<updated>$LastEdited.Format('c')</updated>
		<content type="text"></content>
	</entry>
	<% end_control %>
</feed>