<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of ComChillController
 *
 * @author eve
 */
class ComChillController extends AbstractFrontendController {

    //put your code here
    public function actionIndex() {
        \smarty\SMW::F()->smarty->assign('auth_status', \Auth\Auth::F()->is_authentificated());
        \smarty\SMW::F()->smarty->assign('stickers', \Content\Stickers\StickerList::F());
        \smarty\SMW::F()->smarty->assign('comments', \Content\ChillComment\ChillCommentList::F());
        $this->render_view($this->get_requested_layout('front/layout'), $this->get_requested_template('default'));
    }

    public function API_vote() {
        $this->check_auth();
        Helpers\ChillVoteWriter::run();
    }

    public function API_comment() {
        $this->check_auth();
        Helpers\ChillCommentWriter::run();
    }

    protected function check_auth() {
        if (!$this->auth->is_authentificated()) {
            \Errors\common_error::R("auth");
        }
    }

}
