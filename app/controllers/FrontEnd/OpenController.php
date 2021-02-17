<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of OpenController
 *
 * методы для публичного доступа 
 * 
 * 
 * @author eve
 */
class OpenController extends AbstractFrontendController {
    /**
     * 
     * 
     * ИНдекс - дельта + рт
     * В индекс входят - теги, 
     * 
     * Или забъем пока на свинкс?
     * 
     * 
     * user collections
     * 
     */

    /**
     * вывод всего и вся что доступно в принципе
     * с ранжированием по пользовательским тегам и популярности
     */
    protected function API_list() {
        $limit = \DataMap\InputDataMap::F()->get_filtered('limit', ['IntMore0', 'DefaultNull']);
        $limit && $limit > 10 ? 0 : $limit = 100;
        $limit && $limit < 1000 ? 0 : $limit = 100;
        $offset = \DataMap\InputDataMap::F()->get_filtered('offset', ['IntMore0', 'Default0']);
        $rows_ql = "
            SELECT 
                B.id gallery_id,A.id,A.title,B.name gallery_name, A.created,B.owner_id
                ,CASE WHEN CHAR_LENGTH(TRIM(US.eldername))=0 THEN US.name ELSE US.eldername END nicname
                FROM
                public__gallery__item A JOIN public__gallery B ON(A.gallery_id=B.id)
                JOIN user U ON(U.id=B.owner_id)
                JOIN user__fields US ON(US.id=U.id)
                WHERE A.active=1 AND B.visible=1
                ORDER BY A.id 
                LIMIT {$limit} OFFSET {$offset};
            ";

        $rows_ql = "
            SELECT gallery_id,AX.id,title,gallery_name,AX.created,owner_id
             ,CASE WHEN CHAR_LENGTH(TRIM(US.eldername))=0 THEN US.name ELSE US.eldername END nicname
            FROM(
            SELECT 
                B.id gallery_id,A.id,A.title,B.name gallery_name, A.created,B.owner_id               
                FROM
                public__gallery__item A JOIN public__gallery B ON(A.gallery_id=B.id)
                
                
                WHERE A.active=1 AND B.visible=1
                ORDER BY A.id DESC
                LIMIT {$limit} OFFSET {$offset}
            )AX
            JOIN user U ON(U.id=AX.owner_id)
            JOIN user__fields US ON(US.id=U.id)
            ORDER BY AX.id DESC
            
                    
            ";
        $this->out->add('items', \DB\DB::F()->queryAll($rows_ql));
    }

    protected function API_tag_ac() {
        $term = \DataMap\InputDataMap::F()->get_filtered("term", ['Trim', 'NEString', 'DefaultNull']);
        $results = [];
        $term = str_ireplace(["%", "_"], ["\\%", "\\_"], $term);
        if ($term) {
            $results = \DB\DB::F()->queryAll("SELECT * FROM public__tag WHERE tag LIKE :P ORDER BY id LIMIT 100 OFFSET 0", [":P" => "{$term}%"]);
        }


        $this->out->add('tag_ac', $results);
    }

    protected function API_tag_ac2() {
        $term = \DataMap\InputDataMap::F()->get_filtered("term", ['Trim', 'NEString', 'DefaultNull']);
        $results = [];
        $term = str_ireplace(["%", "_"], ["\\%", "\\_"], $term);
        if ($term) {
            $results = \DB\DB::F()->queryAll("SELECT * FROM public__tag WHERE tag LIKE :P ORDER BY id LIMIT 100 OFFSET 0", [":P" => "%{$term}%"]);
        }


        $this->out->add('tag_ac', $results);
    }

