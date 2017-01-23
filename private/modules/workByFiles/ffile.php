<?php

final class ffile extends finit
{
    /**
     * @link http://php.net/manual/ru/function.file.php
     * */
    function __construct($newFilePath, array $params = [])
    {
        parent::__construct($newFilePath, count($params) > 0, $params);
    }

    public function file()
    {
        if ($this->isFileExists()) {
            $data = file($this->getFilePath());
            if (isArr($data)) {
                $data = array_map(function($line){
                    return trim($line);
                }, $data);
            }
            return $data;
        }
        return false;
    }
}