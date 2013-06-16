<?php
	include_once("scripts/loginInit.php");
	include_once("scripts/connect.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Abe Ariel Photography</title>
		<link rel="stylesheet" type="text/css" href="scripts/mainstyle.css" />
		<script type="text/javascript" src="scripts/mainjs.js"></script>
		<style type="text/css">
			.alert{font-size:1.2em; text-align:center; color:red;}
		</style>
	</head>
	<body>
			<div class="parentDiv" id="leftMargin">
				<?php
					include_once("leftInit/leftInit.php");
				?>
			</div>
			<div class="parentDiv" id="center">
				<?php
					if($_SESSION["logged_in"])
					{
						if($_SESSION["access_level"] == "basic")
						{
							echo("h1 class=\"alert\">You do not have the clearance to upload.</h1>");
						}
					}
					else
						echo("<h1 class=\"alert\">You need to be logged in to upload.</h1>");
				?>
			</div>
			<div class="parentDiv" id="rightMargin">
				<?php
					include_once("scripts/rightInit.php");
				?>
			</div>
	</body>
</html>