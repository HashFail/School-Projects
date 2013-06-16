<?php
	include_once("scripts/loginInit.php");
	include_once("scripts/connect.php");
	if(isset($_POST['title']))
	{
		if($_POST['type'] == 'video')
			$src = "<iframe class=\"content\" src=\"".$_POST['source']."\"></iframe>";
		else
			$src = $_POST['source'];
		$conn = dbConnect();
		$cmd = "insert into content (data_type, source, title, description, upload_date, uploader) values ('".$_POST['type']."', '".$src."', '".htmlentities($_POST['title'])."', '".htmlentities($_POST['description'])."', sysdate(), " . $_SESSION['uid'] .");";
		$statement = $conn->prepare($cmd);
		$statement->execute();
		header("Location: index.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Abe Ariel Photography</title>
		<link rel="stylesheet" type="text/css" href="scripts/mainstyle.css" />
		<script type="text/javascript" src="scripts/mainjs.js"></script>
		<script type="text/javascript">
			function changeInstructions(cmd)
			{
				if(cmd == 0)
				{
					document.getElementById("uploadInstructions").innerHTML = "Enter the url of the image: ";
				}
				else
					document.getElementById("uploadInstructions").innerHTML = "Enter the embed link of the video: ";
			}
		</script>
		<style type="text/css">
			.alert{font-size:1.2em; text-align:center; color:red;}
			#upload{display:none;}
		</style>
	</head>
	<body>
			<div class="parentDiv" id="leftMargin">
				<?php
					include_once("scripts/leftInit.php");
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
						else
						{
							echo("<div class=\"contentContainer\">
							<form class=\"content\" id=\"uploadForm\" method=\"post\" action=\"upload.php\">
							Data Type: <br />
							Image: <input onclick=\"changeInstructions(0);\" checked=\"checked\" type=\"radio\" name=\"type\" value=\"image\" /> Video: <input onclick=\"changeInstructions(1);\" type=\"radio\" name=\"type\" value=\"video\" /><br />
							<div id=\"uploadInstructions\">Enter the url of the image: </div>
							Source: <input type=\"text\" name=\"source\" /><br />
							Title: <input type=\"text\" name=\"title\" /><br />
							Description:<br /> <textarea class=\"content\" name=\"description\"></textarea><br />
							<input type=\"submit\" value=\"Submit\" />
							</form>
							</div>");
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