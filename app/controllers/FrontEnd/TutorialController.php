<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd;

/**
 * Description of TutorialController
 *
 * @author eve
 */
class TutorialController extends AbstractFrontendController {
    //put your code here

    /** @var int */
    protected $client_id;

    protected function check_api_access(): bool {
        $this->client_id = $this->auth->is_authentificated() ? $this->auth->id : null;
        return $this->auth->is_authentificated();
    }

    public function actionGet_protected_video() {
        if (!$this->auth->is_authentificated()) {
            header('HTTP/1.0 403 Forbidden');
            die('private area');
        }
        $request = \Router\Request::F()->request_path;
        $m = [];
        //media\/protected\/tutorials/4/xxx.mp4
        ///media/private/7e0edde0-e863-11e9-8282-001e5826d92c/cover.jpg

        if (preg_match("/^\/{0,1}media\/protected\/tutorial\/(?P<group>\d{1,})\/(?P<name>.{10,})$/i", $request, $m)) {
            try {
                $VideoGroup = \Content\Video\VideoGroup::C(intval($m['group']));

                if (($VideoGroup->active || $this->auth->is(\Auth\Roles\RoleAdmin::class)) && \Auth\ProductAccessMonitor::F()->has_access_to_tutorial((string) $VideoGroup->id)) {
                    $video_item = $VideoGroup->get_item_by_image_file($m['name']);
                    if ($video_item) {
                        $video_path = \Config\Config::F()->PROTECTED_VIDEOTUTORIALS_BASE . $VideoGroup->id . DIRECTORY_SEPARATOR . $video_item->video;
                        if (file_exists($video_path)) {
                            header("Content-Type: ".($video_item->mime?$video_item->mime:"application/octet-stream"));
                            header("X-SendFile: " . realpath($video_path));
                            die();
                        }
                    }
                } else {
                    header('HTTP/1.0 403 Forbidden');
                    die('access denied');
                }
            } catch (\Throwable $e) {
                
            }
        }
        header('HTTP/1.0 404 Not found');
        die('not found');
    }

}
