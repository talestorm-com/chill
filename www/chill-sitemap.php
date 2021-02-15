<?php
    $id = 'default';
    $server = 'localhost';
    $db = 'chill';
    $user = 'chill';
    $password = 'meeneF9shi9aid0pheis1Ootailongoojaequ6Eez6mei4eedo';
    $charset = 'utf8';

    $dsn = "mysql:host=$server;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    function getContent() {
        global $xml;
        $content = [];
        
        foreach ($xml as $k => $v) {
            $loc = strtolower($v->loc);
            if (strrpos($loc, '/soap/')) {
                $record = [];
                $record['id'] = explode('/soap/', $loc)[1];
                $record['lastmod'] = (string)$v->lastmod;
                $content[] = $record;
            }
        }
        return $content;
    }
    function addURltoXML($loc_data, $lastmod_data, $changefreq_data) {
        global $xml;
        $url = $xml->addChild('url');
        $loc = $url->addChild('loc', $loc_data);
        $lastmod = $url->addChild('lastmod', $lastmod_data);
        $changefreq = $url->addChild('changefreq', $changefreq_data);
    }
    function printXML() {
        global $xml;
        header('Content-type: text/xml');
        echo $xml->asXML();
        die();
    }
    function dbGetEmojiById($id) {
        global $pdo;

        $stmt = $pdo->prepare('SELECT id, emoji FROM media__content WHERE id = :id');
        $stmt->execute(array('id' => $id));
        while ($row = $stmt->fetch())
        {   
            return $row['emoji'];
        }
    }
    function dbGetGenreById($id) {
        global $pdo;

        $stmt = $pdo->prepare('SELECT media_id, genre_id FROM media__content__genre_list WHERE media_id = :media_id');
        $stmt->execute(array('media_id' => $id));
        while ($row = $stmt->fetch())
        {   
            return $row['genre_id'];
        }
    }


    $xml = simplexml_load_file('chill-sitemap.xml');
    $content = getContent();

    try {
        $pdo = new PDO($dsn, $user, $password, $opt);
    } catch (PDOException $e) {
        printXML();
    }

    $emojies = [];
    $genres = [];

    foreach ($content as $k => $v) {
        $emoji = dbGetEmojiById($v['id']);
        if ($emoji) {
            if (array_key_exists($emoji, $emojies)) {
                if (strtotime($emojies[$emoji]) < strtotime($v['lastmod'])) $emojies[$emoji] = $v['lastmod']; //этой строкой мы находим самое большое время у этой эмоции
            } else {
                $emojies[$emoji] = $v['lastmod'];
            }
        }

        $genre = dbGetGenreById($v['id']);
        if ($genre) {
            if (array_key_exists($genre, $genres)) {
                if (strtotime($genres[$genre]) < strtotime($v['lastmod'])) $genres[$genre] = $v['lastmod']; //этой строкой мы находим самое большое время у этого жанра
            } else {
                $genres[$genre] = $v['lastmod'];
            }
        }
    }


    foreach ($emojies as $emoji => $lastmod) {
        $loc = 'https://chillvision.ru/search/by_emoji/'.$emoji;
        addURltoXML($loc, $lastmod, 'weekly');
    }
    foreach ($genres as $genre => $lastmod) {
        $loc = 'https://chillvision.ru/search/by_genre/'.$genre;
        addURltoXML($loc, $lastmod, 'weekly');
    }

    printXML();
?>