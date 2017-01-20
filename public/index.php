<?php
/**
 * @link http://php.net/manual/ru/migration56.new-features.php
 * */
require_once('./../conf.php');
require_once(DIR_PRIVATE . 'func.php');
$load = LOAD;

if ($load && $load = loadModule('workByFiles', 'fInterface')) {
    $load = loadModule('workByFiles', 'finit') && $load;

    $load = loadModule('workByFiles', 'ffile') && $load;
}
if (!$load) {
    http_response_code(404);
    die('Server error');
}
set_error_handler("showError");

//php 7
//($boo = new $booo)->callMethod([params]);
echo '<pre>';
var_dump($_POST);
echo '</pre>';
$genreList = ($fFile = new ffile(DIR_PUBLIC_FILES_TXT . 'genre-list.txt', true, ['mode' => 'r']))->file();
$yearList = $fFile->setNewFilePath(DIR_PUBLIC_FILES_TXT . 'year-list.txt')->file();
$countryList = $fFile->setNewFilePath(DIR_PUBLIC_FILES_TXT . 'country-list.txt')->file();
?>
<html>
<head>
    <title>Title</title>
    <link>
</head>
<body>
<form method="post" action="/public/" enctype="multipart/form-data">
    <label>
        {l:m:name}
        <input type="text" name="name">
    </label>
    <label>
        {l:m:country}
        <select multiple name="country[]">
            <?php
            foreach ($countryList as $country):
                $country = text2html($country);
                ?>
                <option value="<?= $country; ?>"><?= $country; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        {l:m:genre}
        <select multiple name="genre[]">
            <?php
                foreach ($genreList as $genre):
                    $genre = text2html($genre);
                ?>
                <option value="<?= $genre; ?>"><?= $genre; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        {l:m:year}
        <select name="year">
            <?php
            foreach ($yearList as $year):
                $year = text2html($year);
                ?>
                <option value="<?= $year; ?>"><?= $year; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        {l:m:author}
        <input type="text" name="author">
    </label>
    <input type="submit">
</form>
</body>
</html>
