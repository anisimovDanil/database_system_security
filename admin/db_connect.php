<?php
	$link = mysqli_connect('localhost', 'root', 'root', 'db_shop');
	if (mysqli_connect_error()) {
	 die('Ошибка подключения (' . mysqli_connect_errno() . ') '
	  . mysqli_connect_error());
	} 
	mysqli_set_charset($link, "UTF-8");
?>