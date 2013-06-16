<?php
	session_start();
	include_once("connect.php");
	$conn = dbConnect();
	$cmd = "insert into favorites (uid, cid) values (" . $_SESSION['uid'] . "," . $_GET['cid'] . ");";
	$statement = $conn->prepare($cmd);
	$statement->execute();
	$cmd = "select fid from favorites where cid = " . $_GET['cid'] . " and uid = " . $_SESSION['uid'] . ";";
	$statement = $conn->prepare($cmd);
	$statement->execute();
	$result = $statement->fetch();
	echo($result['fid']);
?>