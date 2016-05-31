<?php
/**
 * Created by PhpStorm.
 * User: eugen
 * Date: 30.05.2016
 * Time: 22:44
 */

define('DS', DIRECTORY_SEPARATOR);
mb_internal_encoding("UTF-8");
echo __FILE__;

echo '<br>';

$politiciansFileResource = fopen('politicians.csv', 'a+');
$polititiansCompromatFiles = array();

while($row = fgetcsv($politiciansFileResource))
{
    $polititiansCompromatFiles[$row[0]] = $row[1];
}

$polititiansCompromats = array();
foreach ($polititiansCompromatFiles as $name => $file) {
    $path = __DIR__ . DS . 'compromat' . DS . $file;
    if(!file_exists($path))
    {
        continue;
    }
    $resource = fopen($path, 'r');
    $content = fread($resource, filesize($path));
    $polititiansCompromats[$name] = $content;
}


if(isset($_FILES["filename"]["tmp_name"]))
{
    $a = 0;
    // Проверяем загружен ли файл
    if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
    {
        // Если файл загружен успешно, перемещаем его
        // из временной директории в конечную
        move_uploaded_file($_FILES["filename"]["tmp_name"], __DIR__ . DS . "compromat" . DS . $_FILES["filename"]["name"]);
    } else {
        die("Ошибка загрузки файла");
    }
}

if(isset($_POST['text']) && isset($_POST['name']))
{
    //записать в файл с {name}.txt
    $fileId = uniqid();
    $fileName = $fileId . '.txt';
    if(!file_put_contents(__DIR__ . DS . "compromat" . DS . $fileName, $_POST['text']))
    {
        die('не получилось записать в файл');
    }
    fputcsv($politiciansFileResource, array($_POST['name'], $fileName), ',', '"');
}

?>

<h1>Компроматор</h1>
<?php foreach ($polititiansCompromats as $name => $content):?>
    <p>
        <strong><?=$name; ?></strong><br/>
        <?=$content; ?>
    </p>
<?php endforeach; ?>

<h2><p><b> Форма для загрузки файлов </b></p></h2>
<form method="post" enctype="multipart/form-data">
    <label>Имя</label><br>
    <input type="text" name="name"><br>
    <input type="file" name="filename"><br>
    <input type="submit" value="Загрузить"><br>
</form>

<h2><p><b> Текстовая форма </b></p></h2>
<form method="post">
    <label>Имя</label><br>
    <input type="text" name="name"><br>
    <label>Компромат</label><br>
    <textarea name="text" rows="20" cols="50"></textarea>
    <br>
    <input type="submit" value="Добавить"><br>
</form>

<h2>Файлы в директории</h2>
<ul>
<?php foreach (scandir(__DIR__) as $file):?>
    <li><?php echo $file; ?></li>
<?php endforeach; ?>
</ul>

<h2>Файлы в директории компроматов</h2>
<ul>
    <?php foreach (scandir(__DIR__ . DS . 'compromat') as $file):?>
        <li><?php echo $file; ?></li>
    <?php endforeach; ?>
</ul>

<h2>Файлы в директории c помощью dir()</h2>
<ul>
    <?php $dir = dir(__DIR__); ?>
    <?php while ($file = $dir->read()):?>
        <li><?php echo $file; ?></li>
    <?php endwhile; ?>
    <?php $dir->close(); ?>
</ul>

<h2>Текущая директория</h2>

<?php

// current directory
echo getcwd() . "<br/>";

chdir('compromat');

// current directory
echo getcwd() . "<br/>";

?>