<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace controllers\FrontEnd\Helpers;

/**
 * Description of SoapAccessRequestor
 *
 * @author eve
 */
class SoapAccessRequestor {

    //put your code here

    public static function run(int $content_id, int $user_id) {
        
        $content_info = SoapAccessReader::run($content_id);
        $content_info?0: \Errors\common_error::R("not found");
        $user_monery = \Filters\FilterManager::F()->apply_chain(\DB\DB::F()->queryScalar("SELECT money FROM user__wallet WHERE id=:P ", [":P" => $user_id]), ['Float', 'Default0']);
        if ($user_monery < floatval($content_info['price'])) {
            \Errors\common_error::R("no_money");
        }
        $file_list = \Content\MediaContent\FileList\VideoFileList::F($content_id);
        if (!count($file_list)) {
            \Errors\common_error::R("no files");
        }
        $files_to_deprivate = [];
        $deadline = time() + (60 * 60 * 24);
        foreach ($file_list as $file) { /* @var $file  \Content\MediaContent\FileList\FileListItem */
            $request = \CDN_DRIVER\CDNTmpRequest::F();
            $request->run($file->cdn_id, $deadline);
            $files_to_deprivate[] = [
                'id' => $file->cdn_id,
                'size' => $file->size,
                'content_type' => $file->content_type,
                'url' => $request->link_result,
                'deadline' => $request->result_ttl,
            ];
        }       
        $builder = \DB\SQLTools\SQLBuilder::F();
        $rv = "@a" . md5(__METHOD__);
        $tid = $builder
                        ->push("INSERT INTO user__history(user_id,ts,action,param1,param2,amount) VALUES(
                        :P{$builder->c}user_id,
                        NOW(),
                        'payment_local',
                        'content',
                        :P{$builder->c}content_id,
                        :P{$builder->c}amount);")
                        ->push("SET {$rv} = LAST_INSERT_ID();")
                        ->push_params([
                            ":P{$builder->c}user_id" => $user_id,
                            ":P{$builder->c}content_id" => $content_id,
                            ":P{$builder->c}amount" => floatval($content_info['price']),
                        ])
                        ->inc_counter()
                        ->push("INSERT INTO media__content__user__access (media_id,user_id,deadline,links) VALUES(
                    :P{$builder->c}id,
                    :P{$builder->c}uid,
                    :P{$builder->c}ttl,
                    :P{$builder->c}links)                    
                    ON DUPLICATE KEY UPDATE deadline=VALUES(deadline),links=VALUES(links);")
                        ->push_params([
                            ":P{$builder->c}id" => $content_id,
                            ":P{$builder->c}uid" => $user_id,
                            ":P{$builder->c}ttl" => $deadline, ":P{$builder->c}links" => json_encode($files_to_deprivate),
                        ])
                        ->inc_counter()
                        ->push("UPDATE user__wallet SET money = money-:P{$builder->c}summ WHERE id=:P{$builder->c}user;")
                        ->push_params([
                            ":P{$builder->c}summ" => floatval($content_info['price']),
                            ":P{$builder->c}user" => $user_id,
                        ])->execute_transact($rv);
        return ['transaction_id'=>$tid,'links'=>$files_to_deprivate];      
    }

}
