<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Mutex;

abstract class AbstractMutex implements IMutex {

    protected $mutex_dir;
    protected $mutex_file_name;
    protected $file_handle = null;

    protected function __construct($file_spec) {
        $this->mutex_dir = \Config\Config::F()->MUTEX_DIR;
        $this->mutex_file_name = $this->mutex_dir . $this->prepare_mutex_name($file_spec) . ".mutex";
    }

    protected abstract function prepare_mutex_name($file_spec): string;

    /**
     * accure mutex (wait if need)
     * @throws \Exception
     */
    public function get() {
        try {
            if (!$this->file_handle) {
                $this->file_handle = fopen($this->mutex_file_name, "a+b");
                $this->file_handle ? false : MutexError::RF("cant get mutex handle `%s`", $this->mutex_file_name);
                if (!flock($this->file_handle, LOCK_EX)) {
                    MutexError::RF("cant get lock on mutex `%s`", $this->mutex_file_name);
                }                
            }
        } catch (\Exception $e) {
            $this->release();
            throw $e;
        }
    }

    /**
     * release mutex
     */
    public function release() {
        if ($this->file_handle) {
            flock($this->file_handle, LOCK_UN);
            fclose($this->file_handle);
            if (file_exists($this->mutex_file_name)) {
               // unlink($this->mutex_file_name);
            }
            $this->file_handle = null;
        }
    }

    /**
     * accure mutex if it is not buisy. else returns false
     * @return bool
     * @throws \Exception
     */
    public function get_if(): bool {
        try {
            if (!$this->file_handle) {
                $this->file_handle = fopen($this->mutex_file_name, "a+b");
                $this->file_handle ? false : MutexError::RF("cant get mutex handle `%s`", $this->mutex_file_name);
                if (flock($this->file_handle, LOCK_EX | LOCK_NB)) {                    
                    return true;
                }
                fclose($this->file_handle);
                $this->file_handle = null;
                return false;
            }
        } catch (\Exception $e) {
            $this->release();
            throw $e;
        }
    }

    public function __destruct() {
        $this->release();
    }

    /**
     * 
     * @param mixed $file_spec
     * @return \Mutex\AbstractMutex
     */
    public static function F($file_spec): IMutex {
        return new static($file_spec);
    }

}
