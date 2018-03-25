<?php

  function SaveData($code,$imagename){  

    //Каталог загрузки данных
    $uploadDir = './data/';


    $filename = $uploadDir.uniqid('files_').'.txt'; //the name of our file.

    $content = 'Код: '.$code.PHP_EOL.'Картинка: '.$imagename; //what we will be writing to our file.

    $strlength = strlen($content); //gets the length of our $content string.
    $create = fopen($filename, "w"); //uses fopen to create our file.
    $write = fwrite($create, $content, $strlength); //writes our string to our file.
    $close = fclose($create); //closes our file

    return $filename;
  }
?>