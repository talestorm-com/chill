{if $controller->auth_is_client()}
<div class="{$controller->MC}MenuContentWrapper">
    <a href="/Cabinet/MyPlan">Запланировано</a>
    <a href="/Cabinet/MyHistory">История</a>    
    <a href="/Cabinet">Профиль</a>
</div>
{else if $controller->auth_is_trainer()}
    <div class="{$controller->MC}MenuContentWrapper">
    <a href="/Cabinet/TrainerPlan">Запланировано</a>
    <a href="/Cabinet/TrainerHistory">История</a>    
    <a href="/Cabinet/TrainerCalendar">Расписание</a>   
    <a href="/Cabinet/TrainerPlaces">Спортзалы</a>   
    <a href="/Cabinet">Профиль</a>
</div>
{/if}