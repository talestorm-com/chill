{if $controller->auth->is_authentificated()}
    {if $controller->auth->is(\Auth\Roles\RoleTrainer::class)}
        перейите в свой <a href="/Cabinet">кабинет</a> чтобы настроить расписание и просмотреть заявки
    {else}
        {content_block alias='main_page_form_client'}
    {/if}
{else}
    <div class="main_page_form_not_auth">
        Вам нужно <a href="#" class="login_trigger">войти</a> или <a href="#" class="register_trigger">зарегистрироваться</a>
    </div>
    <script>
        {literal}
            (function () {
                window.Eve = window.Eve || {};
                window.Eve.EFO = window.Eve.EFO || {};
                window.Eve.EFO.Ready = window.Eve.EFO.Ready || [];
                window.Eve.EFO.Ready.push(function () {
                    window.Eve.EFO.Events.GEM().on('SYS_LOGIN_SUCCESS', window, function () {
                        window.location.reload(true);
                    })
                });
            })();
        {/literal}
    </script>
{/if}