<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

final class MediaContextInfo {

    const DEBUG_TABLES = true;
    const CACHE_BEACON_NAME = 'media_context';
    const TABLE_PX = "imagefly__"; 

    protected $default_context_max_width = 2600;
    protected $default_context_min_width = 300;
    protected $default_context_max_height = 2600;
    protected $default_context_min_height = 300;
    protected $default_context_allow_caching = true;
    protected $default_context_allow_mimes = null;
    protected $default_context_background = 'rgba(255,255,255,0)';
    protected $version = null;
    protected $contexts = [];
    protected $mimes = [
        'image/png' => true,
        'image/jpeg' => true,
        'image/gif' => true,
    ];

    /** @var MediaContextInfo */
    protected static $instance;
    private $prefix = "imagefly__";

    protected function debug__tables() {
        $table_manager = \DB\TableManager::F(ImageInfoManager::F()->get_adapter());
        if ( !$table_manager->exists("{$this->prefix}media_context")) {
            $query = "CREATE TABLE `{$this->prefix}media_context` ( 
                context VARCHAR(100) NOT NULL,
                max_width DOUBLE NULL DEFAULT NULL,
                max_height DOUBLE NULL DEFAULT NULL,
                min_width DOUBLE NULL DEFAULT NULL,
                min_height DOUBLE NULL DEFAULT NULL,
                allow_caching INT(1)  NOT NULL DEFAULT 1,
                background VARCHAR(100) NULL DEFAULT NULL,                
                allow_mimes BLOB,                             
                PRIMARY KEY (context)
                );                
                ";
            ImageInfoManager::F()->get_adapter()->exec($query);
            $q2 = "INSERT  INTO `{$this->prefix}media_context`(context,max_width,max_height,min_width,min_height,allow_caching,background,allow_mimes)
                    VALUES
                    ('product_group',2600,2600,300,300,1,null,'" . serialize([]) . "'),
                    ('product',2600,2600,300,300,1,null,'" . serialize([]) . "'),
                    ('common_gallery',2600,2600,250,250,1,null,'" . serialize([]) . "'),
                    ('infopage_gallery',2600,2600,250,250,1,null,'" . serialize([]) . "'),
                    ('_color',800,800,50,50,1,null,'" . serialize([]) . "')
                    ON DUPLICATE KEY UPDATE allow_mimes=VALUES(allow_mimes);    
                        ;
                    ";
            ImageInfoManager::F()->get_adapter()->exec($q2);
        }
    }

    protected function dump_contexts() {
        $const_rows = [];
        $data_rows = [];
        foreach ($this->contexts as $key => $value) {
            $ukey = mb_strtoupper($key, 'UTF-8');
            $const_rows[] = "const {$ukey} = \"{$key}\";";
            $mci_array = ["IMediaContext::{$ukey} => [",];
            foreach ($value as $key => $value) {
                $mci_array[] = "\"{$key}\"=>" . var_export($value, true) . ",";
            }
            $mci_array[] = "],";
            $data_rows[] = implode("\n", $mci_array);
        }
        $intf = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "IMediaContext_tpl.php");
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "IMediaContext.php", str_ireplace("/**---CONSTS---*/", implode("\n", $const_rows), $intf), LOCK_EX);
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "context_dump.php", "<?php\n\n return [\n" . implode("\n", $data_rows) . "\n];");
    }

    protected function __construct() {
        static::$instance = $this;
        $this->prefix = static::TABLE_PX;
        $this->version = static::get_file_version();
        if (static::DEBUG_TABLES) {
            $this->debug__tables();
        }
        $this->load();
    }

    protected function load() {
        
        $query = "SELECT * FROM `{$this->prefix}media_context`;";
        $raw_rows = ImageInfoManager::F()->get_adapter()->queryAll($query);
        $this->contexts = [];
        foreach ($raw_rows as $row) {
            $key = \Helpers\Helpers::NEString($row['context'], null);
            if ($key) {
                $this->contexts[$key] = $row;
                $this->contexts[$key]['allow_mimes'] = unserialize($this->contexts[$key]['allow_mimes']);
            }
        }
        $this->set_cache();
        if (static::DEBUG_TABLES) {
            $this->dump_contexts();
        }
    }
    
    public function list_contexts(){
        return array_values($this->contexts);
    }

    protected function set_cache() {
        $cache = \Cache\FileCache::F();
        $cache->put(__CLASS__, $this, 0, \Cache\FileBeaconDependency::F(static::CACHE_BEACON_NAME));
    }

    protected static function get_file_version() {
        return md5(__FILE__ . filemtime(__FILE__));
    }

    public static function register_media_context(string $context, int $max_width = null, int $max_height = null, int $min_width = null, int $min_height = null, bool $allow_caching = true, array $allow_mimes = [], string $background = null) {
        $prefix = static::TABLE_PX;
        $data_raw = compact('context', 'max_width', 'max_height', 'min_width', 'min_height', 'allow_caching', 'allow_mimes', 'background');
        $datamap = \DataMap\CommonDataMap::F()->rebind($data_raw);
        if ($datamap->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull'])) {
            $query = "INSERT  INTO `{$prefix}media_context`(context,max_width,max_height,min_width,min_height,allow_caching,background,allow_mimes)
                VALUES(:Pcontext,:Pmax_width,:Pmax_height,:Pmin_width,:Pmin_height,:Pcache,:Pback,:Pmimes)
                ON DUPLICATE KEY UPDATE context=VALUES(context),
                max_width=VALUES(max_width),max_height=VALUES(max_height),min_width=VALUES(min_width),
                min_height=VALUES(min_height),allow_caching=VALUES(allow_caching),background=VALUES(background),allow_mimes=VALUES(allow_mimes);
                ";
            $params = [
                ":Pcontext" => $datamap->get_filtered('context', ['Strip', 'Trim', 'NEString', 'DefaultNull']),
                ":Pmax_width" => $datamap->get_filtered('max_width', ['IntMore0', 'DefaultNull']),
                ":Pmax_height" => $datamap->get_filtered('max_height', ['IntMore0', 'DefaultNull']),
                ":Pmin_width" => $datamap->get_filtered('min_width', ['IntMore0', 'DefaultNull']),
                ":Pmin_height" => $datamap->get_filtered('min_height', ['IntMore0', 'DefaultNull']),
                ":Pcache" => $datamap->get_filtered('allow_caching', ['Boolean', 'DefaultTrue', 'SQLBool']),
                ":Pback" => $datamap->get_filtered('background', ['Strip', 'Trim', 'NEString', 'DefaultNull']),
                ":Pmimes" => serialize($datamap->get_filtered('allow_mimes', ['ArrayOfNEString', 'NEArray', 'DefaultEmptyArray'])),
            ];
//            $query_2 = "UPDATE `media_context` SET max_width=:Pmax_width,max_height=:Pmax_height,
//                        min_width=:Pmin_width,min_height=:Pmin_height,allow_caching=:Pcache,background=:Pback,
//                        allow_mimes=:Pmimes  WHERE context=:Pcontext;";
            ImageInfoManager::F()->get_adapter()->exec($query,$params);
            //ImageInfoManager::F()->get_pdo()->prepare($query_2)->execute($params);
        }
        if (static::$instance) {
            static::$instance->load();
        } else {
            \Cache\FileBeaconDependency::F(static::CACHE_BEACON_NAME)->reset_dependency_beacons();
        }
    }

    public function remove_media_context(string $context) {
        $context = \Helpers\Helpers::NEString($context, null);
        if ($context) {
            if ($this->context_exists($context)) {
                ImageFly::F()->clear_media_context($context);
                $query = "DELETE FROM `{$this->prefix}media_context` WHERE context=:P";
                ImageInfoManager::F()->get_adapter()->exec($query,[":P" => $context]);
                $this->load();
            }
        }
    }

    /**
     * 
     * @return \ImageFly\MediaContextInfo
     */
    public static function F(): MediaContextInfo {
        return static::$instance ? static::$instance : static::factory(); 
    }

    protected static function factory(): MediaContextInfo {
        $cache = \Cache\FileCache::F();
        $value = $cache->get(__CLASS__); /* @var $value MediaContextInfo */
        $class = self::class;
        if ($value && is_object($value) && ($value instanceof $class) && ($value->version === static::get_file_version())) {
            static::$instance = $value;
            return $value;
        }
        return new static();
    }

    public function context_exists(string $context): bool {
        return array_key_exists($context, $this->contexts) && is_array($this->contexts[$context]);
    }

    public function get_context_data_map(string $context): \DataMap\IDataMap {
        $this->context_exists($context) ? false : ImageFlyError::RF("unknown media context `%s`", $context);
        $ra = $this->contexts[$context];
        $media_data = \DataMap\CommonDataMap::F()->rebind($ra);
        return $media_data;
    }

    public function get_context_max_width(string $context): int {
        $rv = $this->get_context_data_map($context)->get_filtered('max_width', ['IntMore0', 'DefaultNull']);
        return $rv === null ? $this->default_context_max_width : $rv;
    }

    public function get_context_min_width(string $context): int {
        $rv = $this->get_context_data_map($context)->get_filtered('min_width', ['IntMore0', 'DefaultNull']);
        return $rv === null ? $this->default_context_min_width : $rv;
    }

    public function get_context_max_height(string $context): int {
        $rv = $this->get_context_data_map($context)->get_filtered('max_height', ['IntMore0', 'DefaultNull']);
        return $rv === null ? $this->default_context_max_height : $rv;
    }

    public function get_context_min_height(string $context): int {
        $rv = $this->get_context_data_map($context)->get_filtered('min_height', ['IntMore0', 'DefaultNull']);
        return $rv === null ? $this->default_context_min_height : $rv;
    }

    public function get_context_allow_caching(string $context): bool {
        return $this->get_context_data_map($context)->get_filtered('allow_caching', ['Boolean', ($this->default_context_allow_caching ? 'DefaultTrue' : 'DefaultFalse')]);
    }

    public function get_context_allow_mimes(string $context): array {
        $rv = $this->get_context_data_map($context)->get_filtered('allow_mimes', [ 'NEArray', 'DefaultNull']);        
        $rv = $rv ? $rv : $this->default_context_allow_mimes;
        return is_array($rv) ? $rv : [];
    }

    public function can_process_mime(string $context, string $mime): bool {
        
        if (array_key_exists($mime, $this->mimes)) {
            $cm = $this->get_context_allow_mimes($context);
            if (count($cm)) {
                return array_key_exists($mime, $cm);
            } else {
                return true;
            }
        }
        return false;
    }

    public function get_context_default_background(string $context): string {
        $rv = $this->get_context_data_map($context)->get_filtered('background', ['Strip', 'Trim', 'NEString', 'DefaultNull']);
        $rv = $rv ? $rv : $this->default_context_background;
        return $rv;
    }

}
