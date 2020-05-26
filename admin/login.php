
<!DOCTYPE html>
<html>
<head>
	<title>Авторизация</title>
	<link rel="stylesheet" type="text/css" href="login_style.css">
</head>
<body>
	<div class="position-form">
		<form class="main-form" method="POST">
			<h1 class="main-title">Авторизация</h1>
			<ul class="main-points">
				<label>Логин</label>
				<li><input type="text" name="input_login" id = "input_login"></li>
				<label>Пароль</label>
				<li><input type="text" name="input_password" id = "input_password"></li>
				<!--<li><input type="checkbox" name="rememberme" id="rememberme"><label for="rememberme"> Запомнить меня</label></li>-->
			</ul>	
			<button type="submit" name="submit" id="submit"	>Вход</button>
		</form>
	</div>
</body>
</html>

<?php
	session_start();
	include("../include/db_connect.php");

	if(isset($_POST['submit'])) {

		$username = mysqli_real_escape_string($link, trim($_POST['input_login']));
		$pass = mysqli_real_escape_string($link, trim($_POST['input_password']));

		if(!empty($username) && !empty($pass)) {

			$query = " SELECT * FROM people_table WHERE username = '$username' AND password = '$pass' AND role = 'user' ";
			$data = mysqli_query($link, $query);
			
			if(mysqli_num_rows($data) == 1) {
				$user = mysqli_fetch_assoc($data);
				
				$_SESSION['session_fio'] = $user['fio'];// = $username;
				$_SESSION['session_user_id'] = $user['user_id'];
				$_SESSION['session_username'] = $user['username'];
				$_SESSION['session_username'] = $user['password'];
				$_SESSION['session_user_fio'] = $user['fio'];

				header ('Location: http://localhost:8888/SSBD/user_panel/user_panel.php');
			}
			else if(mysqli_num_rows(mysqli_query($link, " SELECT * FROM people_table WHERE username = '$username' AND password = '$pass' AND role = 'admin' ")) == 1){
				header ('Location: http://localhost:8888/SSBD/admin/admin_panel.php');
			}
			else {
				echo 'Неверный логин или пароль.';
			}
		} 	
	}
	mysqli_close($link);
?>







