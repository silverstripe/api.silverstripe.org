{foreach key=subpackage item=files from=$fileleftindex}
	{if $subpackage != ""}<h4>subpackage {$subpackage}</h4>{/if}
  <ul class="package">
	{section name=files loop=$files}
		{if $files[files].link != ''}<li><a href="{$files[files].link}">{/if}
		{$files[files].title}
		{if $files[files].link != ''}</a></li>{/if}
	{/section}
  </ul>
{/foreach}
