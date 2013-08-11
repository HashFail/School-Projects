<?php
	if($_SESSION["logged_in"])
	{
		echo("<div class=\"navP\"><span class=\"notification\"> You are logged in as ".$_SESSION["uname"] . "</span></div>" . "<div class=\"navP\"><a href=\"profile.php?target=". $_SESSION["uid"] ."\" class=\"navButton\">View account</a></div><div class=\"navP\"><span class=\"navButton\" onclick=\"logOut()\">Log Out</span></div>");
	}
	else
	{
		echo("<div class=\"navP\"><a href=\"login.php\" class=\"navButton\" id=\"login\">Log in</a></div><div class=\"navP\"><a href=\"signup.php\" id=\"signup\" class=\"navButton\">Sign up</a></div>");
	}
?>