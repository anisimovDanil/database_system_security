<?php
session_start();
include("../include/db_connect.php"); 
if(!isset($_SESSION["session_fio"])):
	header("Location: http://localhost:8888/SSBD/admin/login.php");
	session_destroy();
else:

$users_id = $_SESSION['session_user_id'];

function update_data($users_id, $name_db, $value_cell_db){
	include("../include/db_connect.php"); 
	$new_data = mysqli_real_escape_string($link, $_POST[$value_cell_db]);
	$data = mysqli_query($link, " SELECT * FROM " . $name_db . " WHERE user_id = '$users_id' ");

 	if(mysqli_num_rows($data) == 1) {
		mysqli_query($link, " UPDATE " . $name_db . " SET " . $value_cell_db . " = '$new_data' WHERE user_id = '$users_id' ");
			if($value_cell_db == 'username')
				mysqli_query($link, " UPDATE " . $name_db . " SET email = '$new_data' WHERE user_id = '$users_id' ");
        echo "<span style='color:blue;'>Данные обновлены</span>";
 	}

}

if(isset($_POST['password_button']))
	update_data($users_id, 'people_table', 'password');

if(isset($_POST['username_button']))
	update_data($users_id, 'people_table', 'username');

if(isset($_POST['fio_button']))
	update_data($users_id, 'people_table', 'fio');

if(isset($_POST['address_button']))
	update_data($users_id, 'people_table', 'address');

if(isset($_POST['birthday_date_button']))
	update_data($users_id, 'more_people_info', 'birthday_date');

if(isset($_POST['about_button']))
	update_data($users_id, 'more_people_info', 'about');

if(isset($_POST['phone_number_button']))
	update_data($users_id, 'more_people_info', 'phone_number');

if(isset($_POST['company_button']))
	update_data($users_id, 'more_people_info', 'company');

if(isset($_POST['card_number_button']))
	update_data($users_id, 'yet_people_info', 'card_number');

mysqli_close($link);
endif; 
?>

<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		.welcome{
			display: flex;
			align-items: center;
		}
		.welcome a{
			color: #000 !important;
			margin-left: 5vmin;	
			text-decoration: none;
			padding: 9px 19px;	
			border: 1px solid black;
			background-color: #d1f059;
		}
		.welcome a:first-child{
			margin-right: -20px;
		}
		.change{
			display: flex;
			align-items: center;
		}
		.change p{
			margin: 0;
			margin-top: 20px;
		}
		button{
			padding: 1.5px 20px;
		}
	</style>
	<title>Shop</title>
</head>
<body>
	<div class="welcome">
		<h2>Добро пожаловать, <span><?php echo $_SESSION['session_fio'];?>! </span></h2>
		<p><a href="http://localhost:8888/SSBD/index.php">На главную</a></p>
	  	<p><a href="../exit.php">Выйти</a></p>
	</div>
	<form method='POST'>
		<div class="change"><p>Изменить пароль</p></div>
		<input type="text" name="password">
		<button type="submit" name="password_button" value="Готово">Готово</button>

		<div class="change"><p>Изменить логин</p></div>
		<input type="text" name="username">
		<button type="submit" name="username_button" value="Готово">Готово</button>

		<div class="change"><p>Изменить ФИО</p></div>
		<input type="text" name="fio">
		<button type="submit" name="fio_button" value="Готово">Готово</button>

		<div class="change"><p>Изменить адрес</p></div>
		<input type="text" name="address">
		<button type="submit" name="address_button">Готово</button>

		<div class="change"><p>Изменить дату рождения</p></div>
		<input type="text" name="birthday_date">
		<button type="submit" name="birthday_date_button">Готово</button>

		<div class="change"><p>Изменить информацию о себе</p></div>
		<input type="text" name="about">
		<button type="submit" name="about_button">Готово</button>

		<div class="change"><p>Изменить номер телефона</p></div>
		<input type="text" name="phone_number">
		<button type="submit" name="phone_number_button">Готово</button>

		<div class="change"><p>Изменить компанию</p></div>
		<input type="text" name="company">
		<button type="submit" name="company_button">Готово</button>

		<div class="change"><p>Изменить номер карты</p></div>
		<input type="text" name="card_number">
		<button type="submit" name="card_number_button">Готово</button>
	</form>
</body>
</html>