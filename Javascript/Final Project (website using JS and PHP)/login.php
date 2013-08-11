<?php
	session_start();
	include_once("scripts/crypt.php");
	include_once("scripts/connect.php");
	$message = "";
	if(isset($_POST['email']))
	{
		$conn = dbConnect();
		$cmd = "select uid, email, coalesce(concat(first_name,' ',last_name), email) as \"name\", access_level, pw, active from users where email='" . $_POST['email'] . "';";
		$statement = $conn->prepare($cmd);
		$statement->execute();
		if($statement->rowCount() == 0)
			$message = "<h1 class=\"alert\">No account was found with email address matching " . $_POST['email'] . "</h1>";
		else
		{
			$result = $statement->fetch();
			if($result['active'] == 0)
			{
				$message = "<h1 class=\"alert\">Your account is not active. You may not have activated your account or your account may have been disabled.</h1>";
			}
			else if($result['pw'] === encryptPassword($_POST['pw'], $_POST['email']))
			{
				$_SESSION['uid'] = $result['uid'];
				$_SESSION['email'] = $result['email'];
				$_SESSION['uname'] = $result['name'];
				$_SESSION['access_level'] = $result['access_level'];
				if($_POST['remember'] == "true")
				{
					$value = "";
					$shift = rand(1,10);
					for($i = 0;$i<strlen($_SESSION['email']);$i++)
					{
						$value = $value . shift($value{$i}, $shift);
					}
					$value = $value . shift($value{0}, $shift);
				}
				setcookie("key",$value);
				$_SESSION['logged_in'] = true;
				header("Location: index.php");
				die();
			}
			else
			{
				$message = "<h1 class=\"alert\">The password you provided does not match the one we associated with this account.</h1>";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Abe Ariel Photography</title>
		<link rel="stylesheet" type="text/css" href="scripts/mainstyle.css" />
		<link rel="stylesheet" type="text/css" href="scripts/loginStyle.css" />
		<style type="text/css">
			#login{display:none;}
		</style>
		<script type="text/javascript" src="scripts/mainjs.js"></script>
	</head>
	<body>
			<div class="parentDiv" id="leftMargin">
				<?php
					include_once("scripts/leftInit.php");
				?>
			</div>
			<div class="parentDiv" id="center">
				<?php
					echo($message);
					echo ("
					<div id=\"contentHolder\">
						<h1 class=\"message\">Log in: </h1>
						<h1 id=\"formContainer\">
							<form method=\"post\" action=\"login.php\">
								Email Address: <input type=\"text\" name=\"email\" /><br />
								Password: <input type=\"password\" name=\"pw\" /><br />
								<!--Remember me: <input type=\"checkbox\" value=\"true\" name=\"remember\" /><br />-->
								<div style=\"text-align:center\"><input type=\"submit\" value=\"Log in\" /></div>
							</form>
						</h1>
						<a href=\"signup.php\"id=\"switch\">Don't have an account? Sign up here.</a>
					</div>");
				?>
			</div>
			<div class="parentDiv" id="rightMargin">
				<?php
					include_once("scripts/rightInit.php");
				?>
			</div>
	</body>
</html>