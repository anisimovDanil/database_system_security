<?php
session_start();

if(!isset($_SESSION["session_fio"])):
	header("Location: http://localhost:8888/SSBD");
	session_destroy();
else:

$id = $_SESSION['session_user_id'];

function update_data($id, $name_db, $value_cell_db){
	require_once '../include/db_connect.php';
	$new_data = htmlspecialchars( trim( $_POST[$value_cell_db] ) );
	$datas = " SELECT * FROM $name_db WHERE user_id = '$id' ";
	$stmt = $link->prepare($datas);
	$stmt->execute();
	$search_data = $stmt->fetch(PDO::FETCH_OBJ);

	if($search_data){
		if($search_data->user_id == $id) {

	 		if($value_cell_db == 'password'){
	 			$update_pass = " UPDATE $name_db  SET $value_cell_db  = :new_pass WHERE user_id = '$id' ";
				$stmt = $link->prepare($update_pass);
				$stmt->execute([':new_pass' => md5($new_data)]);
	 		}
	 		else if($value_cell_db == 'username'){
				$update_username = " UPDATE $name_db SET $value_cell_db = :new_username, email = :new_email WHERE user_id = '$id' ";
				$new_username = [':new_username' => $new_data, ':new_email' => $new_data];
				$stmt = $link->prepare($update_username);
				$stmt->execute($new_username);
			}
	 		else{
	 			$update_data = " UPDATE $name_db SET $value_cell_db = :new_data WHERE user_id = '$id' ";
	 			$data = [':new_data' => $new_data];
				$stmt = $link->prepare($update_data);
				$stmt->execute($data);
			}
	  	echo "<span style='color:blue;'>Данные обновлены</span>";
	  }
	}
}

if(isset($_POST['password_button']))
	update_data($id, 'people_table', 'password');

if(isset($_POST['username_button']))
	update_data($id, 'people_table', 'username');

if(isset($_POST['fio_button'])){
	update_data($id, 'people_table', 'fio');
}

if(isset($_POST['address_button']))
	update_data($id, 'people_table', 'address');

if(isset($_POST['birthday_date_button']))
	update_data($id, 'more_people_info', 'birthday_date');

if(isset($_POST['about_button']))
	update_data($id, 'more_people_info', 'about');

if(isset($_POST['phone_number_button']))
	update_data($id, 'more_people_info', 'phone_number');

if(isset($_POST['company_button']))
	update_data($id, 'more_people_info', 'company');

if(isset($_POST['card_number_button']))
	update_data($id, 'yet_people_info', 'card_number');

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
	  	<p><a href="../pdo_exit.php">Выйти</a></p>
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