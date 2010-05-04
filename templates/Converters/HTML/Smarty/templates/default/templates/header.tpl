<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>{$title}</title>
	<link rel="stylesheet" type="text/css" href="{$subdir}media/style.css">
	<link rel="stylesheet" type="text/css" href="{$subdir}media/screen.css">
	<script type="text/javascript" src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js'></script>
	<script type="text/javascript" src="{$subdir}media/app.js"></script>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" >
</head>
{if strpos($maintitle, '2.2') !== FALSE}
	{assign var="release" value='2.2'}
{elseif strpos($maintitle, '2.3') !== FALSE}
	{assign var="release" value='2.3'}
{elseif strpos($maintitle, '2.4') !== FALSE}
	{assign var="release" value='2.4'}
{elseif strpos($maintitle, 'trunk') !== FALSE}
	{assign var="release" value='trunk'}
{/if}
<body>
	<div id="header">
		<div id="logo">
			<a href="http://api.silverstripe.org/"><img src="{$subdir}media/ss_logo.gif" alt="SilverStripe logo" /></a>
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
		
		<ul class="releases horizontal">
			<li>Releases:</li>
			<li class="{if $release == '2.2'}current{/if}"><a href="http://api.silverstripe.org/2.2">2.2</a></li>
			<li class="{if $release == '2.3'}current{/if}"><a href="http://api.silverstripe.org/2.3">2.3</a></li>
			<li class="{if $release == '2.4'}current{/if}"><a href="http://api.silverstripe.org/2.4">2.4</a></li>
			<li class="{if $release == 'trunk'}current{/if}"><a href="http://api.silverstripe.org/trunk">trunk</a></li>
		</ul>
	</div>

	<div class="row package-list">
		<ul class="horizontal">
			<li>Packages:</li>
			{section name=packagelist loop=$packageindex}
			  <li{if $packageindex[packagelist].title == $package} class="current"{/if}>
					<a href="{$subdir}{$packageindex[packagelist].link}">{$packageindex[packagelist].title}</a>
				</li>
			{/section}
		</ul>
	</div>

	<div class="left">
	
		{if !$noleftindex}{assign var="noleftindex" value=false}{/if}
	
	  {if !$noleftindex}
	
			{if $compiledclassindex}
				<div class="class-list-container">
			  <h3>Classes:</h3>
			  {eval var=$compiledclassindex}
				</div>
			{/if}
	
	  
			{if $compiledfileindex}
				<div class="file-list-container">
			  <h3>Files:</h3>
			  {eval var=$compiledfileindex}
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