<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$input = $_GET['q'];
$input12 = mb_convert_encoding($input, 'CP866', 'utf-8');
$q=[];
for($i = 0;$i<strlen($input12);$i++){
    $q[]= ord($input12[$i]);
}
var_dump(mb_list_encodings());
echo implode(',', $q);
echo "\n\n\n\n\n\n\n\n";
$jso = json_decode('{"origfieldname":["picture","deskname","custom html","category","assigned to","floorname","seats","phone","window","computer","monitor","printer","ethernet jack","projector","coffee","whiteboard","bookable","закрепленное","customclickurl","-template-","function1","function2","function3","function4","function5","сотрудник 1","марка 1","автомобиль 1","сотрудник 2","марка 2","автомобиль 2","сотрудник 3","марка 3","автомобиль 3","номер 2","сотрудник 4","марка 4","номер 4","сотрудник 5","марка 5","номер 5","номер 1","сотрудник1","сотрудник 10"],"newfieldname":["picture","deskname","custom html","category","assigned to","floorname","seats","Phone","Window","Computer","Monitor","Printer","Ethernet Jack","Projector","Coffee","Whiteboard","bookable","Закрепленное","CustomClickURL","-template-","Function1","Function2","Function3","Function4","Function5","Сотрудник 1","Марка 1","Автомобиль 1","Сотрудник 2","Марка 2","Автомобиль 2","Сотрудник 3","Марка 3","Автомобиль 3","Номер 2","Сотрудник 4","Марка 4","Номер 4","Сотрудник 5","Марка 5","Номер 5","Номер 1","Сотрудник1","qwerty"],"fieldtype":["image","text","customhtml","list","text","text","number","check","check","check","check","check","check","check","check","check","check","check","text","-template-","text","text","text","text","text","text","text","text","text","text","text","text","text","text","text","text","text","text","text","text","text","text","text","text"],"defaultvalue":["","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","",""],"alias":["","Место","","","","Этаж","","","","","","","","","","","Можно бронировать","","","","Функция 1","Функция 2","Функция 3","Функция 4","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5","Функция 5"],"listitems":["","","<div class=\"CustomHTML\">[*deskname]</div>","Desk\nOffice\nMeeting Room\nParking Spot","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","","",""],"required":[false,true,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],"panelclass":[false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],"editprofile":[false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],"adminonly":[false,false,true,true,true,false,true,true,true,true,true,true,true,true,true,true,false,false,true,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],"locked":[false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false,false],"hidecategories":["","","-blank-,parking spot","","-blank-,parking spot","","parking,-blank-,parking spot","parking,-blank-,parking spot","parking,-blank-,parking spot","parking,-blank-,parking spot","parking,-blank-,parking spot","parking,-blank-,parking spot","parking,-blank-,parking spot","-blank-,desk,office,parking,parking spot","-blank-,desk,office,parking,parking spot","-blank-,desk,parking,parking spot","parking spot,-blank-","parking spot","-blank-,parking spot","","","","","","","","","","","","","","","","","","","","","","","","",""]}',true);
echo "<pre>";
var_dump($jso);
echo "</pre>";
echo "<pre>";
echo json_encode($jso);
echo "</pre>";
echo urlencode(json_encode($jso));

