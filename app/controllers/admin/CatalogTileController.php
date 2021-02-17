<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

class CatalogTileController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.CatalogTile";
    }

    public function actionIndex() {
        if (!\ImageFly\MediaContextInfo::F()->context_exists(\Content\CatalogTile\CatalogTile::MEDIA_CONTEXT)) {
            \ImageFly\MediaContextInfo::register_media_context(\Content\CatalogTile\CatalogTile::MEDIA_CONTEXT, 3600, 3600, 300, 300);
        }
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        \Content\CatalogTile\ADVTLister::F($this->out)->run();
    }

    protected function API_get_metadata() {
        $md = [
            'loaders' => \Content\CatalogTile\CatalogTileLoaderEnumerator::F(),
            'templates' => \Content\CatalogTile\CatalogTileTemplatesEnumerator::F()
        ];
        $this->out->add("catalog_tile_metadata", $md);
    }

    protected function API_get(int $p = null) {
        $id = $p ? $p : $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? false : \Errors\common_error::R("invalid request");
        $catalog_tile = \Content\CatalogTile\CatalogTile::F($id);
        $this->out->add('catalog_tile', $catalog_tile);
        $this->API_get_metadata();
    }

    protected function API_post() {
        $data = $this->GP->get_filtered('data', ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? false : \Errors\common_error::R("invalid request");
        $datamap = \DataMap\CommonDataMap::F()->rebind($data);
        $ret_id = \Content\CatalogTile\Writer\Writer::F($this->out, $datamap)->run();
        $this->API_get($ret_id);
    }

    protected function API_remove() {
        $id_to_remove = $this->GP->get_filtered('id_to_remove', ['IntMore0', 'DefaultNull']);
        $id_to_remove ? false : \Errors\common_error::R("invalid request");
        \ImageFly\ImageFly::F()->remove_images(\Content\CatalogTile\CatalogTile::MEDIA_CONTEXT, (string) $id_to_remove);
        \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM catalog__tile WHERE id=:Pid")->push_param(":Pid", $id_to_remove)->execute_transact();
        \Content\CatalogTile\CatalogTile::clear_dependency_beacon();
        $this->API_list();
    }

}
