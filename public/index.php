<?php
/**
 * @link http://php.net/manual/ru/migration56.new-features.php
 * @link http://php.net/manual/ru/function.fopen.php
 * */
error_reporting(E_ALL);
require_once('./../conf.php');

require_once(DIR_PRIVATE . 'func.php');


$load = LOAD;

if ($load && $load = loadModule('workByFiles', 'fInterface')) {
    $load = $load && loadModule('workByFiles', 'finit');

    $load = $load && loadModule('workByFiles', 'ffile');
    $load = $load && loadModule('workByFiles', 'fjsonfile');
}
if (!$load) {
    http_response_code(404);
    die('Server error');
}
set_error_handler("showError");

//php 7
//($boo = new $booo)->callMethod([params]);
$genreList = ($fFile = new ffile(DIR_PUBLIC_FILES_TXT . 'genre-list.txt'))->file();
$yearList = $fFile->setNewFilePath(DIR_PUBLIC_FILES_TXT . 'year-list.txt')->file();
$countryList = $fFile->setNewFilePath(DIR_PUBLIC_FILES_TXT . 'country-list.txt')->file();

$fJsonFile = new fJsonFile(DIR_PUBLIC_FILES_TXT . 'films.json');
$fJsonFile->fopen(['mode' => 'a+']);
$fJsonFile->fread();
$formData = [];
$getData = [];

switch ($_SERVER['REQUEST_METHOD']) {
    case "POST":
        $formData = $_POST;
        if (isset($_POST['add-new-film'])) {
            $valid = true;

            $data['name'] = isIsset($formData['name'], 'isStr');
            $data['budget'] = isIsset($formData['budget'], 'toInt');
            if(!$data['budget']){
                $valid = false;
            }
            if ($data['name']) {
                if (isIsset($formData['country'], 'isArr')) {
                    $data['country'] = [];
                    foreach ($formData['country'] as $c) {
                        if (isIsset($c, 'InArr', [$countryList])) {
                            array_push($data['country'], $c);
                        }
                    }
                } else $valid = false;
                if (isIsset($formData['genre'], 'isArr')) {
                    $data['genre'] = [];
                    foreach ($formData['genre'] as $c) {
                        if (isIsset($c, 'InArr', [$genreList])) {
                            array_push($data['genre'], $c);
                        }
                    }
                } else $valid = false;
                if (isIsset($formData['year'], 'toInt') && inArr($formData['year'], $yearList)) {
                    $data['year'] = isIsset($formData['year'], 'toInt');
                } else $valid = false;
            } else $valid = false;

            $data['author'] = isIsset($formData['author'], 'isStr');
            if ($valid) {
                $fJsonFile->fopen(['mode' => 'w+']);

                $fJsonFile->fwriteJson($data);
                header('Location:' . '/public/');
            }
        }
        break;
    case "GET":
        $getData = $_GET;
    default:
        // <------
}

$allFilms = $fJsonFile->getData();
?>
<html>
<head>
    <title>Title</title>
    <link href="./files/css/style.css" rel="stylesheet">
</head>
<body id="page">
<p>
    {l:m:recommend}<a target="_blank" class="link"
                      href="https://chrome.google.com/webstore/detail/json-viewer/gbmdgpbipfallnflgajpaliibnhdgobh?hl=ru">
        {l:m:JSONViewerName}
    </a>
</p>

<form id="main-form" method="post" action="/public/" enctype="multipart/form-data">
    <label>
        {l:m:name}
        <input type="text" name="name" required value="<?= getNIssetValue($data['name'], 'text2html') ?>">
    </label>
    <label>
        {l:m:country}
        <select multiple name="country[]" required>
            <?php
            foreach ($countryList as $country):
                $country = text2html($country);
                ?>
                <option
                    value="<?= $country; ?>"
                    <?= isIsset($formData['country']) && inArr($country, $formData['country']) ? 'selected' : '' ?>
                    ><?= $country; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        {l:m:genre}
        <select multiple name="genre[]" required>
            <?php
            foreach ($genreList as $genre):
                $genre = text2html($genre);
                ?>
                <option value="<?= $genre; ?>"
                    <?= isIsset($formData['genre']) && inArr($genre, $formData['genre']) ? 'selected' : '' ?>
                    ><?= $genre; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        {l:m:year}
        <select name="year" required>
            <?php
            foreach ($yearList as $year):
                $year = text2html($year);
                ?>
                <option value="<?= $year; ?>"
                    <?= getNIssetValue($formData['year'], 'inArr', [$yearList]) ? 'selected' : '' ?>
                    ><?= $year; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        {l:m:budget}
        <input type="text" name="budget" required value="<?= getNIssetValue($data['budget'], 'text2html') ?>">
    </label>
    <label>
        {l:m:author}
        <input type="text" name="author" required value="<?= getNIssetValue($data['author'], 'text2html') ?>">
    </label>
    <input type="submit" value="{l:m:add new film}" name="add-new-film">
</form>


<p><a href="./files/txt/country-list.txt" class="link" target="_blank">./files/txt/country-list.txt</a></p>

<p><a href="./files/txt/genre-list.txt" class="link" target="_blank">./files/txt/genre-list.txt</a></p>

<p><a href="./files/txt/year-list.txt" class="link" target="_blank">./files/txt/year-list.txt</a></p>

<p><a href="./files/txt/films.json" class="link" target="_blank">./files/txt/films.json</a></p>

