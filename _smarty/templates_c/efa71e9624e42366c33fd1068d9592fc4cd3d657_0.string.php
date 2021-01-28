<?php
/* Smarty version 3.1.33, created on 2020-10-22 20:02:36
  from 'efa71e9624e42366c33fd1068d9592fc4cd3d657' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5f91bb2c7a6479_14397150',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5f91bb2c7a6479_14397150 (Smarty_Internal_Template $_smarty_tpl) {
?><Script>
  $(document).ready(function(){
    const ser = [2506,2422,2385,2379,2370,2358,2320,2307,2297,2286,2283,2264,2249,2246,2239,2236,2232,2219,2203,2191,2179,2120,2097,2086,2074,2067,2047,2030,2027,2022,1996,1986,1956,1927,1904,1881,1870,1860,1839,1827,1819,1813,1808,1789,1768,1758,1748,1731,1720,1707,1623,1611,1599,1578,1507,1499,1487,1444,1439,1396,1369,1359,1356,1352,1340,1329,1315,1312,1300,1288,1285,1275,1262,1239,1225,1215,1181,1126,1123,1075,1067,1059,1047,1040,1010,998,993,941,936,926,917,905,901,896,886,874,866,857,839,823,818,806,788,787,772,765,757,754,745,728,707,694,686,678,668,591,587,581,572,547,543,526,506,498,484,464,440,410,396,387,383,364];
    $("#btna").click(function(){
      const random = Math.floor(Math.random() * ser.length);
      $("#rando_chill").text(ser[random]);
      console.log('"https://chillvision.ru/Public/API?action=soap&id='+ser[random]+'"');
      var a = 'https://chillvision.ru/Public/API?action=soap&id='+ser[random];
      $.getJSON( a, {

      })
        .done(function( data ) {
        var n = data.soap.name;
        var l = data.soap.default_poster;
        console.log(l);
        $("#rando_chill").empty();
        $("#rando_chill").append("<h1>"+n+"</h1>");
        $("#rando_chill").append(l);

      });
    });
  });
  <?php echo '</script'; ?>
>
<div id="rando_block">
  <div id="btna">Нажми</div>
  <div id="rando_chill"></div>
</div><?php }
}
