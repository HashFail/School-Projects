<?php
	include_once("connect.php");
	$conn = dbConnect();
	$cmd = "delete from content where cid = ".$_GET['cid'];
	$statement = $conn->prepare($cmd);
	$statement->execute();
?>