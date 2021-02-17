<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "www" . DIRECTORY_SEPARATOR . "__bootstrap.php";

class parser {

    const BASE = "http://www.larro.ru";

    protected $visited_urls;
    protected $urls_to_visit;
    protected $visited_urls_count = 0;
    protected $downloaded_images;
    protected $removes = [
        "http://larro.ru",
        "https://larro.ru",
        "http://www.larro.ru",
        "https://www.larro.ru",
    ];

    protected function __construct() {
        $this->urls_to_visit = [static::BASE];
        $this->visited_urls = [];
        $this->downloaded_images = [];
    }

    public function run() {
        echo "\n START \n";
        while (count($this->urls_to_visit)) {
            $url = $this->urls_to_visit[0];
            $this->urls_to_visit = array_slice($this->urls_to_visit, 1);
            $this->run_url($url);
        }
        echo "\nALL DONE!\n";
    }

    protected function get_page_content(string $url) {
        echo "querying url:{$url}...\n";
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_HTTPGET => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $result = curl_exec($curl);
        $result_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (false === $result_code) {
            $result_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        }
        $this->visited_urls[$url] = $url;
        curl_close($curl);
        return intval($result_code) === 200 ? $result : null;
    }

    protected function run_url($url) {
        $url = rtrim($url, "\\/");
        $page_content = $this->get_page_content($url);
        if ($page_content) {
            $page_doc = \PHPQuery\PhpQ::D($page_content);

            $this->parse_page($page_doc);
            $this->collect_page_links($page_doc);
        }
        $this->visited_urls_count++;
        echo "processed {$this->visited_urls_count} urls remain: " . count($this->urls_to_visit) . "\n";
    }

    protected function get_images_dir() {
        $path = Config\Config::F()->LOCAL_TMP_PATH;
        return $path . "image_parser" . DIRECTORY_SEPARATOR;
    }

    protected function get_colors_dir() {
        $path = Config\Config::F()->LOCAL_TMP_PATH;
        return $path . "image_parser" . DIRECTORY_SEPARATOR . "colors" . DIRECTORY_SEPARATOR;
    }

    protected function download_image($base_name, $url, $article) {
        $url = static::BASE . "/" . trim($url, "\\/");
        array_key_exists($article, $this->downloaded_images) ? 0 : $this->downloaded_images[$article] = [];
        echo "found_image:{$url}. downloading...\n";
        if (array_key_exists($url, $this->downloaded_images[$article])) {
            echo "download aborted: duplicate image\n";
            return false;
        }
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_HTTPGET => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $result = curl_exec($curl);
        $result_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (false === $result_code) {
            $result_code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        }
        if ($result_code === 200) {
            echo "download success. converting...\n";
            try {
                $base_dir = $this->get_images_dir();
                $c = 0;
                $fn = "{$base_dir}{$base_name}.{$c}.png";
                while (file_exists($fn)) {
                    $c++;
                    $fn = "{$base_dir}{$base_name}.{$c}.png";
                }
                $img = new Imagick();
                $img->readimageblob($result);
                $img->setimageformat("PNG32");
                $img->writeimage($fn);
                echo "image stored as {$base_name}.{$c}.png\n";
            } catch (\Exception $e) {
                echo "image process error:{$e->getMessage()}\n";
                return false;
            }
        }
        if (intval($result_code) === 200) {
            array_key_exists($article, $this->downloaded_images) ? 0 : $this->downloaded_images[$article] = [];
            $this->downloaded_images[$article][$url] = $url;
        }
        return intval($result_code) === 200 ? true : false;
    }

    

    /**
     * 
     * @param \phpQueryObject|\QueryTemplatesSource|\QueryTemplatesParse|\QueryTemplatesSourceQuery $doc
     */
    protected function parse_page($doc) {
        $container = $doc->find("#block_shop_product");
        if ($container) {
            if (count($container) === 1) {
                echo "product page. parsing....\n";
                $info = $container->find('.info');
                $html = strip_tags(preg_replace("/</", " <", pq($info)->html()));
                $m = [];
                if (preg_match("/Артикул:\s{0,}(?P<art>\S{1,})/", $html, $m)) {
                    $article = trim($m["art"]);
                    echo "found article:{$article}\n";
                    $img_tag = $container->find('.list_photos>img');
                    if ($img_tag) {
                        foreach ($img_tag as $image) {
                            $src = Helpers\Helpers::NEString(trim(pq($image)->attr('data-src'), "\\/"));
                            if ($src) {
                                $this->download_image(md5($article), $src, $article);
                            }
                        }
                    }                    
                }
            }
        }
    }

    /**
     * 
     * @param \phpQueryObject|\QueryTemplatesSource|\QueryTemplatesParse|\QueryTemplatesSourceQuery $doc
     */
    protected function collect_page_links($doc) {
        $links = $doc->find("a");
        foreach ($links as $link) {/* @var $link phpQueryObject */
            $href = \Helpers\Helpers::NEString(pq($link)->attr('href'), null);
            if ($href) {
                $ahref = $href;
                foreach ($this->removes as $remove) {
                    $rx = "/^" . preg_quote($remove, "/") . "/i";
                    $ahref = preg_replace($rx, "", $ahref);
                }
                if (!preg_match("/^http/i", $ahref) && !preg_match("/^\/\//i", $ahref)) {
                    $ahref = static::BASE . "/" . trim($ahref, "\\/");
                    if (!array_key_exists($ahref, $this->visited_urls)) {
                        $this->urls_to_visit[] = $ahref;
                        $this->visited_urls[$ahref] = $ahref;
                    }
                }
            }
        }
    }

    public static function F() {
        return new static();
    }

}

parser::F()->run();
