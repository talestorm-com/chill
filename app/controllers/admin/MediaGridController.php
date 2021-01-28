<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of MediaGridController
 *
 * @author eve
 */
class MediaGridController extends AbstractAdminController {

    protected function actionIndex() {
        $this->render_view("admin", "list");
    }

    protected function API_list() {
        $this->out->add("metadata", \Content\MediaContent\MediaContentTypeList::F());
        \Content\MediaContent\listerSOAP::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_language_list() {
        $this->out->add('metadata', [
            'language_list' => \Language\LanguageList::F(),
        ]);
    }

    protected function API_grid_season_info() {
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['IntMore0', 'DefaultNull']);
        if ($id) {
            $query = "SELECT A.id,                        
            COALESCE(ASL1.name,ASL2.name)name,            
            ASN.common_name common_name,
            ASN.default_poster,
            NULL as position
            FROM media__content A                         
            LEFT JOIN media__content__season ASN ON(ASN.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL1 ON(ASL1.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL2 ON(ASL2.id=A.id)    
            WHERE A.id=:P
            ";
            $this->out->add('data', \DB\DB::F()->queryRow(sprintf($query, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language()), [":P" => $id]));
        }
    }

    protected function API_grid() {
        $this->out->add('total', \DB\DB::F()->queryScalari("SELECT COUNT(*) FROM media__content__season A JOIN media__content B ON(A.id=B.id) WHERE B.enabled=1;", []));
        $query = "SELECT A.id,                        
            COALESCE(ASL1.name,ASL2.name)name,            
            ASN.common_name common_name,
            ASN.default_poster,
            G.position
            FROM media__content A                         
            LEFT JOIN media__content__season ASN ON(ASN.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL1 ON(ASL1.id=A.id)
            LEFT JOIN media__content__season__strings__lang_%s ASL2 ON(ASL2.id=A.id)    
            JOIN media__grid G ON(G.content_id=A.id)
            ORDER BY G.position
            ";
        $this->out->add('grid', \DB\DB::F()->queryAll(sprintf($query, \Language\LanguageList::F()->get_current_language(), \Language\LanguageList::F()->get_default_language()), []));
    }

    protected function API_ribbon_list() {
        $language = \Language\LanguageList::F()->get_current_language();
        $offset = \DataMap\InputDataMap::F()->get_filtered('ribbon_offset', ['IntMore0', 'Default0']);
        $perpage = 100;
        $ribbon = \Content\MediaContentRibbon\MediaContentRibbon::F($language, $offset, $perpage, true);
        $this->out->add('ribbon', $ribbon);
    }

    protected function API_ribbon_remove() {
        $id_to_remove = \DataMap\InputDataMap::F()->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        if ($id_to_remove) {
            \Content\MediaContentRibbon\MediaContentRibbon::delete($id_to_remove);
        }
        $this->API_ribbon_list();
    }

    protected function API_lent_add_content() {
        $id = \DataMap\InputDataMap::F()->get_filtered('id', ['NEArray', 'ArrayOfInt', 'NEArray', 'DefaultNull']);
        if ($id) {
            \Content\MediaContentRibbon\MediaContentRibbon::prepend_array($id);
        }
        $this->API_ribbon_list();
    }

    protected function API_post_grid() {
        $data = \DataMap\InputDataMap::F()->get_filtered('posx', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        if ($data && count($data)) {
            $b = \DB\SQLTools\SQLBuilder::F();
            $b->push("DELETE  FROM media__grid;");
            foreach ($data as $row) {
                $crow = \Filters\FilterManager::F()->apply_filter_array($row, [
                    'pos' => ['Int'],
                    'id' => ['IntMore0'],
                ]);
                if (\Filters\FilterManager::F()->is_values_ok($crow)) {
                    $b->inc_counter()->push("INSERT INTO media__grid(position,content_id) VALUES(:P{$b->c}pos,:P{$b->c}id);")
                            ->push_params([
                                ":P{$b->c}pos" => $crow['pos'],
                                ":P{$b->c}id" => $crow['id'],
                            ])->inc_counter();
                }
            }            
            if (!$b->empty) {
                $b->execute_transact();                
            }
        }
        return $this->API_grid();
    }

}
