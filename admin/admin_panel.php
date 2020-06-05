<!DOCTYPE html>
<html>
<head>
	<title>Admin_panel</title>
	<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
	<style type="text/css">
		button[type=submit] a{
			color: #000 !important;
			text-decoration: none;
		}
		.change p{
			margin: 0;
		}
		.change_book input{
			margin: 2px 7px 5px 0;
		}
	</style>
</head>
<body>
	<h1>Hello, admEn, what's up?</h1>

	<div id="open_panel_button">
		<button class = "open_users">Пользователи</button>
		<button class = "shop" type = "submit" name = "shop">Магазин</button>
		<button type="submit" name="main_page"><a href="http://localhost:8888/SSBD/index.php">На главную</a></button>
		<button type = "submit" name = "exit"><a href="../pdo_exit.php">Выход</a></button>
	</div>

	<script>
		$(document).ready(function(){
			$('.open_users').click(function(){
				$('.users_panel').slideToggle(0);      
				return false;
			});
		});

		$(document).ready(function(){
			$('.shop').click(function(){
				$('.shop_panel').slideToggle(0);      
				return false;
			});
		});


		$(document).ready(function () {
			$(".users_panel").hide();
		    $(".shop_panel").hide();
		    $(".open_users").click(function () {
		    	$(".shop_panel").hide();
		        });

		    	$(".shop").click(function () {
		        	$(".users_panel").hide();
		        });

		     });
	</script>

				<!-- ==========================================================================================================================-->
				<!-- ===================================================== "ПОЛЬЗОВАТЕЛИ" =====================================================-->
				<!-- ==========================================================================================================================-->

	<div class = "users_panel" id = "users_panel" style="display: none;">
		<?php
			session_start();

			$link = mysqli_connect('localhost', 'admin', '123456', 'db_shop');
			if (mysqli_connect_error()) {
				die('Ошибка подключения (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			} 
			mysqli_set_charset($link, "UTF-8");

			// УДАЛЕНИЕ ДАННЫХ О ПОЛЬЗОВАТЕЛЕ ИЗ БАЗЫ ДАННЫХ
			if(isset($_POST['delete_user_button'])) {
			    $login = mysqli_real_escape_string($link, $_POST['delete_user']);

			    $query = " SELECT * FROM people_table WHERE username ='$login' ";
				$data = mysqli_query($link, $query);

			 	if(mysqli_num_rows($data) == 1) {
				  	$query = " DELETE FROM people_table WHERE username ='$login' "; 
					mysqli_query($link, $query);
				    echo "<span style='color:blue;'>Данные удалены</span>";
				} 
			}


			// ДОБАВЛЕНИЕ ПОЛЬЗОВАТЕЛЯ В БАЗЫ ДАННЫХ
			/*if(isset($_POST['add_user_button'])) {
			    $username = mysqli_real_escape_string($link, $_POST['reg_username']);
			    $pass = mysqli_real_escape_string($link, $_POST['reg_pass']);
			    $pass = md5($pass);
			    $fio = mysqli_real_escape_string($link, $_POST['reg_fio']);
			    $role = mysqli_real_escape_string($link, $_POST['reg_role']);

			    if(!empty($username) && !empty($pass) && !empty($fio) && !empty($role)) {

				$query = " SELECT * FROM people_table WHERE username = '$username' ";
				$data = mysqli_query($link, $query);

					if(mysqli_num_rows($data) == 0) {

						$query = " INSERT INTO people_table (username, email, password, fio, role) VALUES ('$username', '$username', '$pass', '$fio', '$role') ";
						mysqli_query($link, $query);

						echo '<p>Регистрация прошла успешно!</p>';
						$username = NULL;
					}
					else echo 'Данный ник уже существует';
				}
			}*/

			// ПОИСК ПОЛЬЗОВАТЕЛЯ ПО ЗАПРОСУ В БАЗЕ ДАННЫХ
			if(isset($_POST['search_user_button'])) {
			    $search = mysqli_real_escape_string($link, $_POST['search_user']);
			    $search  = preg_replace('~\\\\+(?=["\'])~', '', $search);

			    if(!empty($search)) {
				$data = mysqli_query($link, $search);

					if(mysqli_num_rows($data) > 0) {

						$row = mysqli_fetch_array($data);
						echo "<br /><br />";
						echo "Результат поиска: <br />";
						do{
							echo $row['user_id'] . " | " . $row['username'] . " | " . $row['password'] . " | " . $row['fio'] . " | " . $row['address'] . " | " . $row['birthday_date'] . " | " .  $row['about'] . " | " . $row['phone_number'] . " | " . $row['company'] . " | " . $row['card_number'] . " | " . $row['amount'] . " | " . $row['role'] . " " . "<br />";
						} while($row = mysqli_fetch_array($data));
					}
				}
			}
		?>


		<br /><br />
		<form method="POST">
			<div class="change"><p style="margin: 0;" >Поиск среди пользователей:</p></div>
			<input type="text" name="search_user" placeholder="Введите запрос">
			<button type="submit" name="search_user_button">Готово</button>
		</form>


		<!--<br /><br />
		<form method="post" name="add_user">
			<div class="change"><p>Добавить пользователя:</p></div>
			<p><label>Логин: <input name="reg_username" type="text"></label></p> 
			<p><label>Пароль: <input name="reg_pass" type="text"></label></p>
			<p><label>ФИО: <input name="reg_fio" type="text"></label></p>
			<p><label>Роль: <input name="reg_role" type="text"></label></p>
			<button type="submit" name="add_user_button">Готово</button>
	 	</form>-->


	 	<br /><br />
		<form method="POST">
			<div class="change"><p style="margin: 0;">Удалить пользователя</p></div>
			<input type="text" name="delete_user" placeholder="Введите логин">
			<button type="submit" name="delete_user_button">Готово</button>
		</form>


		<br /><br />
		<div><p style="margin: 0;">Таблица сессий: </p></div>
			<?php
				$session = mysqli_query($link, "SELECT people_table.user_id, username, fio, role, id, token, date_time FROM people_table JOIN session WHERE people_table.user_id = session.id");
				if(mysqli_num_rows($session) > 0) {
					$row = mysqli_fetch_array($session);
					do{
						echo $row['user_id'] . " | " . $row['username'] . " | " . $row['fio'] . " | " . $row['role'] . " | " . $row['id'] . " | " .  $row['token'] . " | " . $row['date_time'] . " " . "<br />";
					}while($row = mysqli_fetch_array($session));
				}
			?>


		<br /><br />
		<div><p style="margin: 0;">Таблица пользователей: </p></div>

	<?php
		// ВЫВОД ВСЕХ ПОЛЬЗОВАТЕЛЕЙ  
		$all_clients = mysqli_query($link, "SELECT people_table.user_id, username, password, fio, address, birthday_date, about, phone_number, company, card_number, amount, role FROM people_table JOIN more_people_info JOIN yet_people_info WHERE people_table.user_id = more_people_info.user_id AND people_table.user_id = yet_people_info.user_id");
			

		if(mysqli_num_rows($all_clients) > 0) {
			$row = mysqli_fetch_array($all_clients);
			do{
				echo $row['user_id'] . " | " . $row['username'] . " | " . $row['password'] . " | " . $row['fio'] . " | " . $row['address'] . " | " . $row['birthday_date'] . " | " .  $row['about'] . " | " . $row['phone_number'] . " | " . $row['company'] . " | " . $row['card_number'] . " | " . $row['amount'] . " | " . $row['role'] . " " . "<br />";
			}while($row = mysqli_fetch_array($all_clients));
		}
	?>

	</div>


				<!-- =========================================================================================================================-->
				<!-- ======================================================= "МАГАЗИН" =======================================================-->
				<!-- =========================================================================================================================-->

	<div class = "shop_panel" id = "shop_panel" style="display: none">

	<?php

		// ПОИСК КНИГИ ПО ЗАПОРСУ В БАЗЕ ДАННЫХ
		if(isset($_POST['search_book_button'])) {
		   $search = mysqli_real_escape_string($link, $_POST['search_book']);
		   $search  = preg_replace('~\\\\+(?=["\'])~', '', $search);

			if(!empty($search)) {
				$data = mysqli_query($link, "$search");

				if(mysqli_num_rows($data) > 0) {
					$row = mysqli_fetch_array($data);
					echo "<br /><br />";
					echo "Результат поиска: <br />";
					do{
						echo $row['product_id'] . " | " . $row['product_name'] . " | " . $row['price'] . " | " . $row['image'] . " | " . $row['genre'] . " | " . $row['edition'] . " | " . $row['year'] . " | " . $row['warehouse_name'] . " | " . $row['count_product'] . " | " . $row['description'] . "  " . "<br />";
					}while($row = mysqli_fetch_array($data));
				}
			}
		}

		// УДАЛЕНИЕ КНИГИ ИЗ БАЗЫ ДАННЫХ
		if(isset($_POST['delete_book_button'])) {
		    $delete_book_title = mysqli_real_escape_string($link, $_POST['product_title']);
		    $delete_book_edition = mysqli_real_escape_string($link, $_POST['product_edition']);
		    $delete_book_year = mysqli_real_escape_string($link, $_POST['product_year']);

		    $get_id = mysqli_query($link, " SELECT table_products.product_id FROM table_products JOIN table_products_info WHERE table_products.product_name = '$delete_book_title' AND table_products_info.edition = '$delete_book_edition' AND table_products_info.year = '$delete_book_year' ");

		 	if(mysqli_num_rows($get_id) > 0) {

		 		$get_arr = mysqli_fetch_array($get_id);
				mysqli_query($link, " DELETE FROM table_products WHERE product_id = '$get_arr[0]' ");

			    echo "<span style='color:blue;'>Данные удалены</span>";
			} 
		}

		// ДОБАВЛЕНИЕ КНИГИ В БАЗУ ДАННЫХ
		if(isset($_POST['add_book_button'])) {
		    $title = mysqli_real_escape_string($link, $_POST['reg_title']);
		    $price = mysqli_real_escape_string($link, $_POST['reg_price']);
		    $img = mysqli_real_escape_string($link, $_POST['reg_img']);
		    $description = mysqli_real_escape_string($link, $_POST['reg_description']);
		    $genre = mysqli_real_escape_string($link, $_POST['reg_genre']);
		    $edition = mysqli_real_escape_string($link, $_POST['reg_edition']);
		    $year = mysqli_real_escape_string($link, $_POST['reg_year']);
		    $warehouse = mysqli_real_escape_string($link, $_POST['reg_warehouse']);
		    $count = mysqli_real_escape_string($link, $_POST['reg_count']);

		    if(!empty($title) && !empty($price) && !empty($img) && !empty($description) && !empty($genre) && !empty($edition) && !empty($year) && !empty($warehouse) && !empty($count)) {

				$query_check = " SELECT * FROM table_products join table_products_info WHERE table_products.product_name = '$title' and table_products_info.edition = '$edition' and table_products_info.year = '$year'";
				$data = mysqli_query($link, $query_check);

				if(mysqli_num_rows($data) == 0) {
					
					$query_0 = " INSERT INTO table_products (product_name, price, image, description, genre) VALUES ('$title', '$price', '$img', '$description', '$genre') ";
					mysqli_query($link, $query_0);


					$q = "SELECT product_id FROM table_products WHERE product_name = '$title' AND image = '$img'";
					$id = mysqli_fetch_array(mysqli_query($link, $q));

					$query_1 = " INSERT INTO table_products_info (product_id, edition, year) VALUES ('$id[0]', '$edition', '$year') ";
					mysqli_query($link, $query_1);

					$query_2 = " INSERT INTO warehouse (warehouse_name, product_id, count_product) VALUES ('$warehouse', '$id[0]', '$count') ";
					mysqli_query($link, $query_2);

					echo '<p>Информация о книге добавлена!</p>';
				}
				else echo 'Данная книга уже содержится в базе данных';
			}
		}

		// ФУНКУИЯ ДЛЯ ИЗМЕНЕНИЯ ИНФОРМАЦИИ О КНИГЕ
		function update_data($input_title, $input_edition, $input_year, $input_new_data, $table_1, $table_2, $table_3, $value_cell_db){
			include("db_connect.php"); 

			$title = mysqli_real_escape_string($link, trim($_POST[$input_title]));
			$edition = mysqli_real_escape_string($link, trim($_POST[$input_edition]));
			$year = mysqli_real_escape_string($link, trim($_POST[$input_year]));
			$new_data = mysqli_real_escape_string($link, trim($_POST[$input_new_data]));

			if(!empty($title) && !empty($edition) && !empty($year) && !empty($new_data)) {

				$query = " SELECT * FROM " . $table_1 . " JOIN " . $table_2 . " WHERE  " . $table_1 . ".product_name = '$title' AND " . $table_2 . ".edition = '$edition' AND " . $table_2 . ".year = '$year' ";
				$data = mysqli_query($link, $query);

			 	if(mysqli_num_rows($data) == 1) {

			 		$id = mysqli_fetch_array(mysqli_query($link, " SELECT table_products.product_id FROM " . $table_1 . " JOIN " . $table_2 . " WHERE table_products.product_name = '$title' AND table_products_info.edition = '$edition' AND table_products_info.year = '$year' "));

			 		$check_0 = mysqli_num_rows(mysqli_query($link, "SELECT " . $value_cell_db . " FROM " . $table_1 . " WHERE product_id = $id[0] "));
			 		$check_1 = mysqli_num_rows(mysqli_query($link, "SELECT " . $value_cell_db . " FROM " . $table_2 . " WHERE product_id = $id[0] "));
			 		$check_2 = mysqli_num_rows(mysqli_query($link, "SELECT " . $value_cell_db . " FROM " . $table_3 . " WHERE product_id = $id[0] "));


			 		if($check_0 == 1 || $check_1 == 0 || $check_2 == 0){
						mysqli_query($link, " UPDATE " . $table_3 . " SET " . $value_cell_db . " = '$new_data' WHERE product_id = $id[0] ");
						mysqli_query($link, " UPDATE " . $table_1 . " SET " . $value_cell_db . " = '$new_data' WHERE product_id = $id[0] ");
						mysqli_query($link, " UPDATE " . $table_2 . " SET " . $value_cell_db . " = '$new_data' WHERE product_id = $id[0] ");
			 		}

			        echo "<span style='color:blue;'>Данные обновлены</span>";
			 	}
		 	}
		}
		
		if(isset($_POST['product_name_button']))
			update_data('product_title', 'product_edition', 'product_year', 'new_product_title', 'table_products', 'table_products_info', 'warehouse', 'product_name');

		if(isset($_POST['price_button']))
			update_data('product_title', 'product_edition', 'product_year', 'new_price', 'table_products', 'table_products_info', 'warehouse', 'price');

		if(isset($_POST['img_button']))
			update_data('product_title', 'product_edition', 'product_year', 'new_img', 'table_products', 'table_products_info', 'warehouse', 'image');

		if(isset($_POST['description_button']))
			update_data('product_title', 'product_edition', 'product_year', 'new_description', 'table_products', 'table_products_info', 'warehouse', 'description');

		if(isset($_POST['genre_button']))
			update_data('product_title', 'product_edition', 'product_year', 'new_genre', 'table_products', 'table_products_info', 'warehouse', 'genre');	

		if(isset($_POST['edition_button']))
			update_data('product_title', 'product_edition', 'product_year', 'new_edition', 'table_products', 'table_products_info', 'warehouse', 'edition');

		if(isset($_POST['year_button']))
			update_data('product_title', 'product_edition', 'product_year', 'new_year', 'table_products', 'table_products_info', 'warehouse', 'year');

		if(isset($_POST['warehouse_button']))
			update_data('product_title', 'product_edition', 'product_year', 'new_warehouse', 'table_products', 'table_products_info', 'warehouse', 'warehouse_name');

		if(isset($_POST['count_button']))
			update_data('product_title', 'product_edition', 'product_year', 'new_count', 'table_products', 'table_products_info', 'warehouse', 'count_product');

	?>

	<!-- ПОИСК СРЕДИ КНИГ -->
	<br />
	<form method="POST">
		<div class="change"><p>Поиск среди книг:</p></div>
		<input type="text" name="search_book" placeholder="Введите запрос">
		<button type="submit" name="search_book_button">Готово</button>
	</form>

	<!-- ДОБАВЛЕНИЕ КНИГИ -->
	<br /><br />
	<form method="post" name="add_user">
		<div class="change"><p>Добавить книгу:</p></div>
		<p><label>Название: <input name="reg_title" type="text"></label></p> 
		<p><label>Цена: <input name="reg_price" type="text"></label></p>
		<p><label>Картинка: <input name="reg_img" type="text"></label></p>
		<p><label>Описание: <input name="reg_description" type="text"></label></p>
		<p><label>Жанр: <input name="reg_genre" type="text"></label></p>
		<p><label>Издательство: <input name="reg_edition" type="text"></label></p>
		<p><label>Год: <input name="reg_year" type="text"></label></p>
		<p><label>Название склада: <input name="reg_warehouse" type="text"></label></p>
		<p><label>Количество книг: <input name="reg_count" type="text"></label></p>
		<button type="submit" name="add_book_button">Готово</button>
	</form>

	<!-- УДАЛЕНИЕ КНИГИ -->
	<br /><br />
	<form method="POST" class="change_book">
		<div class="change"><p>Удалить книгу:</p></div>
		<input type="text" name="product_title" placeholder="Название книги">
		<input type="text" name="product_edition" placeholder="Издательство">
		<input type="text" name="product_year" placeholder="Год">
		<button type="submit" name="delete_book_button">Готово</button>
	</form>

	<!-- ИЗМЕНЕНЕНИЯ ДАННЫХ КНИГ -->
	<br /><br />
	<div><p style="margin: 0 0 7px 0;">Изменение книги:</p></div>

	<form method='POST' class="change_book">
		<div class="change"><p style="">Изменить название:</p></div>
		<input type="text" name="product_title" placeholder="Название книги">
		<input type="text" name="product_edition" placeholder="Издательство">
		<input type="text" name="product_year" placeholder="Год">
		<input type="text" name="new_product_title" placeholder="Новое название">
		<button type="submit" name="product_name_button" value="Готово">Готово</button>
	</form>

	<form method='POST' class="change_book">	
		<div class="change"><p>Изменить цену:</p></div>
		<input type="text" name="product_title" placeholder="Название книги">
		<input type="text" name="product_edition" placeholder="Издательство">
		<input type="text" name="product_year" placeholder="Год">
		<input type="text" name="new_price" placeholder="Новая цена">
		<button type="submit" name="price_button" value="Готово">Готово</button>
	</form>

	<form method='POST' class="change_book">
		<div class="change"><p>Изменить обложку:</p></div>
		<input type="text" name="product_title" placeholder="Название книги">
		<input type="text" name="product_edition" placeholder="Издательство">
		<input type="text" name="product_year" placeholder="Год">
		<input type="text" name="new_img" placeholder="Новая обложка">
		<button type="submit" name="img_button" value="Готово">Готово</button>
	</form>

	<form method='POST' class="change_book">
		<div class="change"><p>Изменить описание:</p></div>
		<input type="text" name="product_title" placeholder="Название книги">
		<input type="text" name="product_edition" placeholder="Издательство">
		<input type="text" name="product_year" placeholder="Год">
		<input type="text" name="new_description" placeholder="Новое описание">
		<button type="submit" name="description_button" value="Готово">Готово</button>
	</form>

	<form method='POST' class="change_book">
		<div class="change"><p>Изменить жанр:</p></div>
		<input type="text" name="product_title" placeholder="Название книги">
		<input type="text" name="product_edition" placeholder="Издательство">
		<input type="text" name="product_year" placeholder="Год">
		<input type="text" name="new_genre" placeholder="Новый жанр">
		<button type="submit" name="genre_button" value="Готово">Готово</button>
	</form>

	<form method='POST' class="change_book">
		<div class="change"><p>Изменить издательство:</p></div>
		<input type="text" name="product_title" placeholder="Название книги">
		<input type="text" name="product_edition" placeholder="Издательство">
		<input type="text" name="product_year" placeholder="Год">
		<input type="text" name="new_edition" placeholder="Новое издательство">
		<button type="submit" name="edition_button" value="Готово">Готово</button>
	</form>

	<form method='POST' class="change_book">
		<div class="change"><p>Изменить год:</p></div>
		<input type="text" name="product_title" placeholder="Название книги">
		<input type="text" name="product_edition" placeholder="Издательство">
		<input type="text" name="product_year" placeholder="Год">
		<input type="text" name="new_year" placeholder="Новый год издания">
		<button type="submit" name="year_button" value="Готово">Готово</button>
	</form>



	<br /><br />
	<div class="change"><p style="margin: 0 0 7px 0;">Изменение склада:</p></div>
	<form method='POST' class="change_book">
		<div class="change"><p style="">Название склада:</p></div>
		<input type="text" name="product_title" placeholder="Название книги">
		<input type="text" name="product_edition" placeholder="Издательство">
		<input type="text" name="product_year" placeholder="Год">
		<input type="text" name="new_warehouse" placeholder="Новый склад">
		<button type="submit" name="warehouse_button" value="Готово">Готово</button>
	</form>

	<form method='POST' class="change_book">
		<div class="change"><p style="">Количество товара: </p></div>
		<input type="text" name="product_title" placeholder="Название книги">
		<input type="text" name="product_edition" placeholder="Издательство">
		<input type="text" name="product_year" placeholder="Год">
		<input type="text" name="new_count" placeholder="Обновленное кол-во товара">
		<button type="submit" name="count_button" value="Готово">Готово</button>
	</form>


	<br /><br />
	<?php
		// вывод данных из таблицы с книгами
		$all_books = mysqli_query($link, "SELECT table_products_info.product_id, table_products.product_name, price, image, genre, edition, year, description  FROM table_products join table_products_info where table_products.product_id = table_products_info.product_id LIMIT 100");
		if(mysqli_num_rows($all_books) > 0) {
		$row = mysqli_fetch_array($all_books);
		do{

			if($row["image"] != "" && file_exists("../images/".$row["image"])){
           		$img_path = '../images/'.$row["image"];
           		$max_width = 200;
           		$max_height = 200;
           		list($width, $height) = getimagesize($img_path);
           		$ratioh = $max_height/$height;
           		$ratiow = $max_width/$width;
           		$ratio = min($ratioh, $ratiow);
           		$width = intval($ratio * $width);
           		$height = intval($ratio * $height);
           	}
           	else{
           		$img_path = "../images/no_photo.jpg";
            	$width = 127;
            	$height = 200;
           	}

			echo '<div class="block-images-grid">
	            		<img src="'. $img_path .'" width ="'.$width.'" height="'.$height.'"/>
	        </div>';
			echo $row['product_id'] . " | " . $row['product_name'] . " | " . $row['price'] . " | " . $row['image'] . " | " . $row['genre'] . " | " . $row['edition'] . " | " . $row['year'] . " | " . $row['description'] . "  " . "<br />";
			}while($row = mysqli_fetch_array($all_books));
		}

		mysqli_close($link);
	?>
	</div>
</body>
</html>