<?php
	session_start();
	if(!isset($_SESSION["logged_in"]))
	{
		if(!isset($_COOKIE["key"]))
		{
			$_SESSION["logged_in"] = false;
		}
		else
		{
			include_once("crypt.php");
			$key = $_COOKIE["key"];
			$shift = ord($key{0}) - ord($key{strlen($key)});
			$email = "";
			for($i = 0; $i<strlen($key); $i++)
			{
				$email = $email . shift($key{$i}, $shift);
			}
			include_once("connect.php");
			$conn = dbConnect();
			$cmd = "select uid, email, coalesce(concat(first_name,' ',last_name), email) as \"name\", access_level, pw, active from users where email='" . $email . "';";
			$statement = $conn->prepare($cmd);
			$statement->execute();
			if($statement->rowCount() > 0)
			{
				$result = $statement->fetch();
				$_SESSION['uid'] = $result['uid'];
				$_SESSION['email'] = $result['email'];
				$_SESSION['uname'] = $result['name'];
				$_SESSION['access_level'] = $result['access_level'];
				$_SESSION["logged_in"] = true;
			}
			else
				$_SESSION["logged_in"] = false;
		}
	}
?>