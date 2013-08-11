<?php
	echo("<div class=\"navP\"><a class=\"navButton\" id=\"home\" href=\"http://jakechristensentesting.co.nf/abearielphotography/\">Home</a></div>");
	if($_SESSION["logged_in"])
	{
		echo("<div class=\"navP\"><a class=\"navButton\" id=\"upload\" href=\"upload.php\">Upload</a></div>");
		echo("<div class=\"navP\"><a class=\"navButton\" id=\"favs\" href=\"favorites.php\">Favorites</a></div>");
		echo("<div class=\"navP\"><a class=\"navButton\" id=\"uploads\" href=\"uploads.php\">Past uploads</a></div>");
	}
?>