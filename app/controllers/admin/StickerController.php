<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of StickerController
 *
 * @author eve
 */
class StickerController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.StickerList";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    protected function API_list() {
        \Content\Stickers\Lister::F(\DataMap\InputDataMap::F())->run($this->out);
    }

    protected function API_get(int $p = null) {
        $id = $p ? $p : $this->GP->get_filtered('id', ['IntMore0', 'DefaultNull']);
        $id ? false : \Errors\common_error::R("invalid request");
        $cb = \Content\Stickers\StickerItem::F($id);
        $cb && $cb->id ? 0 : \Errors\common_error::R("not found");
        $this->out->add('data', $cb);
    }

    protected function API_put() {
        $data = \DataMap\InputDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $ret_id = \Content\Stickers\Writer::F(\DataMap\CommonDataMap::F()->rebind($data))->run()->operation_id;
        $this->API_get($ret_id);
    }

    protected function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id_to_remove", ['IntMore0', 'DefaultNull']);
        if ($id) {
            \Content\Stickers\Remover::F($id)->run();
        }
        $this->API_list();
    }

    protected function API_stata() {
        die('permission denied');
        $query = "SELECT id,cdn_id FROM media__content__cdn__file WHERE info LIKE '%trbcdn%'";
        $rows = \DB\DB::F()->queryAll($query);
        $b = \DB\SQLTools\SQLBuilder::F();
        foreach ($rows as $row) {
            $id = intval($row['id']);
            $cdn_id = \Helpers\Helpers::NEString($row['cdn_id']);
            $request = \CDN_DRIVER\CDNInfoRequest::F();
            $request->run($cdn_id);
            if ($request->success) {
                $b->push("UPDATE media__content__cdn__file SET info=:P{$b->c}inf WHERE id=:P{$b->c}id;")
                        ->push_params([
                            ":P{$b->c}inf" => json_encode($request->result),
                            ":P{$b->c}id" => $id,
                        ])->inc_counter();
            }
        }
        $b->execute_transact();
        var_dump($b->sql);
        die();
    }

    protected function API_stat() {
        die('permission denied');
        $query = "SELECT id,cdn_id FROM media__lent__gif WHERE cdn_id IS NOT NULL";
        $rows = \DB\DB::F()->queryAll($query);
        $b = \DB\SQLTools\SQLBuilder::F();
        foreach ($rows as $row) {
            $id = intval($row['id']);
            $cdn_id = \Helpers\Helpers::NEString($row['cdn_id']);
            $request = \CDN_DRIVER\CDNInfoRequest::F();
            $request->run($cdn_id);
            if ($request->success) {
                if (array_key_exists('cdn_url', $request->result)) {
                    $b->push("UPDATE media__lent__gif SET cdn_url=:P{$b->c}url WHERE id=:P{$b->c}id;")
                            ->push_params([
                                ":P{$b->c}url" => $request->result['cdn_url'],
                                ":P{$b->c}id" => $id,
                            ])->inc_counter();
                }
            }
        }
        $b->execute_transact();
    }

}
