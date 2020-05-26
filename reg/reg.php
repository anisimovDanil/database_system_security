<!DOCTYPE html>
<html>
<head>
	<title>Регистрация</title>
	<link rel="stylesheet" type="text/css" href="reg_style.css">
	<script type="text/javascript" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
</head>
<body>
<!-- ========================================= -->
<!-- ПРОВЕРКА ЗАПОЛНЕННОСТИ ОБЯЗАТЕЛЬНЫХ ПОЛЕЙ -->
<!-- ========================================= -->
	<script type="text/javascript">
		$(document).ready (function () {
		    $('button').bind('click', function() {
			    var name = $('#reg_fio').val ();
			    var login = $('#reg_username').val ();
			    var pass = $('#reg_pass').val ();
			    var address = $('#reg_address').val ();
			    var number = $('#reg_number').val ();
			    var fail = "";

			    if (name.length < 3)
			    	fail = "ФИО не менее 3 символов";
			    else if (login.split ('@').length - 1 == 0 || login.split ('.').length - 1 == 0) 
			    	fail = "Введен некорректный E-mail, в логине должны содержаться @ и .";
			    else if (pass.length < 5) 
			    	fail = "Пароль не менее 5 символов";
			    else if (address.length < 0) 
			    	fail = "Заполните поле адреса";
			    else if (number.length < 9) 
			    	fail = "Номер телефона должен быть из менее чем из 9 цифр";


			    if (fail != "") {
			    	alert(fail);
			    	return false;
			    }
		    });
   		});
	</script>


<!-- ================= -->
<!-- ФОРМА РЕГИСТРАЦИИ -->
<!-- ================= -->
	<div class="position-form">
		<form id="main-form" method="POST">
			<h1 class="main-title">Регистрация</h1>
			<ul class="main-points">
				<label>ФИО</label>
				<li><input type="text" name="reg_fio" id="reg_fio"></li>
				<label>Логин</label>
				<li><input type="text" name="reg_username" id="reg_username"></li>
				<label>Пароль</label>
				<li><input type="text" name="reg_pass" id="reg_pass"></li>
				<label>Адрес</label>
				<li><input type="text" name="reg_address" id="reg_address"></li>
				<label>Дата рождения</label>
				<li><input type="text" name="reg_birthday_date" id="birthday_date"></li>
				<label>О себе</label>
				<li><input type="text" name="reg_about" id="reg_about"></li>
				<label>Номер телефона</label>
				<li><input type="text" name="reg_number" id="reg_number"></li>
				<label>Компания</label>
				<li><input type="text" name="reg_company" id="reg_company"></li>
				<label>Номер карты</label>
				<li><input type="text" name="reg_card_number" id="reg_card_number"></li>
			</ul>
 			<button type="submit" name="submit" id="submit"	>Отправить</button>
		</form>
	</div>	


<!-- ========================== -->
<!-- ОТПРАВКА И ОБРАБОТКА ФОРМЫ -->
<!-- ========================== -->
<?php 
	session_start();
	include("../include/db_connect.php");

	if(isset($_POST['submit'])){
		$fio = mysqli_real_escape_string($link, trim($_POST['reg_fio']));
		$username = mysqli_real_escape_string($link, trim($_POST['reg_username']));
		$pass = mysqli_real_escape_string($link, trim($_POST['reg_pass']));
		$address = mysqli_real_escape_string($link, trim($_POST['reg_address']));
		$b_day = mysqli_real_escape_string($link, trim($_POST['reg_birthday_date']));
		$about = mysqli_real_escape_string($link, trim($_POST['reg_about']));
		$mob_num = mysqli_real_escape_string($link, trim($_POST['reg_number']));
		$company = mysqli_real_escape_string($link, trim($_POST['reg_company']));
		$card_number = mysqli_real_escape_string($link, trim($_POST['reg_card_number']));


		if(!empty($username) && !empty($pass) && !empty($fio) && !empty($address) && !empty($mob_num)) {
			$data = mysqli_query($link, " SELECT * FROM people_table WHERE username = '$username' ");

			if(mysqli_num_rows($data) == 0) {

				$pass = md5($pass);

				$id = $username . " " . $address;
				$id = md5($id);


				mysqli_query($link, " INSERT INTO more_people_info (user_id, birthday_date, about, phone_number, company) VALUES ('$id', '$b_day', '$about', '$mob_num', '$company') ");
				
				mysqli_query($link, " INSERT INTO yet_people_info (user_id, card_number) VALUES ('$id', '$card_number') ");

				mysqli_query($link," INSERT INTO people_table (user_id, username, email, password, fio, address, role) VALUES ('$id', '$username', '$username', '$pass', '$fio', '$address', 'user') ");

				echo '<p>Регистрация прошла успешно! Через <span id="time"></span> секунд вы будете перенаправлены на главную страницу.</p>';
				mysqli_close($link);
			}

			else echo 'Данный ник уже существует';
		}
	}
?>


<!-- ============== -->
<!-- ТАЙМЕР ОТСЧЕТА -->
<!-- ============== -->
<script>
	var i = 3;
	function time(){
		document.getElementById("time").innerHTML = i;
		i--;
	 	if (i < 0) location.href = "http://localhost:8888/SSBD";
	}

	time();
	setInterval(time, 1000);
</script>

<?php session_destroy();?>

</body>
</html>