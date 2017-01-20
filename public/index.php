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
    $load = loadModule('workByFiles', 'finit') && $load;

    $load = loadModule('workByFiles', 'ffile') && $load;
    $load = loadModule('workByFiles', 'fjsonfile') && $load;
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST && isset($_POST['add-new-film'])) {
        $data['name'] = isset($_POST['name']) && isStr($_POST['name']) ? $_POST['name'] : null;
        $data['country'] = isset($_POST['country']) && isArr($_POST['country']) ? $_POST['country'] : [];
        $data['genre'] = isset($_POST['genre']) && isArr($_POST['genre']) ? $_POST['genre'] : [];
        $data['year'] = isset($_POST['year']) && isInt((int)$_POST['year']) ? (int)$_POST['year'] : null;
        $data['budget'] = isset($_POST['budget']) && isInt((int)$_POST['budget']) ? (int)$_POST['budget'] : null;
        $data['author'] = isset($_POST['author']) && isStr($_POST['author']) ? $_POST['author'] : null;

        $valid = true;
        foreach ($data as $d) {
            if ($d == null) {
                $valid = false;
                break;
            }
        }
        if ($valid) {
            $fJsonFile->fopen(['mode' => 'w+']);

            $fJsonFile->fwriteJson($data);
        }
    }
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
    Рекомендую<a target="_blank" class="link"
                 href="https://chrome.google.com/webstore/detail/json-viewer/gbmdgpbipfallnflgajpaliibnhdgobh?hl=ru">
        JSON Viewer
    </a>
</p>

<form id="main-form" method="post" action="/public/" enctype="multipart/form-data">
    <label>
        {l:m:name}
        <input type="text" name="name" required>
    </label>
    <label>
        {l:m:country}
        <select multiple name="country[]" required>
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
        <select multiple name="genre[]" required>
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
        <select name="year" required>
            <?php
            foreach ($yearList as $year):
                $year = text2html($year);
                ?>
                <option value="<?= $year; ?>"><?= $year; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        {l:m:budget}
        <input type="text" name="budget" required>
    </label>
    <label>
        {l:m:author}
        <input type="text" name="author" required>
    </label>
    <input type="submit" value="{l:m:add new film}" name="add-new-film">
</form>


<p><a href="./files/txt/country-list.txt" class="link" target="_blank">./files/txt/country-list.txt</a></p>

<p><a href="./files/txt/genre-list.txt" class="link" target="_blank">./files/txt/genre-list.txt</a></p>

<p><a href="./files/txt/year-list.txt" class="link" target="_blank">./files/txt/year-list.txt</a></p>

<p><a href="files/txt/films.json" class="link" target="_blank">./files/txt/films.json</a></p>

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
            <div class="c c-6"><?= text2html(implode(', ', $film['country'])); ?></div>
            <div class="c c-6"><?= text2html(implode(', ', $film['genre'])); ?></div>
            <div class="c c-6"><?= text2html($film['year']); ?></div>
            <div class="c c-6"><?= text2html($film['budget']); ?></div>
            <div class="c c-6"><?= text2html($film['author']); ?></div>
        </div>
    <?php endforeach; ?>
</div>


<?php
$allFilmsCopy = $allFilms;
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
            <div class="c c-6"><?= text2html(implode(', ', $film['country'])); ?></div>
            <div class="c c-6"><?= text2html(implode(', ', $film['genre'])); ?></div>
            <div class="c c-6"><?= text2html($film['year']); ?></div>
            <div class="c c-6"><?= text2html($film['budget']); ?></div>
            <div class="c c-6"><?= text2html($film['author']); ?></div>
        </div>
    <?php endforeach; ?>
</div>
<form id="second-form" method="post" action="/public/" enctype="multipart/form-data">
    <label>
        {l:m:country}
        <select name="country" required>
            <?php
            foreach ($countryList as $country):
                $country = text2html($country);
                ?>
                <option value="<?= $country; ?>"><?= $country; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        {l:m:string}
        <input type="text" name="string">
    </label>

    <input type="submit" value="{l:m:go}" name="additional-tasks">
</form>

<?php
$avgBudget = 0;
$sumBudget = 0;
$f = false;
$allFilmsCopy = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST && isset($_POST['additional-tasks'])
        && isset($_POST['country']) && isStr($_POST['country'])
        && isset($_POST['string']) && isStr($_POST['string'])
    ) {
        $country = $_POST['country'];
        $string = $_POST['string'];
        $f = true;
        foreach ($allFilms as $film) {
            if (inArr($film['country'], $country)) {
                $sumBudget += $film['budget'];
                $avgBudget++;
            }
//            mb_strpos($film['name'], mb_substr($string, $i, 1)) !== false
//            $p = false;
            for ($i = 0; $i < strlen($string) - 1; $i++) {
//                if ($p) break;
                for ($j = 0; $j < strlen($film['name']) - 1; $j++) {
                    var_dump(($string[$i]),($film['name'][$j]));
                    echo '<br>';
                    if ($string[$i] == $film['name'][$j]) {
                        array_push($allFilmsCopy, $film);
//                        $p = true;
//                        break;
                    }
                }
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
$avgBudget = $avgBudget > 0 ? round($sumBudget / ($avgBudget), 2) : 0;
?>
<div class="table">
    <div class="r">
        <div class="title c c-1">{l:m:third-task}</div>
    </div>
    <div class="r">
        <div class="c c-2">{l:m:average-budget}<?= ($f) ? $_POST['country'] : ''; ?></div>
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
            <div class="c c-6"><?= text2html(implode(', ', $film['country'])); ?></div>
            <div class="c c-6"><?= text2html(implode(', ', $film['genre'])); ?></div>
            <div class="c c-6"><?= text2html($film['year']); ?></div>
            <div class="c c-6"><?= text2html($film['budget']); ?></div>
            <div class="c c-6"><?= text2html($film['author']); ?></div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
