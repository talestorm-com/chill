<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Content\MediaContent\FileList;

/**
 * Description of VideoFileList
 *
 * @author eve
 */
class VideoFileList extends FileList {

    //put your code here
    protected function get_file_list_table(): string {
        return 'media__content__cdn__file';
    }

}
