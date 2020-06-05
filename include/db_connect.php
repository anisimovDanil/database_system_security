<?php
	$host = 'localhost';
	$db_title = 'db_shop';
	$db_user = 'user';//	admin   |	user
	$db_pass = 'user';//	123456	|	user
	$charset = 'utf8';
	$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

	try {
		$link = new PDO("mysql:host=$host;dbname=$db_title;charset=$charset", $db_user, $db_pass, $options);
	} 
	catch (PDOException $e) {
		die ('Подключение не удалось!<br />' . $e->getMessage());
	}

	/*$result = $link->query('SELECT * FROM table_products');//db_shop.


	while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
		print_r($row['product_name'] . "<br/>");
	}*/

?>