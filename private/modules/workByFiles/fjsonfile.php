<?php

final class fJsonFile extends finit
{
    private $data = [];

    function __construct($newFilePath, array $params = [])
    {
        parent::__construct($newFilePath, count($params) > 0, $params);
    }

    public function fread(array $params = [])
    {
        if ($data = parent::fread($params)) {
            if (funcE('json_decode')) {
                $this->data = json_decode($data, true);
                return $data;
            }
        }
        return false;

    }

    public function fwriteJson(array $data, array $params = [])
    {
        if (funcE('json_encode')) {
            array_push($this->data, $data);

            $json = json_encode($this->data, JSON_UNESCAPED_UNICODE);
            parent::fwrite($json, $params);

            return true;
        }
        return false;
    }

    public function getData(){
        return $this->data;
    }
//    private function fwrite(array $params = []){}

}