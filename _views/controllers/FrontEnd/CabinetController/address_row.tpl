<tr data-id="{$item.uid}">
    <td class="{$controller->MC}addresslistcellpp">{$acounter}</td>
    <td class="{$controller->MC}addresslistcelllabel">{$item.label}</td>
    <td class="{$controller->MC}addresslistcelladdress">{$item.address}</td>                    
    <td class="{$controller->MC}addresslistcellcontrol">
        <div class="{$controller->MC}addresslistremovebth" data-id="{$item.uid}">
            <svg ><use xlink:href="#global_cross" /></svg>
        </div>
    </td>
</tr>