<?php 
	session_start();
	include("include/db_connect.php"); 
	$id = $_SESSION['session_user_id'];
	$query_0 = " SELECT * FROM session WHERE session.id = '" . $id . "' ";
	$data = mysqli_query($link, $query_0);
	echo $_SESSION['username'];
		if(mysqli_num_rows($data) == 1) {
			$query_1 = " DELETE FROM session WHERE id = '" . $id . "' ";
			mysqli_query($link, $query_1);
		} 
	session_destroy();
	mysqli_close($link);
	header('Location: http://localhost:8888/SSBD');
?>