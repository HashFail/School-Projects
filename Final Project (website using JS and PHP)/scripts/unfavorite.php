<?php
	include_once("connect.php");
	$conn = dbConnect();
	$cmd = "delete from favorites where fid = " . $_GET['fid'] . ";";
	$statement = $conn->prepare($cmd);
	$statement->execute();
?>