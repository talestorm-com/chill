<?php
const SERVER   = 'mysql';
const DB       = 'chill';
const USER     = 'root';
const PASSWORD = '123';
const CHARSET  = 'utf8';

const DSN             = 'mysql:host=' . SERVER . ';dbname=' . DB . ';charset=' . CHARSET;
const OPT             = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
const AGE_RESTRICTION_GOOGLE = [
    0 => 'yes',
    1 => 'yes',
    3 => 'no',
    4 => 'no',
    5 => 'no',
    6 => 'yes',
];
const AGE_RESTRICTION_YANDEX = [
    0 => 'no',
    1 => 'no',
    3 => 'yes',
    4 => 'yes',
    5 => 'yes',
    6 => 'no',
];
function createSitemap()
{
    try {
        $pdo = new PDO(DSN, USER, PASSWORD, OPT);
    } catch (PDOException $e) {
        return;
    }
    $domGoogle   = createDomGoogle();
    $domYandex   = createDomYandex();
    $contentList = getContent($pdo);

    foreach ($contentList as $content) {
        if (empty($content['cdn_url'])) {
            continue;
        }
        $urlsetGoogle = createGoogleVideoMap($content, $domGoogle);
        $urlsetYandex = createYandexVideoMap($content, $domYandex);
        $domYandex->appendChild($urlsetYandex);
    }
    $domGoogle = $domGoogle['dom'];

    $domGoogle->appendChild($urlsetGoogle);
    $domGoogle->save(__DIR__ . '/google-video-sitemap.xml');

    $domYandex->save(__DIR__ . '/yandex-video-sitemap.xml');
}

function getContent($pdo)
{
    $stmt = $pdo->prepare('SELECT * FROM media__content__season mcs, media__content__season__strings__lang_ru mssl,
        media__lent__mode mm, media__lent__video mv,media__content mc
        where mcs.id = mssl.id AND mcs.id = mm.id AND mcs.id = mv.id AND mcs.id = mc.id ORDER BY mcs.id ASC');
    $stmt->execute();
    $seasonList = $stmt->fetchAll();;
    return $seasonList;
}

function createDomGoogle()
{
    $dom               = new DOMDocument('1.0', 'utf-8');
    $dom->formatOutput = true;
    $urlset            = $dom->createElement('urlset');
    $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
    $urlset->setAttribute('xmlns:video', 'http://www.google.com/schemas/sitemap-video/1.1');
    return [
        'dom'    => $dom,
        'urlset' => $urlset,
    ];
}

function createDomYandex()
{
    $domYandex               = new DOMDocument('1.0', 'utf-8');
    $domYandex->formatOutput = true;
    return $domYandex;
}

function createGoogleVideoMap($content, $domGoogle)
{
    $dom    = $domGoogle['dom'];
    $urlset = $domGoogle['urlset'];
    $url    = $dom->createElement('url');
    $loc    = $dom->createElement('loc');
    $text   = $dom->createTextNode(
        htmlentities('https://' . 'chillvision.ru' . '/Soap/' . $content['id'] . '.html', ENT_QUOTES)
    );
    $loc->appendChild($text);

    $video                = $dom->createElement('video:video');
    $videoThumbnail       = $dom->createElement('video:thumbnail_loc');
    $videoTitle           = $dom->createElement('video:title');
    $videoDescription     = $dom->createElement('video:description');
    $videoContentLoc      = $dom->createElement('video:content_loc');
    $videoPublicationDate = $dom->createElement('video:publication_date');
    $videoFamilyFriendly  = $dom->createElement('video:family_friendly');
    $videoLive            = $dom->createElement('video:live');

    $thumbnailLoc = $dom->createTextNode(
        htmlentities('https://' . 'chillvision.ru' . '/media/lent_poster/' . $content['id'] . '/' .
            $content['lent_image_name'] . '.SW_400H_520CF_1' . '.jpg', ENT_QUOTES)
    );

    $videoThumbnail->appendChild($thumbnailLoc);
    $videoTitle->appendChild($dom->createTextNode($content['common_name']));
    $videoDescription->appendChild($dom->createTextNode(trim(strip_tags($content['info']))));
    $videoContentLoc->appendChild($dom->createTextNode(htmlentities('https://' . $content['cdn_url'])));
    $videoPublicationDate->appendChild($dom->createTextNode($content['released']));
    $videoFamilyFriendly->appendChild($dom->createTextNode(AGE_RESTRICTION_GOOGLE[$content['age_restriction']]));
    $videoLive->appendChild($dom->createTextNode('no'));

    $video->appendChild($videoThumbnail);
    $video->appendChild($videoTitle);
    $video->appendChild($videoDescription);
    $video->appendChild($videoContentLoc);
    $video->appendChild($videoPublicationDate);
    $video->appendChild($videoFamilyFriendly);
    $video->appendChild($videoLive);

    $url->appendChild($loc);
    $url->appendChild($video);

    $urlset->appendChild($url);
    return $urlset;
}

function createYandexVideoMap($content, $dom)
{
    $ovsVideo = $dom->createElement('ovs:video');
    $ovsVideo->setAttribute('xmlns:ovs', 'http://webmaster.yandex.ru/schemas/video');
    $ovsVideo->setAttribute('xmlns:xsi:video', 'http://www.google.com/schemas/sitemap-video/1.1');
    $ovsVideo->setAttribute('xsi:schemaLocation', 'http://webmaster.yandex.ru/schemas/video');

    $url          = $dom->createTextNode(
        htmlentities('https://' . 'chillvision.ru' . '/Soap/' . $content['id'] . '.html', ENT_QUOTES)
    );
    $thumbnailLoc = $dom->createTextNode(
        htmlentities('https://' . 'chillvision.ru' . '/media/lent_poster/' . $content['id'] . '/' .
            $content['lent_image_name'] . '.SW_400H_520CF_1' . '.jpg', ENT_QUOTES)
    );

    $ovsId          = $dom->createElement('ovs:content_id');
    $ovsUrl         = $dom->createElement('ovs:url');
    $ovsThumbnail   = $dom->createElement('ovs:thumbnail');
    $ovsTitle       = $dom->createElement('ovs:title');
    $ovsDescription = $dom->createElement('ovs:description');
    $ovsUploadDate  = $dom->createElement('ovs:upload_date');
    $ovsAdult       = $dom->createElement('ovs:adult');
    $ovsEmbedUrl    = $dom->createElement('ovs:embed_url');

    $ovsId->appendChild($dom->createTextNode($content['id']));
    $ovsUrl->appendChild($url);
    $ovsThumbnail->appendChild($thumbnailLoc);
    $ovsTitle->appendChild($dom->createTextNode($content['common_name']));
    $ovsDescription->appendChild($dom->createTextNode(trim(strip_tags($content['info']))));
    $ovsUploadDate->appendChild($dom->createTextNode($content['released']));
    $ovsAdult->appendChild($dom->createTextNode(AGE_RESTRICTION_YANDEX[$content['age_restriction']]));
    $ovsEmbedUrl->appendChild($dom->createTextNode(htmlentities('https://' . $content['cdn_url'])));

    $ovsVideo->appendChild($ovsId);
    $ovsVideo->appendChild($ovsUrl);
    $ovsVideo->appendChild($ovsThumbnail);
    $ovsVideo->appendChild($ovsTitle);
    $ovsVideo->appendChild($ovsDescription);
    $ovsVideo->appendChild($ovsUploadDate);
    $ovsVideo->appendChild($ovsAdult);
    $ovsVideo->appendChild($ovsEmbedUrl);

    return $ovsVideo;
}

createSitemap();
?>