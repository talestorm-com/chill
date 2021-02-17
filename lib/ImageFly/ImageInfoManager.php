<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

class ImageInfoManager {

    const DEBUG_CHECK_TABLE = false;
    const DEFAULT_PRESET_MARKER = "a9a95106d9b2469ebe";

    /** @var ImageInfoManager */
    protected static $instance;

    /** @var \DB\IDBAdapter */
    protected $db;
    private $prefix = "imagefly__";

    protected function __construct() {
        $this->db = \DB\DB::F();
        if (static::DEBUG_CHECK_TABLE) {
            $table_manager = \DB\TableManager::F($this->db);
            if (!$table_manager->exists("{$this->prefix}images")) {
                $query = "CREATE TABLE `{$this->prefix}images` ( 
                context VARCHAR(100) NOT NULL,
                owner_id VARCHAR(100) NOT NULL,
                image VARCHAR(64) NOT NULL,
                sort int(11) NOT NULL DEFAULT 0,
                crop_start_x DOUBLE NULL DEFAULT NULL,
                crop_start_y DOUBLE NULL DEFAULT NULL,
                crop_end_x DOUBLE NULL DEFAULT NULL,
                crop_end_y DOUBLE NULL DEFAULT NULL,
                title VARCHAR(1024) NULL DEFAULT NULL,
                doc int(19) NOT NULL,
                PRIMARY KEY (context,owner_id,image)
                );
                CREATE INDEX image_sort ON images (context,owner_id,sort,doc,image);                
                ";
                $this->db->exec($query);
            }
            if (!($table_manager->exists("{$this->prefix}images_props"))) {
                $query = "CREATE TABLE `{$this->prefix}images_props` ( 
                context VARCHAR(100) NOT NULL,
                owner_id VARCHAR(100) NOT NULL,
                image VARCHAR(32) NOT NULL,
                property_name VARCHAR(100) NOT NULL,
                property_value VARCHAR(2048) NULL DEFAULT NULL,
                PRIMARY KEY (context,owner_id,image,property_name),
                FOREIGN KEY(context,owner_id,image) REFERENCES `{$this->prefix}images`(context,owner_id,image) 
                ON DELETE CASCADE ON UPDATE CASCADE
                );                
                ";
                $this->db->exec($query);
            }

