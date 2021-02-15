<!-- metadata -->
<title>{$this->sv_title}</title>
<meta name="title" content="{$this->sv_title}"/>
<meta name="keywords" content="{$this->sv_keywords}"/>
<meta name="description" content="{$this->sv_description}"/>
{if $this->og_support}
    <meta property="og:url" content="{$this->og_url}" />
    <meta property="og:locale" content="{$this->og_locale}" />
    <meta property="og:title" content="{$this->sv_og_title}" />
    <meta property="og:description" content="{$this->sv_og_description}" />
    {if $this->og_image_support}
        <meta property="og:image" content="{$this->sv_og_image}" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />
    {/if}
{/if}
<!-- end of metadata -->
