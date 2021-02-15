<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "www" . DIRECTORY_SEPARATOR . "__bootstrap.php";

class parser {

    const BASE = "http://larro.ironstar.pw";

    protected $visited_urls;
    protected $urls_to_visit;
    protected $visited_urls_count = 0;
    protected $article_urls;
    protected $removes = [
        "http://larro.ru",
        "https://larro.ru",
        "http://www.larro.ru",
        "https://www.larro.ru",
        "http://larro.ironstar.pw",
        "https://larro.ironstar.pw",
        "http://www.larro.ironstar.pw",
        "https://www.larro.ironstar.pw",
    ];

    protected function __construct() {
        $this->urls_to_visit = [static::BASE];
        $this->visited_urls = [];
        $this->article_urls = [];
    }

    public function run() {
        echo "\n START \n";
        while (count($this->urls_to_visit)) {
            $url = $this->urls_to_visit[0];
            $this->urls_to_visit = array_slice($this->urls_to_visit, 1);
            $this->run_url($url);
        }
        
        
        file_put_contents(__DIR__.DIRECTORY_SEPARATOR."result.json", json_encode($this->article_urls));
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

    protected function clean_url($url) {
        $url = \Helpers\Helpers::NEString($url, '');
        foreach ($this->removes as $base) {
            $preg = "/^" . preg_quote($base, "/") . "/i";
            $url = preg_replace($preg, "", $url);
        }
        $aurl = explode("?", $url);
        $url = trim($aurl[0], "\\/");
        echo "appr url:{$url}\n";
        return $url;
    }

    protected function run_url($url) {
        $url = rtrim($url, "\\/");
        $page_content = $this->get_page_content($url);
        if ($page_content) {
            $page_doc = \PHPQuery\PhpQ::D($page_content);

            $this->parse_page($page_doc, $this->clean_url($url));
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

    /**
     * 
     * @param \phpQueryObject|\QueryTemplatesSource|\QueryTemplatesParse|\QueryTemplatesSourceQuery $doc
     */
    protected function parse_page($doc, $url) {
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
                    $this->article_urls[$url] = $article;
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