    protected function API_search_tag() {
        $result = [];
        $tags = \DataMap\InputDataMap::F()->get_filtered("tag", ["NEArray", 'ArrayOfNEString', 'NEArray', 'DefaultNull'],
                \Filters\params\ArrayParamBuilder::B(["ArrayOfNEString" => ["more" => 2]], true)->get_param_set_for_property()
        );
        $limit = \DataMap\InputDataMap::F()->get_filtered("limit", ["IntMore0", 'Default0']);
        $limit = min([max([10, $limit]), 1000]);
        $offset = \DataMap\InputDataMap::F()->get_filtered("offset", ["IntMore0", 'Default0']);

        if (!($tags && count($tags))) {
            \Errors\common_error::R("no searchable tags");
        }
        $tmp = "a" . md5(__METHOD__);
        $q = "DROP TEMPORARY TABLE IF EXISTS `{$tmp}`;DROP TEMPORARY TABLE IF EXISTS `{$tmp}a`;DROP TEMPORARY TABLE IF EXISTS `{$tmp}b`;
            CREATE TEMPORARY TABLE `{$tmp}` (tag VARCHAR(100) NOT NULL,PRIMARY KEY(tag))ENGINE=MyISAM;
            CREATE TEMPORARY TABLE `{$tmp}a` (id BIGINT(19) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MyISAM;                
            CREATE TEMPORARY TABLE `{$tmp}b` (id BIGINT(19) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MyISAM;                
        ";
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push($q);
        $inserts = [];
        foreach ($tags as $tag) {
            $inserts[] = "(:P{$b->c}tag)";
            $b->push_param(":P{$b->c}tag", $tag);
            $b->inc_counter();
        }
        $b->push(sprintf("INSERT INTO `{$tmp}` (tag) VALUES %s ON DUPLICATE KEY UPDATE tag=VALUES(tag);", implode(",", $inserts)));
        $b->push("
            INSERT INTO `{$tmp}a` (id)
                SELECT id FROM `{$tmp}` A JOIN public__tag B ON(B.tag=A.tag)
                WHERE EXISTS( SELECT tag_id FROM public__gallery__item__tag__result WHERE tag_id=B.id  )    
                ON DUPLICATE KEY UPDATE id = VALUES(id);
            ");
        $b->execute();
        $ft = \DB\DB::F()->queryScalari("SELECT COUNT(*) FROM `{$tmp}a`");
        if ($ft) {
            $this->out->add("debug_tts", \DB\DB::F()->queryAll("SELECT * FROM public__tag A JOIN `{$tmp}a` B ON(A.id=B.id)"));
            $query = "
            SELECT gallery_id,AX.id,title,gallery_name,AX.created,owner_id
             ,CASE WHEN CHAR_LENGTH(TRIM(US.eldername))=0 THEN US.name ELSE US.eldername END nicname
            FROM(
            SELECT 
                B.id gallery_id,A.id,A.title,B.name gallery_name, A.created,B.owner_id               
                FROM
                public__gallery__item A JOIN public__gallery B ON(A.gallery_id=B.id)                                
                WHERE A.active=1 AND B.visible=1
                AND EXISTS(SELECT TR.item_id FROM public__gallery__item__tag__result TR JOIN `{$tmp}a` X ON(X.id=TR.tag_id) WHERE TR.item_id=A.id)
                ORDER BY A.id DESC
                LIMIT {$limit} OFFSET {$offset}
                    )AX
            JOIN user U ON(U.id=AX.owner_id)
            JOIN user__fields US ON(US.id=U.id)
            ORDER BY AX.id DESC
            ";

            $result = \DB\DB::F()->queryAll($query);
        }
        $this->out->add("items", $result);
    }

    protected function API_search_user() {
        $query = \DataMap\InputDataMap::F()->get_filtered("term", ["Trim", "NEString", "DefaultNull"]);
        $query ? 0 : \Errors\common_error::R("query is empty");
        $limit = \DataMap\InputDataMap::F()->get_filtered("limit", ["IntMore0", 'Default0']);
        $limit = min([max([10, $limit]), 1000]);
        $offset = \DataMap\InputDataMap::F()->get_filtered("offset", ["IntMore0", 'Default0']);
        mb_strlen($query, "UTF-8") < 3 ? \Errors\common_error::R("min query length is 3") : 0;
        $result = \DB\DB::F()->queryAll("
            SELECT B.id,CASE WHEN CHAR_LENGTH(TRIM(eldername))>0 THEN eldername ELSE name END nic,
            B.created
            FROM  user__search A JOIN user B ON(B.id=A.id)
            JOIN user__fields C ON(C.id=A.id)           
            WHERE search_name LIKE :P 
            
            ORDER BY B.created DESC, B.id DESC
            
            LIMIT $limit OFFSET $offset
            ", [":P" => implode("", ["%", str_ireplace(["%", "_"], ["\\%", "\\_"], $query), "%"])]);
        $this->out->add('users', $result);
    }

    protected function API_get_user() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0"]);
        \Filters\FilterManager::F()->raise_array_error(compact('id'));
        $q = " SELECT U.id,CASE WHEN CHAR_LENGTH(TRIM(eldername))>0 THEN eldername ELSE name END nic,
            U.created
             FROM user U 
            JOIN user__fields C ON(C.id=U.id)           
            WHERE U.id=:P                        
            ";
        $r = \DB\DB::F()->queryRow($q, [":P" => $id]);
        $r ? 0 : \Errors\common_error::R("not found");
        $this->out->add("user", $r);
    }

    protected function API_get_user_media() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0"]);
        $limit = \DataMap\InputDataMap::F()->get_filtered("limit", ["IntMore0", 'Default0']);
        $limit = min([max([10, $limit]), 1000]);
        $offset = \DataMap\InputDataMap::F()->get_filtered("offset", ["IntMore0", 'Default0']);
        $query = "
            SELECT gallery_id,AX.id,title,gallery_name,AX.created,owner_id
             ,CASE WHEN CHAR_LENGTH(TRIM(US.eldername))=0 THEN US.name ELSE US.eldername END nicname
            FROM(
            SELECT 
                B.id gallery_id,A.id,A.title,B.name gallery_name, A.created,B.owner_id               
                FROM
                public__gallery__item A JOIN public__gallery B ON(A.gallery_id=B.id)                                
                WHERE A.active=1 AND B.visible=1 AND B.owner_id=:PPP                
                ORDER BY A.id DESC
                LIMIT {$limit} OFFSET {$offset}
                    )AX
            JOIN user U ON(U.id=AX.owner_id)
            JOIN user__fields US ON(US.id=U.id)
            ORDER BY AX.id DESC
            ";
        $result = \DB\DB::F()->queryAll($query, [":PPP" => $id]);
        $this->out->add("items", $result);
    }

    protected function API_search_user_media() {
        $result = [];
        $tags = \DataMap\InputDataMap::F()->get_filtered("tag", ["NEArray", 'ArrayOfNEString', 'NEArray', 'DefaultNull'],
                \Filters\params\ArrayParamBuilder::B(["ArrayOfNEString" => ["more" => 2]], true)->get_param_set_for_property()
        );
        $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0", "DefaultNull"]);
        $limit = \DataMap\InputDataMap::F()->get_filtered("limit", ["IntMore0", 'Default0']);
        $limit = min([max([10, $limit]), 1000]);
        $offset = \DataMap\InputDataMap::F()->get_filtered("offset", ["IntMore0", 'Default0']);

        if (!($tags && count($tags))) {
            \Errors\common_error::R("no searchable tags");
        }
        $id ? 0 : \Errors\common_error::R("no author id");
        $tmp = "a" . md5(__METHOD__);
        $q = "DROP TEMPORARY TABLE IF EXISTS `{$tmp}`;DROP TEMPORARY TABLE IF EXISTS `{$tmp}a`;DROP TEMPORARY TABLE IF EXISTS `{$tmp}b`;
            CREATE TEMPORARY TABLE `{$tmp}` (tag VARCHAR(100) NOT NULL,PRIMARY KEY(tag))ENGINE=MyISAM;
            CREATE TEMPORARY TABLE `{$tmp}a` (id BIGINT(19) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MyISAM;                
            CREATE TEMPORARY TABLE `{$tmp}b` (id BIGINT(19) UNSIGNED NOT NULL,PRIMARY KEY(id))ENGINE=MyISAM;                
        ";
        $b = \DB\SQLTools\SQLBuilder::F();
        $b->push($q);
        $inserts = [];
        foreach ($tags as $tag) {
            $inserts[] = "(:P{$b->c}tag)";
            $b->push_param(":P{$b->c}tag", $tag);
            $b->inc_counter();
        }
        $b->push(sprintf("INSERT INTO `{$tmp}` (tag) VALUES %s ON DUPLICATE KEY UPDATE tag=VALUES(tag);", implode(",", $inserts)));
        $b->push("
            INSERT INTO `{$tmp}a` (id)
                SELECT id FROM `{$tmp}` A JOIN public__tag B ON(B.tag=A.tag)
                WHERE EXISTS( SELECT tag_id FROM public__gallery__item__tag__result WHERE tag_id=B.id  )    
                ON DUPLICATE KEY UPDATE id = VALUES(id);
            ");
        $b->execute();
        $ft = \DB\DB::F()->queryScalari("SELECT COUNT(*) FROM `{$tmp}a`;");
        if ($ft) {
            $this->out->add("debug_tts", \DB\DB::F()->queryAll("SELECT * FROM public__tag A JOIN `{$tmp}a` B ON(A.id=B.id)"));
            $query = "
            SELECT gallery_id,AX.id,title,gallery_name,AX.created,owner_id
             ,CASE WHEN CHAR_LENGTH(TRIM(US.eldername))=0 THEN US.name ELSE US.eldername END nicname
            FROM(
            SELECT 
                B.id gallery_id,A.id,A.title,B.name gallery_name, A.created,B.owner_id               
                FROM
                public__gallery__item A JOIN public__gallery B ON(A.gallery_id=B.id)                                
                WHERE A.active=1 AND B.visible=1 AND B.owner_id=:P
                AND EXISTS(SELECT TR.item_id FROM public__gallery__item__tag__result TR JOIN `{$tmp}a` X ON(X.id=TR.tag_id) WHERE TR.item_id=A.id)
                ORDER BY A.id DESC
                LIMIT {$limit} OFFSET {$offset}
                    )AX
            JOIN user U ON(U.id=AX.owner_id)
            JOIN user__fields US ON(US.id=U.id)
            ORDER BY AX.id DESC
            ";

            $result = \DB\DB::F()->queryAll($query, [":P" => $id]);
        }
        $this->out->add("items", $result);
    }    
    
    protected function actionget_media_content(){
        try {
            $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0", "Default0"]);
            $id ? 0 : \Errors\common_error::HR("id required", 500);
            $item = \PublicMedia\PublicMediaItemShort::F()->load($id);
            $item && $item->valid ? 0 : \Errors\common_error::HR("not found", 404);
            if (!($item->active && $item->gallery_visible)) {
                if (!($this->auth->is_authentificated() && $this->auth->get_id() === $item->owner_id)) {
                    \Errors\common_error::HR("media is hidden", 403);
                }
            }
            if (headers_sent()) {
                \Errors\common_error::R("headers alredy sent");
            }
            header("Content-Type: {$item->safe_type}");
            header("X-SendFile: " . realpath($item->get_media_path()));
        } catch (\Errors\common_error $e) {
            if (!headers_sent()) {
                $code = $e->get_http_code() ? $e->get_http_code() : 500;
                header("HTTP/1.0 {$code} {$this->get_http_code_string($code)}");
            }
            die($e->getMessage());
        } catch (\Throwable $e) {
            if (!headers_sent()) {
                $code = 500;
                header("HTTP/1.0 {$code} {$this->get_http_code_string($code)}");
            }
            die($e->getMessage());
        }
    }

    protected function actionget_media_preview() {
        try {
            $id = \DataMap\InputDataMap::F()->get_filtered("id", ["IntMore0", "Default0"]);
            $id ? 0 : \Errors\common_error::HR("id required", 500);
            $item = \PublicMedia\PublicMediaItemShort::F()->load($id);
            $item && $item->valid ? 0 : \Errors\common_error::HR("not found", 404);
            if (!($item->active && $item->gallery_visible)) {
                if (!($this->auth->is_authentificated() && $this->auth->get_id() === $item->owner_id)) {
                    \Errors\common_error::HR("image is hidden", 403);
                }
            }
            if (headers_sent()) {
                \Errors\common_error::R("headers alredy sent");
            }
            header("Content-Type: image/jpeg");
            header("X-SendFile: " . realpath($item->get_preview_path()));
        } catch (\Errors\common_error $e) {
            if (!headers_sent()) {
                $code = $e->get_http_code() ? $e->get_http_code() : 500;
                header("HTTP/1.0 {$code} {$this->get_http_code_string($code)}");
            }
            die($e->getMessage());
        } catch (\Throwable $e) {
            if (!headers_sent()) {
                $code = 500;
                header("HTTP/1.0 {$code} {$this->get_http_code_string($code)}");
            }
            die($e->getMessage());
        }
    }

}
