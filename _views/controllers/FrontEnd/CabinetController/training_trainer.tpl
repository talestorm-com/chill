<div class="{$controller->MC}_training_info">
    <div class="{$controller->MC}_training_info_inner">
        <div class="{$controller->MC}_training_info_header">тренировка {$training->start_moment->format('d.m.Y в H:i')}</div>
        <div class="{$controller->MC}_training_info_place"><b>Место:</b>{$training->place_name}</div>
        <div class="{$controller->MC}_training_info_trainer"><b>Курсант:</b>{$training->client_name}</div>
        <div class="{$controller->MC}_training_info_state {$controller->MC}_training_info_state{$training_>state}">
            <b>Статус:</b>{if $training->state == 0}Ожидает подтверждения{elseif $training->state==1}Подтверждена{elseif $training->state==2}Отменена{else}Неизвестно{/if}
        </div>        
    </div>
</div>