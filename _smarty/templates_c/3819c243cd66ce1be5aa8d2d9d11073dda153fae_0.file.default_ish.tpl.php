<?php
/* Smarty version 3.1.33, created on 2020-06-28 18:33:25
  from '/var/VHOSTS/site/_views/controllers/FrontEnd/NewsListController/default_ish.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5ef8b8452c67a7_87826010',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3819c243cd66ce1be5aa8d2d9d11073dda153fae' => 
    array (
      0 => '/var/VHOSTS/site/_views/controllers/FrontEnd/NewsListController/default_ish.tpl',
      1 => 1593358305,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5ef8b8452c67a7_87826010 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.TT.php','function'=>'smarty_function_TT',),1=>array('file'=>'/var/VHOSTS/site/lib/vendor/smarty/libs/plugins/function.get_user_auth_status.php','function'=>'smarty_function_get_user_auth_status',),));
?>
<div style="background:white;color:black;padding: 1em">
    Контроллер дает на выход следующие переменные:<br>
    <b>items</b> - массив новостей:
    <pre style="font-family: monospace">
        <?php if (count($_smarty_tpl->tpl_vars['items']->value)) {?>
            <?php echo var_dump($_smarty_tpl->tpl_vars['items']->value[0]);?>
 
        <?php }?>
    </pre>
    Поля новости не отличаются от полей на полной новости, только их меньше<br>.
    и доступ к ним - как к ключам массива, а не полям объекта, тоесть:<br>
    <b>{$items[0].default_poster}</b>: <?php if (count($_smarty_tpl->tpl_vars['items']->value)) {
echo $_smarty_tpl->tpl_vars['items']->value[0]['default_poster'];
}?><br><br><br><br>
    <b>total,page,perpage</b> - данные для построения пагинатора:
    <ul>
        <li>{$total}:<?php echo $_smarty_tpl->tpl_vars['total']->value;?>
</li>
        <li>{$perpage}:<?php echo $_smarty_tpl->tpl_vars['perpage']->value;?>
</li>
        <li>{$page}:<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
</li>
    </ul>
    <br><br><br><br>
    <b>paginator</b> - уже построеный пагинатор, если нужен:
    <pre><?php echo var_dump($_smarty_tpl->tpl_vars['paginator']->value);?>
</pre><br><br>
    Дополнительные параметры:<br>
    Для отладки пагинатора и вообще можно передать GET <b>perpage</b><br>
    Для замены лэйаута можно передать параметр <b>sys_render_layout</b><br>
    Для замены виева можно передать параметр <b>sys_render_template</b><br>
    <br><br><br><br><br>
    Адрес страницы с новостями (поскольку news/xx уже занято) - /newslist или newslist/[page]<br><br>
    пример: <a href="/newslist?perpage=2&sys_render_layout=raw&sys_render_template=json">/newslist?perpage=2&sys_render_layout=raw&sys_render_template=json</a><br><br>
    <a href="/newslist/2?perpage=1">/newslist/2?perpage=1</a>
    <div>
        ПРоверка транслятора:
        <div>russian_language=<?php echo smarty_function_TT(array('l'=>'ru','t'=>'russian_language'),$_smarty_tpl);?>
</div>
        <div>current_language=<?php echo smarty_function_TT(array('t'=>'current_language'),$_smarty_tpl);?>
</div>
        <div>engilish_language=<?php echo smarty_function_TT(array('l'=>'en','t'=>'engilish_language'),$_smarty_tpl);?>
</div>
        ПРоверка транслятора - 2:
        <div>current_language=<?php echo $_smarty_tpl->tpl_vars['T']->value->T('current_language');?>
</div>
        <div>russian_language=<?php echo $_smarty_tpl->tpl_vars['T']->value->T('russian_language','ru');?>
</div>
        <div>engilsh_language=<?php echo $_smarty_tpl->tpl_vars['T']->value->T('english_language','en');?>
</div>
        <div>engilsh_language=<?php echo $_smarty_tpl->tpl_vars['T']->value->T('english_language');?>
</div>
        <div>engilsh_language=<?php echo $_smarty_tpl->tpl_vars['T']->value->T('english_language','ru');?>
</div>
        <div>engilsh_language=<?php echo $_smarty_tpl->tpl_vars['T']->value->T('english_language','es');?>
</div>
    </div>
    <div>
        <?php ob_start();
echo smarty_function_get_user_auth_status(array(),$_smarty_tpl);
$_prefixVariable1 = ob_get_clean();
if ($_prefixVariable1) {?>authorized<?php } else { ?>not authorized<?php }?>
    </div>
</div>
    
    <?php }
}
