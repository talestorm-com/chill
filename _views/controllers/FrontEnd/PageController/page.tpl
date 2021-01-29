<div class="CommonPageWrapper {$controller->MC}PageWrapper Infopage_{$page->properties->get('css')} {if !($page->properties->get_filtered('system',['Boolean','DefaultFalse']))} CommonInfoPage {/if}">    
    {$page->render_content()}
</div>