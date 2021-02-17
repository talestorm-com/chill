<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\admin;

/**
 * Description of EmojiListController
 *
 * @author eve
 */
class EmojiListController extends AbstractAdminController {

    public function get_desktop_component_id() {
        return "desktop.emoji_list";
    }

    public function actionIndex() {
        $this->render_view('admin', '../common_index');
    }

    public function API_vocabulary() {
        //$this->out->add('voc', \Emoji\EmojiList::F());
    }

    public function API_list() {
        \Emoji\Lister::F(\DataMap\PostDataMap::F())->run($this->out);
        $this->API_vocabulary();
    }

    public function API_get(string $rid = null) {
        $id = $rid ? $rid : \DataMap\InputDataMap::F()->get_filtered("id", ['IntMore0', 'DefaultNull']);
        $id ? 0 : \Errors\common_error::R("invalid request");
        $emo = \Emoji\EmojiListItem::F()->load_db($id);
        $emo && $emo->valid ? 0 : \Errors\common_error::R("not found");
        $this->out->add('data', $emo);
        $this->API_langlist();
    }

    public function API_langlist() {
        $this->out->add('langs', \Language\LanguageList::F());
    }

    public function API_put() {
        $data = \DataMap\InputDataMap::F()->get_filtered("data", ['Trim', 'NEString', 'JSONString', 'NEArray', 'DefaultNull']);
        $data ? 0 : \Errors\common_error::R("invalid request");
        $ret_id = \Emoji\EmojiWriter::F(\DataMap\CommonDataMap::F()->rebind($data))->run()->id;
        $this->API_get($ret_id);
    }

    public function API_remove() {
        $id = \DataMap\InputDataMap::F()->get_filtered("id_to_remove", ['IntMore0', 'DefaultNull']);
        if ($id) {
            \DB\SQLTools\SQLBuilder::F()->push("DELETE FROM media__emoji WHERE id=:P")->push_param(":P", $id)->execute_transact();
        }
        $this->API_list();
    }

}
