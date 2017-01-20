<?php

define('LOAD', version_compare(PHP_VERSION, '7.0.0', '>='));
define('IsDEBUG', true);
define('IsLOCAL', true);


define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS);

define('DIR_PRIVATE', ROOT . 'private' . DS);

define('DIR_PUBLIC', ROOT . 'public' . DS);
define('DIR_PUBLIC_FILES', ROOT . 'public' . DS . 'files' . DS);
define('DIR_PUBLIC_FILES_TXT', ROOT . 'public' . DS . 'files' . DS . 'txt' . DS);

define('DIR_MODULES', ROOT . 'private' . DS . 'modules' . DS);

