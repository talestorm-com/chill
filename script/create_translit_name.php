<?php
const SERVER   = 'localhost';
const DB       = 'chill';
const USER     = 'chill';
const PASSWORD = 'meeneF9shi9aid0pheis1Ootailongoojaequ6Eez6mei4eedo';
const CHARSET  = 'utf8';

const DSN                    = 'mysql:host=' . SERVER . ';dbname=' . DB . ';charset=' . CHARSET;
const OPT                    = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
const RUS = [
    'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
];
const LAT = [
    'A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya'
];
const SIMBL = [
    ' ',',','.','\'','"','#','«','»',';',':','_','=','+','(','^','%','$','@',')','{','}','[',']','<','>'
];
const SIMBL2 = [
    '!','?'
];
function createTranslitName()
{
    try {
        $pdo = new PDO(DSN, USER, PASSWORD, OPT);
    } catch (PDOException $e) {
        return;
    }
    try {
    seasonGetTranslit($pdo);
    collectionGetTranslit($pdo);
    } catch (\Throwable $e) {
        $pdo->rollback();
        throw $e; // but the error must be handled anyway
    }
}

function getTranslitName($name){
    $translName = mb_strtolower(str_replace(RUS, LAT, $name));
    $translName = (str_replace(SIMBL, '-', $translName));
    $translName = (str_replace(SIMBL2, '', $translName));
    $translName = trim($translName,'-');
    $translName = preg_replace('/(\-){2,}/', '$1', $translName);
    return $translName;
}

function seasonGetTranslit($pdo){
    $stmt = $pdo->prepare('SELECT * FROM media__content__season');
    $stmt->execute();
    $seasonList = $stmt->fetchAll();
        $pdo->beginTransaction();
        foreach ($seasonList as $season){
            $name =  $season['common_name'];
            $id =  $season['id'];
            $translName = getTranslitName($name);
            $stmt = $pdo->prepare("UPDATE `media__content__season` SET `translit_name` = '$translName' WHERE `media__content__season`.`id` = $id;");
            $stmt->execute();
        }
        $pdo->commit();
}

function collectionGetTranslit($pdo){
    $stmt = $pdo->prepare('SELECT * FROM media__content__collection');
    $stmt->execute();
    $seasonList = $stmt->fetchAll();
        $pdo->beginTransaction();
        foreach ($seasonList as $season){
            $name =  $season['common_name'];
            $id =  $season['id'];
            $translName = getTranslitName($name);
            $stmt = $pdo->prepare("UPDATE `media__content__collection` SET `translit_name_collection` = '$translName' WHERE `media__content__collection`.`id` = $id;");
            $stmt->execute();
        }
        $pdo->commit();
    }

createTranslitName();
?>