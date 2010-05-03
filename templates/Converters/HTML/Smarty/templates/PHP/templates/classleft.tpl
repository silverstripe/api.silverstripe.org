{foreach key=subpackage item=files from=$classleftindex}
  <ul class="package">
	{if $subpackage != ""}<h4>{$subpackage}</h4>{/if}
	{section name=files loop=$files}
		{if $files[files].link != ''}<li><a href="{$files[files].link}">{/if}{$files[files].title}{if $files[files].link != ''}</a></li>{/if}
	{/section}
  </ul>
{/foreach}
