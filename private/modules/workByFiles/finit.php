<?php

abstract class finit implements fInterface
{
    private $filePath;
    private $file;
    private $isOpen;
    private $Path = [];

    function __construct($newFilePath, $open = false, array $params = [])
    {
        $this->setIsOpen(false)->setNewFilePath($newFilePath);
        if ($open === true) {
            $this->fopen($params);
        }
    }

    public function fopen(array $params = [])
    {
        if (inArrKey($params, 'mode') && isStr($params['mode'])) {
            if ($this->isFileExists()) {
                $this->file = fopen($this->filePath, $params['mode']);
                $this->setIsOpen(true);
                return true;
            }
        }
        return false;
    }

    public function fread(array $params = [])
    {
        if ($this->getIsOpen()) {
            $length = (inArrKey($params, 'length') && isInt($params['length'])) ? $params['length'] : filesize($this->filePath);
            if($length>0){
                $data = fread($this->file, $length);
                return $data;
            }
        }
        return false;
    }

    public function fwrite($value, array $params = [])
    {
        if ($this->getIsOpen() && isStr($value)) {
            fwrite($this->file, $value);
            return true;
        }
        return false;
    }


    protected function setIsOpen($value = false)
    {
        $this->isOpen = (bool)$value;
        return $this;
    }

    public function getIsOpen()
    {
        return $this->isOpen;
    }

    public function fclose(array $params = [])
    {
        if ($this->getIsOpen()) {
            fclose($this->file);
            $this->setIsOpen();
            return true;
        }
        return false;
    }

    public function setNewFilePath($newFilePath, $reload = false)
    {
        $this->filePath = (string)$newFilePath;
        if ($reload === true) {
            $this->fclose();
        }
        $this->validatePath();
        return $this;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @link http://php.net/manual/ru/function.stat.php
     * */
    private function validatePath()
    {
        $this->Path['fE'] = fE($this->filePath);
        if ($this->isFileExists()) {
            $fileInfo = stat($this->filePath);
            if ($fileInfo) {
                $this->Path['atime'] = $fileInfo['atime'];
                $this->Path['mtime'] = $fileInfo['mtime'];
                $this->Path['ctime'] = $fileInfo['ctime'];
                $this->Path['mode'] = $fileInfo['mode'];
            }
        }
        return $this;
    }

    public function isFileExists()
    {
        return $this->Path['fE'];
    }
}