<?php 
	session_start();
	require_once 'include/db_connect.php';

	$id = $_SESSION['session_user_id'];

	$query = " SELECT * FROM session WHERE session.id = '$id' ";
	$stmt = $link->prepare($query);
	$stmt->execute();
	$search_query = $stmt->fetch(PDO::FETCH_OBJ);

	if($search_query) {
		if ($search_query->id == $id) {
			$query = " DELETE FROM session WHERE id = '$id' ";
			$stmt = $link->prepare($query);
			$stmt->execute();
		}			
	}

	session_destroy();

	header('Location: http://localhost:8888/SSBD');
?>