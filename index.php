<?php
	session_start();
	include("include/db_connect.php");
?>


<!DOCTYPE html>

<html>

<head>
	<title>Shop</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<?php 
	if(isset($_POST['submit'])) {

		$username = mysqli_real_escape_string($link, trim($_POST['input_login']));
		$pass = mysqli_real_escape_string($link, trim($_POST['input_password']));
		$pass = md5($pass);

		if(!empty($username) && !empty($pass)) {

			$query_user = " SELECT * FROM people_table WHERE username = '$username' AND password = '$pass' AND role = 'user' ";
			$data_user = mysqli_query($link, $query_user);

			if(mysqli_num_rows($data_user) == 1) {
				$user = mysqli_fetch_assoc($data_user);
				/*print_r($query_user);
				print_r("<br/>");
				print_r(mysqli_num_rows($data_user));
				print_r("<br/>");
				print_r($user[username]);
				print_r("<br/>");*/

				$_SESSION['session_user_id'] = $user['user_id'];
				$_SESSION['session_username'] = $user['username'];
				$_SESSION['session_fio'] = $user['fio'];

				$query_username = "SELECT user_id from people_table where people_table.username = '" . $user['username'] . "' ";
				//print_r($query_username . "<br />");				mysqli_query($link, $query_username);
				//print_r(mysqli_num_rows(mysqli_query($link, $query_username)) . "<br / >");

				//$query_1 = " INSERT INTO session SET id = (select user_id from people_table where people_table.username = '" . $user['username'] . "'), token = 'online', date_time = NOW()";
				$query_1 = " INSERT INTO session SET id = (select user_id from people_table where people_table.username = '" . $user['username'] . "'), token = 'online', date_time = NOW()";
				// print_r($query_1 . "<br / >");
				mysqli_query($link, $query_1);
			}

			$query_admin = " SELECT * FROM people_table WHERE username = '$username' AND password = '$pass' AND role = 'admin' ";
			$data_admin = mysqli_query($link, $query_admin);
			if(mysqli_num_rows($data_admin) == 1) {
				$user = mysqli_fetch_assoc($data_admin);

				$_SESSION['session_user_id'] = $user['user_id'];
				$_SESSION['session_username'] = $user['username'];
				$_SESSION['session_fio'] = $user['fio'];
				
				$query_1 = " INSERT INTO session SET id = (select user_id from people_table where people_table.username = '" . $user['username'] . "'), token = 'online', date_time = NOW()";
				mysqli_query($link, $query_1);
			}

			if(mysqli_num_rows($data_admin) == 0 && mysqli_num_rows($data_user) == 0){
			?>
				<script type="text/javascript">
					alert("Неверный логин или пароль.");
				</script>
			<?php
			}

		} 	
	}
	?>
	<?php
	if(mysqli_num_rows (mysqli_query($link, " SELECT session.id FROM session join people_table WHERE people_table.user_id = session.id ") ) == 0 || $_SESSION['session_username'] == null) {
	echo '
	<header>
		<h1 class="main-title">Книжный магазин</h1>
		<div class="enter-and-sign_up">
			<div id="block-top-auth">
				<div class="corner">
					<form  сlass="main-form" method="POST" style="/*display: none;*/">
						<ul class="main-points">
							<label>Логин</label>
							<li><input type="text" name="input_login" id = "input_login"></li>
							<label>Пароль</label>
							<li><input type="text" name="input_password" id = "input_password"></li>
							<!--<li><input type="checkbox" name="rememberme" id="rememberme"><label for="rememberme"> Запомнить меня</label></li>-->
						</ul>	
						<button type="submit" name="submit" id="submit">Вход</button>
						<a class="close" href="">Закрыть</a>
					</form>
				</div>
			</div>	

			<a href="#block-top-auth">Вход</a><!--<p id="enter"></p>-->
			<a href="reg/reg.php">Регистрация</a>
		</div>
	</header>
	';
	}
	else if(mysqli_num_rows (mysqli_query($link, " SELECT session.id FROM session join people_table WHERE people_table.user_id = session.id and people_table.role = 'user' ") ) > 0 && $_SESSION['session_username'] != null) {					
		echo '
			<header>
				<h1 class="main-title">Книжный магазин</h1>
				<div class="enter-and-sign_up">
				<a href="user_panel/user_panel.php">Привет, ' . $_SESSION['session_fio'] . '! </a>
				<a href="exit.php">Выход</a>
				</div>
			</header>
			';
	}
	else if(mysqli_num_rows (mysqli_query($link, " SELECT session.id FROM session join people_table WHERE people_table.user_id = session.id and people_table.role = 'admin' ") ) > 0 && $_SESSION['session_username'] != null) {					
		echo '
			<header>
				<h1 class="main-title">Книжный магазин</h1>
				<div class="enter-and-sign_up">
				<a href="admin/admin_panel.php">Welcome, ' . $_SESSION['session_fio'] . '! </a>
				<a href="exit.php">Выход</a>
				</div>
			</header>
			';
	}
	?>
							<!-- ===========================================================================================================================-->
							<!-- ======================================================= ВЫВОД КНИГ ======================================================= -->
							<!-- ===========================================================================================================================-->
<div class="product-choice">

	<ul class="block-product-grid">
	<?php
     	$result = mysqli_query($link, "SELECT * FROM table_products");
        if(mysqli_num_rows($result) > 0) {
        	$row = mysqli_fetch_array($result);
            do {

            	if($row["image"] != "" && file_exists("./images/".$row["image"])){
            		$img_path = './images/'.$row["image"];
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
            		//echo "The image indicated in the B / D is not displayed, because it is not in the folder";
            		$img_path = "./images/no_photo.jpg";
            		$width = 127;
            		$height = 200;
            	}

            	



	            echo'
	            	<li class="products-grid">
		            	<div class="block-images-grid">
		            		<img src="'. $img_path .'" width ="'.$width.'" height="'.$height.'"/>
		            	</div>
		            	<div class="products-grid-info">
			            	<p class="style-title-grid"><strong>'. $row["product_name"] .'</strong></p>
			            	<p class="description">'.$row["description"].'</p>
			            	<p class="add-cart-style-grid"><a href="">Купить</a></p>
			            	<p class="style-price-grid"><strong>'.$row["price"].'</strong> руб.</p>
		            	</div>
		            	
		            	
	            	</li>
	            ';
            }
            while ($row = mysqli_fetch_array($result));
        }

        echo '</ul>';
	?>


	<!--</ul>-->
</div>
</body>
</html>