            if (!($table_manager->exists("{$this->prefix}colors"))) {
                $query = "CREATE TABLE `{$this->prefix}colors` (                 
                image VARCHAR(48),                
                crop_start_x DOUBLE NULL DEFAULT NULL,
                crop_start_y DOUBLE NULL DEFAULT NULL,
                crop_end_x DOUBLE NULL DEFAULT NULL,
                crop_end_y DOUBLE NULL DEFAULT NULL, 
                doc int(19) NOT NULL,
                PRIMARY KEY (image)
                );     
                CREATE INDEX colors_doc ON colors (doc,image);                
                ";
                $this->db->exec($query);
            }
        }
        static::$instance = $this;
    }

    /**
     * 
     * @return \ImageFly\ImageInfoManager
     */
    public static function F(): ImageInfoManager {
        return static::$instance ? static::$instance : new static();
    }

    public function check_color_image_exists(string $guid): bool {
        $query = "SELECT * FROM `{$this->prefix}colors` where image=:P";
        $row = $this->db->queryRow($query, [":P" => $guid]);
        return $row && is_array($row) ? true : false;
    }

    protected function _get_images_properties(string $context, string $owner_id): array {
        $query = "SELECT image,property_name,property_value FROM `{$this->prefix}images_props` WHERE context=:Pcontext AND owner_id=:Powner_id ;";
        $rows = $this->db->queryAllIndex($query, [":Pcontext" => $context, ":Powner_id" => $owner_id]);
        $rows = is_array($rows) ? $rows : [];
        $result = [];
        foreach ($rows as $row) {
            array_key_exists($row['image'], $result) ? false : $result[$row['image']] = [];
            $result[$row['image']][$row['property_name']] = $row['property_value'];
        }
        return $result;
    }

    protected function _get_image_extended_props(ImageInfo $ii): array {
        $query = "SELECT image,property_name,property_value FROM `{$this->prefix}images_props` WHERE context=:Pcontext AND owner_id=:Powner_id AND image=:Pimage;";
        $rows = $this->db->queryAll($query, [":Pcontext" => $ii->context, ":Powner_id" => $ii->owner_id, ':Pimage' => $ii->image]);
        $rows = is_array($rows) ? $rows : [];
        $result = [];
        foreach ($rows as $row) {
            $result[$row['property_name']] = $row['property_value'];
        }
        return $result;
    }

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @return ImageInfo[]
     */
    public function list_images(string $context, string $owner_id): array {
        $query = "SELECT * FROM `{$this->prefix}images` WHERE context=:Pcontext AND owner_id=:Powner_id ORDER BY sort,doc;";
        $rows = $this->db->queryAll($query, [":Pcontext" => $context, ":Powner_id" => $owner_id]);
        $rows = is_array($rows) ? $rows : [];
        $result = [];
        $all_props = $this->_get_images_properties($context, $owner_id);
        foreach ($rows as $row) {
            $ii = ImageInfo::F($row);
            $ii && $ii->valid ? $result[] = $ii : false;
            $ii->set_extended_properties(array_key_exists($ii->image, $all_props) ? $all_props[$ii->image] : []);
        }
        return $result;
    }

    public function list_image_marshall(string $context, string $owner_id): array {
        $re = $this->list_images($context, $owner_id);
        /* @var $re ImageInfo[] */
        $ro = [];
        foreach ($re as $row) {
            $ro[] = $row->marshall();
        }
        return $ro;
    }

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @param string $image     
     * @param string $preset
     * @return ImageInfo|null
     */
    public function get_image_info(string $context, string $owner_id, string $image, $preset = null) {
        if ($context === "_color") {
            $query = "SELECT * FROM `{$this->prefix}colors` WHERE  image=:Pimage;";
            $row = $this->db->queryRow($query, [":Pimage" => $image]);
            if ($row && is_array($row)) {
                $ii = ColorInfo::F($row);
                return $ii && $ii->valid ? $ii : null;
            }
            return null;
        }
        $query = "SELECT A.*
            ,COALESCE(B.csx,A.crop_start_x) crop_start_x
            ,COALESCE(B.csy,A.crop_start_y) crop_start_y
            ,COALESCE(B.cex,A.crop_end_x) crop_end_x
            ,COALESCE(B.cey,A.crop_end_y) crop_end_y            
            FROM `{$this->prefix}images` A
                LEFT JOIN `{$this->prefix}aspect_preset` B ON(A.context=B.context AND A.owner_id=B.owner_id AND A.image=B.image)
                WHERE A.context=:Pcontext AND A.owner_id=:Powner_id AND A.image=:Pimage AND (B.preset IS NULL OR B.preset=:Ppreset);";
        $row = $this->db->queryRow($query, [":Pcontext" => $context, ":Powner_id" => $owner_id, ":Pimage" => $image, ":Ppreset" => \Helpers\Helpers::NEString($preset, static::DEFAULT_PRESET_MARKER)]);
        if ($row && is_array($row)) {
            $ii = ImageInfo::F($row);
            ($ii && $ii->valid) ? $ii->set_extended_properties($this->_get_image_extended_props($ii)) : 0;
            return $ii && $ii->valid ? $ii : null;
        }
        return null;
    }

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @param string $image     
     * @return ImageAspectDimensionList|null
     */
    public function get_image_info_v2(string $context, string $owner_id, string $image) {
        if ($context === "_color") {
            $query = "SELECT * FROM `{$this->prefix}colors` WHERE  image=:Pimage;";
            $row = $this->db->queryRow($query, [":Pimage" => $image]);
            if ($row && is_array($row)) {
                $ii = ColorInfo::F($row);
                return $ii && $ii->valid ? $ii : null;
            }
            return null;
        }
        return ImageAspectDimensionList::F($context, $owner_id, $image);
    }

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @param string $image
     * @return ImageInfo|null
     */
    public function get_color_info(string $image) {
        $query = "SELECT * FROM `{$this->prefix}colors` WHERE image=:Pimage;";
        $row = $this->db->queryRow($query, [":Pimage" => $image]);
        if ($row && is_array($row)) {
            $ii = ColorInfo::F($row);
            return $ii && $ii->valid ? $ii : null;
        }
        return null;
    }

    public function register_color_info(string $image, float $sx = null, float $sy = null, float $ex = null, float $ey = null) {
        $query = "INSERT INTO `{$this->prefix}colors`(image,crop_start_x,crop_start_y,crop_end_x,crop_end_y,doc)
            VALUES(:Pimage,:Psx,:Psy,:Pex,:Pey,:Pdoc)
            ON DUPLICATE KEY UPDATE image=VALUES(image),crop_start_x=VALUES(crop_start_x),crop_start_y=VALUES(crop_start_y),
            crop_end_x = VALUES(crop_end_x),crop_end_y=VALUES(crop_end_y),doc=VALUES(doc);
            ;";
        $this->db->exec($query, [":Pimage" => $image,
            ":Psx" => $sx, ":Psy" => $sy, ":Pex" => $ex, ":Pey" => $ey, ":Pdoc" => time()
        ]);
    }

    public function register_image_info(string $context, string $owner_id, string $image, string $title = null, int $sort = 0, float $sx = null, float $sy = null, float $ex = null, float $ey = null) {
        $query = "INSERT INTO `{$this->prefix}images`(context,owner_id,image,title,sort,crop_start_x,crop_start_y,crop_end_x,crop_end_y,doc)
            VALUES(:Pcontext,:Powner_id,:Pimage,:Ptitle,:Psort,:Psx,:Psy,:Pex,:Pey,:Pdoc)
            ON DUPLICATE KEY UPDATE context=VALUES(context),owner_id=VALUES(owner_id),
            image=VALUES(image),title=VALUES(title),sort=VALUES(sort),crop_start_x=VALUES(crop_start_x),crop_start_y=VALUES(crop_start_y),
            crop_end_x=VALUES(crop_end_x),crop_end_y=VALUES(crop_end_y),doc=VALUES(doc)
            ;";
        $this->db->exec($query, [":Pcontext" => $context, ":Powner_id" => $owner_id, ":Pimage" => $image,
            ":Psort" => $sort, ":Ptitle" => $title, ":Psx" => $sx, ":Psy" => $sy, ":Pex" => $ex, ":Pey" => $ey, ":Pdoc" => time(),
        ]);
    }

    public function set_image_order_first(string $context, string $owner_id, string $image) {
        $query = "UPDATE `{$this->prefix}images` SET sort = COALESCE( (SELECT MIN(sort)-1 FROM `{$this->prefix}images` WHERE context=:Pc AND owner_id=:Po AND image!=:Pi) ,0) WHERE context=:Pc AND owner_id=:Po AND image=:Pi; ";
        $this->db->exec($query, [
            ":Pc" => $context,
            ":Po" => $owner_id,
            ":Pi" => $image,
        ]);
    }

    public function register_or_update_image_info(string $context, string $owner_id, string $image, string $title = null, int $sort = 0, float $sx = null, float $sy = null, float $ex = null, float $ey = null) {
        $this->register_image_info($context, $owner_id, $image, $title, $sort, $sx, $sy, $ex, $ey);
//        $query = "INSERT OR IGNORE INTO images(context,owner_id,image,title,sort,crop_start_x,crop_start_y,crop_end_x,crop_end_y,doc)
//            VALUES(:Pcontext,:Powner_id,:Pimage,:Ptitle,:Psort,:Psx,:Psy,:Pex,:Pey,:Pdoc);";
//        $params = [":Pcontext" => $context, ":Powner_id" => $owner_id, ":Pimage" => $image,
//            ":Psort" => $sort, ":Ptitle" => $title, ":Psx" => $sx, ":Psy" => $sy, ":Pex" => $ex, ":Pey" => $ey, ":Pdoc" => time(),
//        ];
//        $this->pdo->prepare($query)->execute($params);
//        $query = "UPDATE images SET
//            title=:Ptitle,sort=:Psort,crop_start_x=:Psx,crop_start_y=:Psy,crop_end_x=:Pex,crop_end_y=:Pey,
//            doc=:Pdoc
//            WHERE context=:Pcontext AND owner_id=:Powner_id AND image=:Pimage;";
//        $this->pdo->prepare($query)->execute($params);
    }

    public function set_image_title(string $context, string $owner_id, string $image, string $title = null) {
        $query = "UPDATE `{$this->prefix}images` SET title=:Ptitle WHERE context=:Pcontext AND owner_id=:Powner_id AND image=:Pimage;";
        $this->db->exec($query, [":Ptitle" => $title, ":Pcontext" => $context, ":Powner_id" => $owner_id, ":Pimage" => $image]);
    }

    public function set_image_crop(string $context, string $owner_id, string $image, float $sx = null, float $sy = null, float $ex = null, float $ey = null) {
        $query = "UPDATE `{$this->prefix}images` SET crop_start_x=:Psx, crop_start_y=:Psy,crop_end_x=:Pex,crop_end_y=:Pey WHERE context=:Pcontext AND owner_id=:Powner_id AND image=:Pimage;";
        $this->db->exec($query, [":Psx" => $sx,
            ":Psy" => $sy,
            ":Pex" => $ex,
            ":Pey" => $ey,
            ":Pcontext" => $context,
            ":Powner_id" => $owner_id,
            ":Pimage" => $image]);
    }

    public function set_image_crop_v2(string $context, string $owner_id, string $image, array $items) {
        $inserts = [];
        $params = [];
        $counter = 0;
        foreach ($items as $item) {
            if (is_array($item) && count($item)) {
                $citem = \Filters\FilterManager::F()->apply_filter_array($item, [
                    'preset' => ['Strip', 'Trim', 'NEString'],
                    'csx' => ['Float'],
                    'csy' => ['Float'],
                    'cex' => ['Float'],
                    'cey' => ['Float'],
                ]);

                if (\Filters\FilterManager::F()->is_values_ok($citem)) {
                    $counter++;
                    $inserts[] = "(:Pcontext,:Powner_id,:Pimage,:P{$counter}_preset,:P{$counter}_csx,:P{$counter}_csy,:P{$counter}_cex,:P{$counter}_cey)";
                    $params = array_merge($params, [
                        ":P{$counter}_preset" => $citem["preset"],
                        ":P{$counter}_csx" => $citem["csx"],
                        ":P{$counter}_csy" => $citem["csy"],
                        ":P{$counter}_cex" => $citem["cex"],
                        ":P{$counter}_cey" => $citem["cey"],
                    ]);
                    $counter++;
                }
            }
        }
        if (count($inserts)) {
            $params[":Pcontext"] = $context;
            $params[":Powner_id"] = $owner_id;
            $params[":Pimage"] = $image;
            $query = sprintf("INSERT INTO {$this->prefix}aspect_preset (context,owner_id,image,preset,csx,csy,cex,cey) VALUES %s 
                ON DUPLICATE KEY UPDATE csx=VALUES(csx),csy=VALUES(csy),cex=VALUES(cex),cey=VALUES(cey);
            ", implode(",", $inserts));
            $this->db->exec($query, $params);
        }
//        $query = "UPDATE `{$this->prefix}images` SET crop_start_x=:Psx, crop_start_y=:Psy,crop_end_x=:Pex,crop_end_y=:Pey WHERE context=:Pcontext AND owner_id=:Powner_id AND image=:Pimage;";
//        $this->db->exec($query, [":Psx" => $sx,
//            ":Psy" => $sy,
//            ":Pex" => $ex,
//            ":Pey" => $ey,
//            ":Pcontext" => $context,
//            ":Powner_id" => $owner_id,
//            ":Pimage" => $image]);
    }

    public function set_color_crop(string $image, float $sx = null, float $sy = null, float $ex = null, float $ey = null) {
        $query = "UPDATE `{$this->prefix}colors` SET crop_start_x=:Psx, crop_start_y=:Psy,crop_end_x=:Pex,crop_end_y=:Pey WHERE  image=:Pimage;";
        $this->db->exec($query, [":Psx" => $sx,
            ":Psy" => $sy,
            ":Pex" => $ex,
            ":Pey" => $ey,
            ":Pimage" => $image]);
    }

    public function set_image_extended_data(string $context, string $owner_id, string $image, array $extended_data) {
        die(__FILE__ . __LINE__);
    }

    public function remove_color_data(string $image) {
        $query = "DELETE FROM `{$this->prefix}colors` WHERE  image=:Pimage;";
        $this->db->exec($query, [":Pimage" => $image]);
    }

    public function remove_image_data(string $context, string $owner_id, string $image) {
        $query = "DELETE FROM `{$this->prefix}images` WHERE context=:Pcontext AND owner_id=:Powner_id AND image=:Pimage;";
        $this->db->exec($query, [":Pcontext" => $context, ":Powner_id" => $owner_id, ":Pimage" => $image]);
    }

    public function remove_images(string $context, string $owner_id) {
        $query = "DELETE FROM `{$this->prefix}images` WHERE context=:Pcontext AND owner_id=:Powner_id;";
        $this->db->exec($query, [":Pcontext" => $context, ":Powner_id" => $owner_id]);
    }

    public function clear_context(string $context) {
        $query = "DELETE FROM `{$this->prefix}images` WHERE context=:Pcontext;";
        $this->db->exec($query, [":Pcontext" => $context]);
    }

    public function reorder_images(string $context, string $owner_id, array $image_natural_order) {
        $c = 0;
        $inserts = [];
        $params = [":Pcontext" => $context, ":Powner_id" => $owner_id];
        foreach ($image_natural_order as $image_name) {
            $inserts[] = "(:Pcontext,:Powner_id,:P{$c}name,:P{$c}sort)";
            $params[":P{$c}name"] = $image_name;
            $params[":P{$c}sort"] = $c;
            $c++;
        }
        if (count($inserts)) {
            $tn = "a" . md5(__METHOD__);
            $this->db->exec("DROP TEMPORARY TABLE IF EXISTS `{$tn}`;
                CREATE TEMPORARY TABLE `$tn` ( context VARCHAR(100),owner_id VARCHAR(100), image VARCHAR(32),sort int(11),PRIMARY KEY(context,owner_id,image) )ENGINE=MEMORY;");
            $query = "INSERT INTO `{$tn}` (context,owner_id,image,sort) VALUES " . implode(",", $inserts) . " ON DUPLICATE KEY UPDATE context=VALUES(context),owner_id=VALUES(owner_id),image=VALUES(image),sort=VALUES(sort);";
            $this->db->exec($query, $params);
            $this->db->exec("UPDATE `{$this->prefix}images` A JOIN `{$tn}` B ON(A.context=B.context AND A.owner_id=B.owner_id AND A.image=B.image) 
                SET A.sort = COALESCE(B.sort,0);");
            $this->db->exec("DROP TEMPORARY TABLE IF EXISTS `{$tn}`;");
        }
    }

    public function clear_orphaned_colors(array $used_colors) {
        $used_colors = array_unique($used_colors);
        $tn = "a" . md5(__METHOD__);
        $queries = ["DROP TEMPORARY TABLE IF EXISTS `{$tn}`;",
            "DROP TEMPORARY TABLE IF EXISTS `{$tn}q`;",
            "CREATE TEMPORARY TABLE `{$tn}`(guid VARCHAR(48),PRIMARY KEY(guid));",
            "CREATE TEMPORARY TABLE `{$tn}q`(guid VARCHAR(48),PRIMARY KEY(guid));",
        ];
        if (count($used_colors)) {
            $queries[] = "INSERT INTO `{$tn}`(guid) VALUES('" . implode("'),('", $used_colors) . "') ON DUPLICATE KEY UPDATE guid=VALUES(guid);";
        }
        $this->db->exec(implode("\n", $queries));
        // выбрать цвета, которые нужно удалить
        $query = "INSERT INTO `{$tn}q`(guid) SELECT B.image FROM `{$this->prefix}colors` B LEFT JOIN `{$tn}` A ON(B.image=A.guid) WHERE A.guid IS NULL AND B.doc<:P;                    
        ";
        $this->db->exec($query, [":P" => (time() - 86400)]);
        $query = "SELECT guid FROM `{$tn}q`;";
        $image_fly = ImageFly::F();
        $rows = $this->db->query($query);
        while (($x = $rows->fetch())) {
            $image_fly->remove_color_files($x['guid']);
        }
        $this->db->exec("DELETE FROM `{$this->prefix}colors` WHERE EXISTS( SELECT guid FROM `{$tn}q` WHERE guid=colors.image )");
    }

    public function set_image_properties(string $context, string $owner_id, string $image, array $props) {
        MediaContextInfo::F()->context_exists($context) ? 0 : ImageFlyError::RF("unknown media context `%s`", $context);
        $props = ImagePropertyCollection::F($context, $owner_id, $image);
        $props->import_array($props, ImagePropertiesFilterDelegate::MODE_SET);
        $query = ["DELETE FROM `{$this->prefix}images_props` WHERE context=:Pcontext AND owner_id=:Powner_id AND image=:Pimage;"];
        $params = [":Pcontext" => $context, ":Powner_id" => $owner_id, ":Pimage" => $image];
        $counter = 0;
        $inserts = [];
        if (count($props)) {
            foreach ($props as $key => $value) {
                if (null !== $value) {
                    $inserts[] = "(:Pcontext,:Powner_id,:Pimage,:P{$counter}key,:P{$counter}value)";
                    $params[":P{$counter}key"] = $key;
                    $params[":P{$counter}value"] = $value;
                    $counter++;
                }
            }
            if (count($inserts)) {
                $query[] = sprintf("INSERT INTO `{$this->prefix}images_props`(context,owner_id,image,property_name,property_value) VALUES %s ;", implode(",", $inserts));
            }
        }
        $this->db->exec(implode("\n", $query), $params);
    }

    public function append_image_properties(string $context, string $owner_id, string $image, array $input_props = null) {
        if ($input_props && count($input_props)) {
            MediaContextInfo::F()->context_exists($context) ? 0 : ImageFlyError::RF("unknown media context `%s`", $context);
            $props = ImagePropertyCollection::F($context, $owner_id, $image);
            $props->import_array_for_append($input_props);
            if (count($props)) {
                $query = [];
                $params = [":Pcontext" => $context, ":Powner_id" => $owner_id, ":Pimage" => $image];
                $counter = 0;
                $acts = 0;
                $inserts = [];
                foreach ($props as $key => $value) {
                    $this->db->exec("DELETE FROM `{$this->prefix}images_props` 
                        WHERE context=:Pcontext AND owner_id=:Powner_id AND image=:Pimage AND property_name=:P{$counter}key;", [":P{$counter}key" => $key, ":Pcontext" => $context, ":Powner_id" => $owner_id, ":Pimage" => $image, ":P{$counter}key" => $key]);
                    if (null !== $value) {
                        $inserts[] = "(:Pcontext,:Powner_id,:Pimage,:P{$counter}key,:P{$counter}value)";
                        $params[":P{$counter}key"] = $key;
                        $params[":P{$counter}value"] = $value;
                        $acts++;
                    }
                    $counter++;
                }
                if (count($inserts)) {
                    $query = sprintf("INSERT INTO `{$this->prefix}images_props`(context,owner_id,image,property_name,property_value) VALUES %s ON DUPLICATE KEY UPDATE 
                        property_value=VALUES(property_value)
                        ;", implode(",", $inserts));
                    //die();
                    $this->db->exec($query, $params);
                }
            }
        }
    }

    /**
     * 
     * @param string $context
     * @param string $owner_id
     * @param array $images [image=>["prop"=>value,"prop"=>value],image=>[]]
     */
    public function set_images_properties(string $context, string $owner_id, array $images) {
        MediaContextInfo::F()->context_exists($context) ? 0 : ImageFlyError::RF("unknown media context `%s`", $context);
        $params = [":Pcontext" => $context, ":Powner_id" => $owner_id];
        $query = ["DELETE FROM `{$this->prefix}images_props` WHERE context=:Pcontext AND owner_id=:Powner_id;"];
        $counter = 0;
        $global_inserts = [];
        $global_params = []; // [":Pcontext" => $context, ":Powner_id" => $owner_id];
        foreach ($images as $image => $properties) {
            $counter++;
            $props = ImagePropertyCollection::F($context, $owner_id, $image);
            $props->import_array($properties, ImagePropertiesFilterDelegate::MODE_SET);
            if (count($props)) {
                $ic = 0;
                foreach ($props as $prop_key => $prop_value) {
                    if ($prop_value !== null) {
                        $global_inserts[] = "(:P{$counter}context{$ic}a,:P{$counter}owner_id{$ic}a,:P{$counter}image{$ic}a,'{$prop_key}',:P{$counter}property_value{$ic}a)";
                        $global_params[":P{$counter}context{$ic}a"] = $context;
                        $global_params[":P{$counter}owner_id{$ic}a"] = $owner_id;
                        $global_params[":P{$counter}image{$ic}a"] = $image;
                        //$global_params[":P{$counter}property_name{$ic}a"] = $prop_key; /// why?????
                        $global_params[":P{$counter}property_value{$ic}a"] = $prop_value;
                        $ic++;
                    }
                }
            }
        }


        $this->db->beginTransaction();
        try {
            $this->db->exec(implode("\n", $query), $params);
            if (count($global_inserts)) {
                $query2 = sprintf("INSERT INTO images_props(context,owner_id,image,property_name,property_value) VALUES %s ;", implode(",", $global_inserts));
                $this->db->exec($query2, $global_params);
            }
        } catch (\Throwable $e) {
            $this->db->Rollback();
            var_dump($e);
            die();
            throw $e;
        }
        $this->db->commit();
        ;
    }

    public function get_adapter(): \DB\IDBAdapter {
        return $this->db;
    }

}
