<?php
require '../config/config.php';

	$id = $_GET['id'];

	$pdostatement =$pdo->prepare("DELETE FROM user WHERE id='$id' ");
	$result = $pdostatement->execute();

header("location:user_list.php");
?>