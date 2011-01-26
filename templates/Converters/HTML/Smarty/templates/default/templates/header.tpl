<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>{$title}</title>
	<link rel="stylesheet" type="text/css" href="{$subdir}media/screen.css"></link>
	<script type="text/javascript" src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>
	<script type="text/javascript" src="{$subdir}media/app.js"></script>
	<script type="text/javascript" src="http://silverstripe.org/toolbar/javascript/toolbar.min.js?site=api&amp;searchShow=false"></script>
	<link rel="stylesheet" type="text/css" href="http://silverstripe.org/toolbar/css/toolbar.css" />	
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" >

</head>
{if strpos($basedir, 'module') !== FALSE}
	{assign var="isModule" value=true}
{else}
	{assign var="isModule" value=false}
{/if}

{if strpos($maintitle, '2.2') !== FALSE}
	{assign var="release" value='2.2'}
{elseif strpos($maintitle, '2.3') !== FALSE}
	{assign var="release" value='2.3'}
{elseif strpos($maintitle, '2.4') !== FALSE}
	{assign var="release" value='2.4'}
{elseif strpos($maintitle, 'trunk') !== FALSE}
	{assign var="release" value='trunk'}
{/if}

<body class="{$bodyclass} phpDocumentor">   
	<div id="Container">
	<div id="Header">
		<div id="Logo">
			<h1>
				<a href="http://api.silverstripe.org/">
					API Documentation
				</a>
			</h1>
		</div>
		
		<ul class="packages horizontal">
			<li>Index:</li>
			{assign var="packagehaselements" value=false}
	    {foreach from=$packageindex item=thispackage}
	        {if in_array($package, $thispackage)}
	            {assign var="packagehaselements" value=true}
	        {/if}
	    {/foreach}
	    {if $packagehaselements}
		  <li><a href="{$subdir}classtrees_{$package}.html" class="menu">class tree</a></li>
			<li><a href="{$subdir}elementindex_{$package}.html" class="menu">index</a></li>
			{/if}
	    <li><a href="{$subdir}elementindex.html" class="menu">all elements</a></li>
		</ul>
		{if !$isModule}
		<ul class="releases horizontal">
			<li>Releases:</li>
			<li class="{if $release == '2.2'}current{/if}"><a href="http://api.silverstripe.org/2.2">2.2</a></li>
			<li class="{if $release == '2.3'}current{/if}"><a href="http://api.silverstripe.org/2.3">2.3</a></li>
			<li class="{if $release == '2.4'}current{/if}"><a href="http://api.silverstripe.org/2.4">2.4</a></li>
			<li class="{if $release == 'trunk'}current{/if}"><a href="http://api.silverstripe.org/trunk">trunk</a></li>
		</ul>
		{/if}
	</div>

	<div class="left">
	
		{if !$noleftindex}{assign var="noleftindex" value=false}{/if}
	
	  {if !$noleftindex}
	
			<div class="package-list">
				<h3>Packages:</h3>
				<ul>
					{section name=packagelist loop=$packageindex}
					  <li{if $packageindex[packagelist].title == $package} class="current"{/if}>
							<a href="{$subdir}{$packageindex[packagelist].link}">{$packageindex[packagelist].title}</a>
						</li>
					{/section}
				</ul>
			</div>
	
			{if $compiledclassindex}
				<div class="class-list-container">
			  <h3>Classes:</h3>
			  {eval var=$compiledclassindex}
				</div>
			{/if}
	
	  
			{if $compiledfileindex}
				<div class="file-list-container">
			  <h3>Files:</h3>
				<p class="toggle" id="file-list-toggle">
					<a href="#file-list">Show/hide</a>
				</p>
				<div id="file-list">
			  {eval var=$compiledfileindex}
				</div>
				</div>
		  {/if}

		{/if}

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

	</div>                                        

	<div class="right">
		{if !$hasel}{assign var="hasel" value=false}{/if}        
		{if $hasel}
		<h1>{$eltype|capitalize}: {$class_name}</h1>
		<p><small>Source Location: {$source_location}</small></p>
		{/if}