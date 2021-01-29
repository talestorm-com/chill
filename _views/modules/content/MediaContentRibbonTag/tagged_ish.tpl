{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module.css',0)|void}
{$OUT->add_css('/assets/css/chill/MediaContentRibbon/module_alex.css',0)|void}

<div id="tag_result">
            <div id="main_header">
                <div class="container">
                    <div class="row">
                        <div class="col s12 l10 offset-l1">
                            <div class="row">
                                <div class="col l10 s12">
                                    <h1>{$this->tag_name}</h1>
                                </div>
                                <div class="col s2">
                                    <div class="right-align">
                                        <a href="" class="back_back">Назад</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<div class="tag_list">
<div class="container">
                    <div class="row">
                        <div class="col s12 l10 offset-l1">
                        {$this->gifs|var_dump}
                        </div>
                        </div>
                        </div>
                        </div>

            </div>
    <div class="container">
        <div class="row">
            <div class="col s12 l10 offset-l1">   
                <div class="chill-lenta-content">
                    <div class="chill-lenta-content-inner">                        
                        {$this->tag_id}{$this->tag_name}
                        <pre>
                            {$this->gifs|var_dump}
                        </pre><pre>
                            {$this->news|var_dump}
                        </pre><pre>
                            {$this->soap|var_dump}
                        </pre>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>