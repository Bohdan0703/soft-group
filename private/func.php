<?php
function fE($filename, array $options = [])
{
    return file_exists($filename);
}

function isIsset(&$value, $f = null, array $params = [])
{
    if (isset($value)) {
        if (funcE($f)) {
            array_unshift($params, $value);
            if (!$r = call_user_func_array($f, $params)) {
                return null;
            } elseif (!is_bool($r)) {
                return $r;
            }
        }
        return $value;
    }
    return null;
}

function isStr($value)
{
    return is_string($value);
}

function isInt($value)
{
    return is_int($value);
}

function isArr($value)
{
    return is_array($value);
}

function toString($str)
{
    return (string)$str;
}

function toInt($value)
{
    return (int)$value;
}

function inArr($value, array $arr = [])
{
    return in_array($value, $arr) ? $value : false;
}

function inArrKey($key, array $arr = [])
{
    return array_key_exists($key, $arr);
}

function loadModule($dirName, $mName)
{
    if (fE($path = DIR_MODULES . $dirName . DS . $mName . '.php')) {
        require_once($path);
        return true;
    }
    return false;
}

function showError($errorLevel, $errorMessage,
                   $errorFile, $errorLine, $errorContext)
{
    static $stackErrors = [];
    array_push($stackErrors, func_get_args());
    if (IsDEBUG) {
        echo '<pre>';
        print_r(func_get_args());
        echo '</pre>';
    }
}

function text2html($value)
{
    $value = htmlspecialchars($value);
    $value = trim($value);
    return $value;
}

function funcE($funcName)
{
    return function_exists($funcName);
}

function cyrillicStringToArray($str)
{
    $cyrillic = [
        'й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ї', 'ф', 'і', 'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'є', 'я', 'ч', 'с', 'м', 'и', 'т', 'ь', 'б', 'ю',
        'Й', 'Ц', 'У', 'К', 'Е', 'Н', 'Г', 'Ш', 'Щ', 'З', 'Х', 'Ї', 'Ф', 'І', 'В', 'А', 'П', 'Р', 'О', 'Л', 'Д', 'Ж', 'Є', 'Я', 'Ч', 'С', 'М', 'И', 'Т', 'Ь', 'Б', 'Ю'
    ];
    $data = str_split($str, 2);
    $strResult = [];
    for ($i = 0; $i < count($data); $i++) {
        if (inArr($data[$i], $cyrillic)) {
            $strResult[] = $data[$i];
        } else {
            $tmp = str_split($data[$i]);
            foreach ($tmp as $v) {
                $strResult[] = $v;
            }
        }
    }
    return $strResult;
}

function getNIssetValue(&$value, $f = null, $params = [0 => ''])
{
    if (is_scalar($value)) {
        if (funcE($f)) {
            array_unshift($params, $value);
            return call_user_func_array($f, $params);
        } else return $value;
    }

    return (inArrKey(0, $params)) && is_scalar($params[0]) ? $params[0] : (new ErrorException());
}

