<?php

interface fInterface
{
    function getIsOpen();

    function fopen(array $params = []);

    function fread(array $params = []);

    function fwrite($value, array $params = []);

    function fclose(array $params = []);

    function setNewFilePath($newFilePath, $reload = false);

    function getFilePath();

    function isFileExists();
}