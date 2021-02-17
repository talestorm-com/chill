<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ImageFly;

/**
 * Description of ImageFlyContextHandlerAvatar
 *
 * @author eve
 */
class ImageFlyContextHandlerAvatar extends ImageFlyContextHandler {

    //put your code here
    public static function on_source_not_found(string $source_path, string $context, string $owner_id, string $image_name): bool {
        $user_info = \Auth\UserInfo::F(intval($owner_id));
        if ($user_info && $user_info->valid && $image_name==="aaca0f5eb4d2d98a6ce6dffa99f8254b") { // md5(avatar)
            $image_path = \Config\Config::F()->IMAGE_STORAGE_PATH . "{$context}/{$owner_id}";
            if (!(file_exists($image_path) && is_dir($image_path) )) {
                @mkdir($image_path, 0777, true);
            }
            if ((file_exists($image_path) && is_dir($image_path))) {
                try {
                    AvatarGen::F()->mk_avatar($user_info, $source_path);
                    return true;
                } catch (\Throwable $e) {
                    
                }
            }
        }
        return false;
    }

}
