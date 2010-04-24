{if strpos($maintitle, '2.2') !== FALSE}
	{assign var="release" value='2.2'}
{elseif strpos($maintitle, '2.3') !== FALSE}
	{assign var="release" value='2.3'}
{elseif strpos($maintitle, '2.4') !== FALSE}
	{assign var="release" value='2.4'}
{elseif strpos($maintitle, 'trunk') !== FALSE}
	{assign var="release" value='trunk'}
{/if}

<html>
<head>
<title>{$title}</title>
<link rel="stylesheet" type="text/css" href="{$subdir}media/style.css">
<link rel="stylesheet" type="text/css" href="{$subdir}media/screen.css">
<link rel="stylesheet" href="googlesearch.css" type="text/css" />
</head>
<body>
	<div id="banner">
		<div id="header">
			<a href="http://api.silverstripe.org/"><img src="{$subdir}media/ss_logo.gif" alt="SilverStripe logo" /></a>
		</div>
	</div>
	
	<div class="search row">
		<div id="cse" style="width: 100%;">Loading</div>
		<script src="http://www.google.com/jsapi" type="text/javascript"></script>
		<script type="text/javascript">
			{literal}
		  google.load('search', '1', {language : 'en'});
		  google.setOnLoadCallback(function(){
		    var customSearchControl = new google.search.CustomSearchControl(
					{/literal}
					{if $release == '2.2'}'008918494708543759628:4zq0wh03i0m'{/if}
					{if $release == '2.3'}'008918494708543759628:k9hie5n-tpi'{/if}
					{if $release == '2.4'}'008918494708543759628:xrr3g-om4zu'{/if}
					{if $release == 'trunk'}'008918494708543759628:nf_ogd7nec8'{/if}
					{literal}
				);
		    customSearchControl.setResultSetSize(google.search.Search.SMALL_RESULTSET);
		    customSearchControl.draw('cse');
		  }, true);
			{/literal}
		</script>
	</div>
	
	<div class="row">
		<h2 class="releases-header">Releases:</h2>
		<ul class="releases horizontal">
			<li class="{if $release == '2.2'}current{/if}"><a href="http://api.silverstripe.org/2.2">2.2</a></li>
			<li class="{if $release == '2.3'}current{/if}"><a href="http://api.silverstripe.org/2.3">2.3</a></li>
			<li class="{if $release == '2.4'}current{/if}"><a href="http://api.silverstripe.org/2.4">2.4</a></li>
			<li class="{if $release == 'trunk'}current{/if}"><a href="http://api.silverstripe.org/trunk">trunk</a></li>
		</ul>
	</div>

	<div class="row">
		<h2 class="packages-header">{$package}</h2>
		<ul class="packages horizontal">
			{assign var="packagehaselements" value=false}
	    {foreach from=$packageindex item=thispackage}
	        {if in_array($package, $thispackage)}
	            {assign var="packagehaselements" value=true}
	        {/if}
	    {/foreach}
	    {if $packagehaselements}
		  <li><a href="{$subdir}classtrees_{$package}.html" class="menu">class tree: {$package}</a></li>
	  <li><a href="{$subdir}elementindex_{$package}.html" class="menu">index: {$package}</a></li>
	{/if}
	    <li><a href="{$subdir}elementindex.html" class="menu">all elements</a></li>
		</ul>
	</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr valign="top">
    <td width="200" class="menu">
{if count($ric) >= 1}
	<div id="ric">
		{section name=ric loop=$ric}
			<p><a href="{$subdir}{$ric[ric].file}">{$ric[ric].name}</a></p>
		{/section}
	</div>
{/if}
{if $hastodos}
	<div id="todolist">
			<p><a href="{$subdir}{$todolink}">Todo List</a></p>
	</div>
{/if}
      <b>Packages:</b><br />
      {section name=packagelist loop=$packageindex}
        <a href="{$subdir}{$packageindex[packagelist].link}">{$packageindex[packagelist].title}</a><br />
      {/section}
      <br /><br />
{if $tutorials}
		<b>Tutorials/Manuals:</b><br />
		{if $tutorials.pkg}
			<strong>Package-level:</strong>
			{section name=ext loop=$tutorials.pkg}
				{$tutorials.pkg[ext]}
			{/section}
		{/if}
		{if $tutorials.cls}
			<strong>Class-level:</strong>
			{section name=ext loop=$tutorials.cls}
				{$tutorials.cls[ext]}
			{/section}
		{/if}
		{if $tutorials.proc}
			<strong>Procedural-level:</strong>
			{section name=ext loop=$tutorials.proc}
				{$tutorials.proc[ext]}
			{/section}
		{/if}
{/if}
      {if !$noleftindex}{assign var="noleftindex" value=false}{/if}
      {if !$noleftindex}
      {if $compiledfileindex}
      <b>Files:</b><br />
      {eval var=$compiledfileindex}
      {/if}

      {if $compiledinterfaceindex}
      <b>Interfaces:</b><br />
      {eval var=$compiledinterfaceindex}
      {/if}

      {if $compiledclassindex}
      <b>Classes:</b><br />
      {eval var=$compiledclassindex}
      {/if}
      {/if}
    </td>
    <td>
      <table cellpadding="10" cellspacing="0" width="100%" border="0"><tr><td valign="top">

{if !$hasel}{assign var="hasel" value=false}{/if}
{if $hasel}
<h1>{$eltype|capitalize}: {$class_name}</h1>
Source Location: {$source_location}<br /><br />
{/if}