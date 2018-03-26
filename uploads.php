<?php
	/**
	* Скрипт загрузки файлов
	* Site: http://bezramok-tlt.ru
	* PHP загрузка файлов на сервер
	*/

        include_once "savedata.php"; // это подключит savedata.php

	//Устанавливаем кодировку и вывод всех ошибок
        header('Content-Type: text/html; charset=UTF-8', true);
	
	//Каталог загрузки картинок
	$uploadDir = './image';
	
	//Вывод ошибок
	$err = array();
	
	//Коды ошибок загрузки файла
	$errUpload = array( 
						 0 => 'Ошибок не возникло, файл был успешно загружен на сервер. ', 
						 1 => 'Размер принятого файла превысил максимально допустимый размер, который задан директивой upload_max_filesize конфигурационного файла php.ini.', 
						 2 => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме.', 
						 3 => 'Загружаемый файл был получен только частично.', 
						 4 => 'Файл не был загружен.', 
						 6 => 'Отсутствует временная папка. Добавлено в PHP 4.3.10 и PHP 5.0.3.' 
					  ); 
					  
	//Определяем типы файлов для загрузки
	$fileTypes = array(
						 'jpg'  => 'image/jpg',
						 'jpeg' => 'image/jpeg',
						 'png'  => 'image/png',
						 'gif'  => 'image/gif'
						);
	
	//Если нажата кнопка загрузить
	if(isset($_POST['upload']))
	{
		//Проверяем пустые данные или нет
		if(!empty($_FILES))
		{
			//Проверяем на ошибки
			if($_FILES['files']['error'] > 0)
				$err[] = $errUpload[$_FILES['files']['error']];
				
			//Проверям тип файла для загрузки
			if(!in_array($_FILES['files']['type'], $fileTypes))
				$err[] = 'Данный тип файла <b>'. $_FILES['files']['type'] .'</b> не подходит для загрузки!';
			
			//Если нет ошибок то грузим файл
			if(empty($err))
			{
				$type = pathinfo($_FILES['files']['name']);
				$name = $uploadDir .'/'. uniqid('files_') .'.'. $type['extension'];
				move_uploaded_file($_FILES['files']['tmp_name'],$name);


                                $code='';
                                if(isset($_POST['code'])) $code=$_POST['code'];

                                $datafilename=SaveData($code,$name);
                                $_datalink='';

                                 if(isset($_POST['showdatalink'])) $_datalink='&datafilename='.$datafilename;

				//Сбрасываем POST параметры
                                header('Location: http://'. $_SERVER['HTTP_HOST'] .'/uploads.php?name='.$name.
                                  '&detail=1&filelist=1&host=1'.$_datalink, true);
				exit();
			}
			else {
				echo implode('<br>', $err);
                        }
		} 
	} 
	
	//Сообщение об успешной загрузке файла на сервер
	if(isset($_GET['name'])){
          if(isset($_GET['detail']))
		echo '<h1>Файл <font color="red">'. htmlentities($_GET['name']) .'</font> успешно загружен!</h1>';
          else 
            echo htmlentities($_GET['name']);
        }

        if(isset($_GET['filelist'])){
	  //Выводим картинки из каталога
	  $imgDir = array_values(array_diff(scandir($uploadDir), array('..', '.')));
	
	  for($i = 0; $i < count($imgDir); $i++)
	  {
		if($i % 2 == 0)
			echo '<br>'."\n";
		
		echo '<img src="'. $uploadDir .'/'. $imgDir[$i] .'">'."\n";
	  }
	
        }

        if(isset($_GET['host'])){
	  echo '<br /><br />'."\n";
	  echo '<a href="http://'. $_SERVER['HTTP_HOST'] .'" 
			title="http://'. $_SERVER['HTTP_HOST'] .'">
			http://'. $_SERVER['HTTP_HOST'] .'	</a>';
        }

        if(isset($_GET['datafilename'])){
	  echo '<br /><br />'."\n";
	  echo '<a href="'.$_GET['datafilename'].'">'.$_GET['datafilename'].'</a>';
        }

?>
