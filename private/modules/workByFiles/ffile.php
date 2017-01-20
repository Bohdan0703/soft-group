<?php

final class ffile extends finit
{
    /**
     * @link http://php.net/manual/ru/function.file.php
     * */
    public function file(){
        if($this->isFileExists()){
            $data = file($this->getFilePath());
            return $data;
        }
        return false;
    }
}