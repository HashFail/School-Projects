<?php
	session_start();
	$message = "";
	$done = false;
	if(isset($_POST['email']))
	{
		include_once("scripts/connect.php");
		include_once("scripts/crypt.php");
		$conn = dbConnect();
		$cmd = "select email from users where email = '" . $_POST['email']. "';";
		$statement = $conn->prepare($cmd);
		$statement->execute();
		if($statement->rowCount() > 0)
			$message = "An account with that email already exists.";
		else
		{
			if(isset($_POST['lname']) && isset($_POST['fname']))
				$cmd = "insert into users (email, access_level, last_name, first_name, pw, active) values ('". $_POST['email'] ."', 'basic', '" . $_POST['lname'] ."', '" . $_POST['fname']. "', '" . encryptPassword($_POST['pw'], $_POST['email']) ."', 1)";	
			else
				$cmd = "insert into users (email, access_level, pw, active) values ('". $_POST['email'] ."', 'basic', '" . encryptPassword($_POST['pw'], $_POST['email']) ."', 1)";
			$statement = $conn->prepare($cmd);
			$statement->execute();
			$content = "You have been successfully registered. ";
			if(!mail($_POST['email'], "Registration confirmation", "You have been registered."))
			{
				$content = "An error occured when sending the email.";
			}
			$done = true;
		}
	}
	if(!$done)
	{
		$content = "
		<div id=\"contentHolder\">
			<h1 class=\"message\">Sign up: </h1>
			<h1 id=\"formContainer\">
				<form id=\"form\" method=\"post\" action=\"signup.php\">
					<span id=\"emailMessage\">Email Address: </span><input type=\"text\" id=\"email\" name=\"email\" /><br />
					<span id=\"pwMessage\">Password (between 8 and 16 characters): </span><input type=\"password\" id=\"pw\" name=\"pw\" /><br />
					<span id=\"pw2Message\">Confirm password: </span><input type=\"password\" id=\"pw2\" name=\"pw2\" /><br />
					First Name (optional): <input type=\"text\" name=\"fname\" /><br />
					Last Name (optional): <input type=\"text\" name=\"lname\" /><br />
					<div style=\"text-align:center\"><input onclick=\"checkSubmit();\"type=\"button\" value=\"Sign up\" /></div>
				</form>
			</h1>
			<a href=\"login.php\"id=\"switch\">Already have an account? Log in here.</a>
		</div>";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Abe Ariel Photography</title>
		<link rel="stylesheet" type="text/css" href="scripts/mainstyle.css" />
		<link rel="stylesheet" type="text/css" href="scripts/loginStyle.css" />
		<style type="text/css">
			#signup{display:none;}
		</style>
		<script type="text/javascript" src="scripts/mainjs.js"></script>
		<script type="text/javascript">
			function checkSubmit()
			{
				var valid = true;
				if(!isEmail(document.getElementById("email").value))
				{
					document.getElementById("emailMessage").className = "alert";
					document.getElementById("emailMessage").innerHTML = "Please eneter a valid email address. ";
					valid = false;
				}
				else
				{
					document.getElementById("emailMessage").className = "";
					document.getElementById("emailMessage").innerHTML = "Email Address: ";
				}
				if(document.getElementById("pw").value.length < 8 || document.getElementById("pw").value.length > 16)
				{
					document.getElementById("pwMessage").className = "alert";
					document.getElementById("pwMessage").innerHTML = "Invalid password. ";
					var valid = false;
				}
				else
				{
					document.getElementById("pwMessage").className = "";
					document.getElementById("pwMessage").innerHTML = "Password (between 8 and 16 characters): ";
				}
				if(document.getElementById("pw2").value != document.getElementById("pw").value)
				{
					document.getElementById("pw2Message").className = "alert";
					document.getElementById("pw2Message").innerHTML = "Your passwords do not match. ";
					var valid = false;
				}
				else
				{
					
					document.getElementById("pw2Message").className = "";
					document.getElementById("pw2Message").innerHTML = "Confirm Password: ";
				}
				if(valid)
					document.getElementById("form").submit();
			}
			function isEmail(email)
			{
				return /^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/.test(email);	
			}
		</script>
	</head>
	<body>
			<div class="parentDiv" id="leftMargin">
				<?php
					include_once("scripts/leftInit.php");
				?>
			</div>
			<div class="parentDiv" id="center">
				<?php
					echo("<span class=\"alert\">" . $message . "</span>");
					echo($content);
				?>
			</div>
			<div class="parentDiv" id="rightMargin">
				<?php
					include_once("scripts/rightInit.php");
				?>
			</div>
	</body>
</html>