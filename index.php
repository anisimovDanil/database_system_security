<?php
	session_start();
	require_once 'include/db_connect.php';
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

		$username = htmlspecialchars( trim($_POST['input_login']) );
		$pass = htmlspecialchars( trim($_POST['input_password']) );

		if(!empty($username) && !empty($pass)) {

			$pass = md5($pass);

			$query_user = " SELECT * FROM people_table WHERE username = :username AND password = :pass AND role = :role ";
			$data_user = [':username' => $username, ':pass' => $pass, ':role' => 'user'];
			$stmt = $link->prepare($query_user);
			$stmt->execute($data_user);

			$row = $stmt->fetch(PDO::FETCH_OBJ);


			if ($row) 
			{
 		
				if ($row->username == $username && $row->password == $pass && $row->role == 'user'){

					$_SESSION['session_user_id'] = $row->user_id;
					$_SESSION['session_username'] = $row->username;
					$_SESSION['session_fio'] = $row->fio;
					$_SESSION['session_role'] = $row->role;


					$query_username = " SELECT user_id FROM people_table WHERE username = :session_username ";
					$user_data = [':session_username' => $_SESSION['session_username']];
					$stmt = $link->prepare($query_username);
					$stmt->execute($user_data);


					$add_session = " INSERT INTO session SET id = :query_table, token = :state, date_time = NOW() ";
					$arguments_session = [':query_table' => $_SESSION['session_user_id'], ':state' => 'online'];
					$stmt = $link->prepare($add_session);
					$stmt->execute($arguments_session);
				}

			}


			$query_admin = " SELECT * FROM people_table WHERE username = :username AND password = :pass AND role = :role ";
			$data_admin = [':username' => $username, ':pass' => $pass, ':role' => 'admin'];
			$stmt = $link->prepare($query_admin);
			$stmt->execute($data_admin);

			$admin = $stmt->fetch(PDO::FETCH_OBJ);


			if ($admin) {
				if($admin->username == $username && $admin->password == $pass && $admin->role == 'admin') {
					$_SESSION['session_user_id'] = $admin->user_id;
					$_SESSION['session_username'] = $admin->username;
					$_SESSION['session_fio'] = $admin->fio;
					$_SESSION['session_role'] = $admin->role;

					$query_username = " SELECT user_id FROM people_table WHERE username = :session_username ";
					$user_data = [':session_username' => $_SESSION['session_username']];
					$stmt = $link->prepare($query_username);
					$stmt->execute($user_data);

					
					$add_session_admin = ' INSERT INTO session SET id = :query_table, token = :state, date_time = NOW() ';
					$arguments_session_admin = [':query_table' => $_SESSION['session_user_id'], ':state' => 'online'];
					$stmt = $link->prepare($add_session_admin);
					$stmt->execute($arguments_session_admin);
				}
			}

			if (!$user && !$admin) 
			{
				if($admin->username != $username && $admin->password != $pass && $row->username != $username && $row->password != $pass) {
				?>
					<script type="text/javascript">
						alert("Неверный логин или пароль.");
					</script>
				<?php
				}
			}
		} 	
	}
	?>
	<?php


	$query_0 = " SELECT session.id FROM session join people_table WHERE people_table.user_id = session.id ";
	$stmt = $link->prepare($query_0);
	$stmt->execute();

	$empty = $stmt->fetch(PDO::FETCH_OBJ);


	if($empty == false ||  $_SESSION['session_username'] == null) {
	echo '
	<header>
		<h1 class="main-title">Книжный магазин</h1>
		<div class="enter-and-sign_up">
			<div id="block-top-auth">
				<div class="corner">
					<form  сlass="main-form" method="POST">
						<ul class="main-points">
							<label>Логин</label>
							<li><input type="text" name="input_login" id = "input_login"></li>
							<label>Пароль</label>
							<li><input type="text" name="input_password" id = "input_password"></li>
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

	$query_1 = " SELECT session.id, people_table.role FROM session join people_table WHERE people_table.user_id = session.id and people_table.role = 'user' ";
	$stmt = $link->prepare($query_1);
	$stmt->execute();

	$input_user = $stmt->fetch(PDO::FETCH_OBJ);
	//print_r($input_user);
	//print_r($_SESSION['session_role']);

	if(($input_user == true || $_SESSION['session_username'] != null) && $_SESSION['session_role'] == 'user') {
		echo '
			<header>
				<h1 class="main-title">Книжный магазин</h1>
				<div class="enter-and-sign_up">
				<a href="user_panel/user_panel.php">Привет, ' . $_SESSION['session_fio'] . '! </a>
				<a href="pdo_exit.php">Выход</a>
				</div>
			</header>
			';
	}


	$query_2 = " SELECT session.id, people_table.role FROM session join people_table WHERE people_table.user_id = session.id and people_table.role = 'admin' ";
	$stmt = $link->prepare($query_2);
	$stmt->execute();

	$input_admin = $stmt->fetch(PDO::FETCH_OBJ);


	if(($input_admin == true || $_SESSION['session_username'] != null) && $_SESSION['session_role'] == 'admin') {					
		echo '
			<header>
				<h1 class="main-title">Книжный магазин</h1>
				<div class="enter-and-sign_up">
				<a href="admin/admin_panel.php">Welcome, ' . $_SESSION['session_fio'] . '! </a>
				<a href="pdo_exit.php">Выход</a>
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
	include("admin/db_connect.php"); 
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