<div id="error_block">
<div class="container">
    <div class="row">
            <div class="col s12 m10 offset-m1"> 

<h1 class="error">404</h1>
<p class="error_text"><span>Страница не найдена. В ленте есть много интересного!</span></p>
</div>
</div>
    </div>
    </div>
    </div>
<div class="CommonPageWrapper {$controller->MC}PageWrapper Infopage_{$page->properties->get('css')} {if !($page->properties->get_filtered('system',['Boolean','DefaultFalse']))} CommonInfoPage {/if}">    
    {$page->render_content()}
</div>

