<pages>
{foreach key=name item=class from=$classes}
	<page type="class" title="{$name}" link="{$class.url}">
{foreach item=prop from=`$class.properties`}
{if $prop->type == 'method'}
		<page type="method" title="{$prop->name}"  link="{$class.url}{literal}#method{/literal}{$prop->name}" />
{elseif $prop->type == 'var'}
		<page type="property" title="{$prop->name}" link="{$class.url}{literal}#var{/literal}{$prop->name}" />
{/if}
{/foreach}
	</page>
{/foreach}
</pages>	