<div class="table">
    <div class="r">
        <div class="title c c-1">{l:m:first-task}</div>
    </div>
    <div class="r">
        <div class="title c c-6">{l:m:name}</div>
        <div class="title c c-6">{l:m:country}</div>
        <div class="title c c-6">{l:m:genre}</div>
        <div class="title c c-6">{l:m:year}</div>
        <div class="title c c-6">{l:m:budget}</div>
        <div class="title c c-6">{l:m:author}</div>
    </div>
    <?php foreach ($allFilms as $film): ?>
        <div class="r">
            <div class="c c-6"><?= text2html($film['name']); ?></div>
            <div
                class="c c-6"><?php isArr($film['country']) && print text2html(implode(', ', $film['country'])); ?></div>
            <div class="c c-6"><?php isArr($film['genre']) && print text2html(implode(', ', $film['genre'])); ?></div>

            <div class="c c-6"><?= getNIssetValue($film['year'],'text2html'); ?></div>
            <div class="c c-6"><?= getNIssetValue($film['budget'],'text2html'); ?></div>
            <div class="c c-6"><?= getNIssetValue($film['author'],'text2html'); ?></div>
        </div>
    <?php endforeach; ?>
</div>


<?php
$allFilmsCopy = $allFilms;
$budget = [];
foreach ($allFilmsCopy as $key => $row) {
    $budget[$key] = $row['budget'];
}
array_multisort($budget, SORT_DESC, $allFilmsCopy);
?>

<div class="table">
    <div class="r">
        <div class="title c c-1">{l:m:second-task}</div>
    </div>
    <div class="r">
        <div class="title c c-6">{l:m:name}</div>
        <div class="title c c-6">{l:m:country}</div>
        <div class="title c c-6">{l:m:genre}</div>
        <div class="title c c-6">{l:m:year}</div>
        <div class="title c c-6">{l:m:budget}</div>
        <div class="title c c-6">{l:m:author}</div>
    </div>
    <?php foreach ($allFilmsCopy as $film): ?>
        <div class="r">
            <div class="c c-6"><?= text2html($film['name']); ?></div>
            <div
                class="c c-6"><?php isArr($film['country']) && print text2html(implode(', ', $film['country'])); ?></div>
            <div class="c c-6"><?php isArr($film['genre']) && print text2html(implode(', ', $film['genre'])); ?></div>
            <div class="c c-6"><?= getNIssetValue($film['year'],'text2html'); ?></div>
            <div class="c c-6"><?= getNIssetValue($film['budget'],'text2html'); ?></div>
            <div class="c c-6"><?= getNIssetValue($film['author'],'text2html'); ?></div>
        </div>
    <?php endforeach; ?>
</div>
<form id="second-form" method="get" action="/public/" enctype="multipart/form-data">
    <label>
        {l:m:country}
        <select name="country" required>
            <?php
            foreach ($countryList as $country):
                $country = getNIssetValue($country, 'text2html');
                ?>
                <option value="<?= $country; ?>"><?= $country; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        {l:m:string}
        <input type="text" name="string" value="<?= getNIssetValue($getData['string'], 'text2html'); ?>">
    </label>

    <input type="submit" value="{l:m:go}" name="additional-tasks">
</form>

<?php
$avgBudget = 0;
$sumBudget = 0;
$f = false;
$allFilmsCopy = [];
if (isset($getData['additional-tasks'])
    && getNIssetValue($getData['country'], 'isStr')
    && getNIssetValue($getData['string'], 'isStr')
) {
    $country = $getData['country'];
    $string = $getData['string'];
    $f = true;

    $Letters = cyrillicStringToArray($string);

    foreach ($allFilms as $film) {
        if (inArr($country, $film['country'])) {
            $sumBudget += $film['budget'];
            $avgBudget++;
        }

        $nameLetters = cyrillicStringToArray($film['name']);

        foreach ($Letters as $l) {
            if (inArr($l, $nameLetters)) {
                array_push($allFilmsCopy, $film);
                break;
            }
        }
    }
}
if (!$f) {
    foreach ($allFilms as $film) {
        $sumBudget += $film['budget'];
        $avgBudget++;
    }
}
$avgBudget = $avgBudget > 0 ? round($sumBudget / ($avgBudget), 2) : '-';
?>
<div class="table">
    <div class="r">
        <div class="title c c-1">{l:m:third-task}</div>
    </div>
    <div class="r">
        <div class="c c-2">{l:m:average-budget}<?= ($f) ? $_GET['country'] : ''; ?></div>
        <div class="c c-2"><?= $avgBudget; ?></div>
    </div>
</div>
<div class="table">
    <div class="r">
        <div class="title c c-1">{l:m:last-task}</div>
    </div>
    <div class="r">
        <div class="title c c-6">{l:m:name}</div>
        <div class="title c c-6">{l:m:country}</div>
        <div class="title c c-6">{l:m:genre}</div>
        <div class="title c c-6">{l:m:year}</div>
        <div class="title c c-6">{l:m:budget}</div>
        <div class="title c c-6">{l:m:author}</div>
    </div>
    <?php foreach ($allFilmsCopy as $film): ?>
        <div class="r">
            <div class="c c-6"><?= text2html($film['name']); ?></div>
            <div
                class="c c-6"><?php isArr($film['country']) && print text2html(implode(', ', $film['country'])); ?></div>
            <div class="c c-6"><?php isArr($film['genre']) && print text2html(implode(', ', $film['genre'])); ?></div>
            <div class="c c-6"><?= getNIssetValue($film['year'],'text2html'); ?></div>
            <div class="c c-6"><?= getNIssetValue($film['budget'],'text2html'); ?></div>
            <div class="c c-6"><?= getNIssetValue($film['author'],'text2html'); ?></div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
