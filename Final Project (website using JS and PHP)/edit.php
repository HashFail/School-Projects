<?php
	include_once("scripts/loginInit.php");
	include_once("scripts/connect.php");
	if(isset($_POST['title']))
	{
		if($_POST['type'] == 'video')
		{
			$src = "<iframe class=\"content\" src=\"".$_POST['source']."\"></iframe>";
		}
		else
			$src = $_POST['source'];
		$conn = dbConnect();
		$cmd = "update content set cid = ".$_POST['cid'].", data_type = '".$_POST['type']."', source = '".$src."', title = '".htmlentities($_POST['title'])."', description = '".htmlentities($_POST['description'])."' where cid = " .$_POST['cid']. ";";
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
		<style type="text/css">
			#uploads{display:none};
		</style>
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
	</head>
	<body>
			<div class="parentDiv" id="leftMargin">
				<?php
					include_once("scripts/leftInit.php");
				?>
			</div>
			<div class="parentDiv" id="center">
				<?php
					if($_SESSION['logged_in'])
					{
						$conn = dbConnect();
						$cmd = "select cid, data_type, source, title, description from content where uploader = " . $_SESSION['uid'] . " and cid = " . $_GET['cid'] . " order by cid limit 10;";
						$statement = $conn->prepare($cmd);
						$statement->execute();
						if($statement->rowcount()>0)
						{
							$result = $statement->fetchAll();
							echo("Recent Uploads: <br />");
							foreach($result as $row)
							{
								echo("<div class=\"contentContainer\">
								<form class=\"content\" id=\"uploadForm\" method=\"post\" action=\"edit.php\">
								Data Type: <br />");
								if($row['data_type'] == "image")
								{
									echo("Image: <input onclick=\"changeInstructions(0);\" checked=\"checked\" type=\"radio\" name=\"type\" value=\"image\" /> Video: <input onclick=\"changeInstructions(1);\" type=\"radio\" name=\"type\" value=\"video\" /><br />
									<div id=\"uploadInstructions\">Enter the url of the image: </div>");
								}
								else
								{
									echo("Image: <input onclick=\"changeInstructions(0);\" type=\"radio\" name=\"type\" value=\"image\" /> Video: <input checked=\"checked\" onclick=\"changeInstructions(1);\" type=\"radio\" name=\"type\" value=\"video\" /><br />
									<div id=\"uploadInstructions\">Enter the url of the image: </div>");
								}
								echo("Source: <input type=\"text\" name=\"source\" value=\"" . $row['source'] . "\" /><br />
								Title: <input type=\"text\" name=\"title\" value=\"" . $row['title'] . "\" /><br />
								Description:<br /> <textarea class=\"content\" name=\"description\">" . $row['description'] . "</textarea><br />
								<input type=\"submit\" value=\"submit\" />
								<input name=\"cid\" value=\"".$_GET['cid']."\" type=\"hidden\" />
								</form>
								</div>");
							}
						}
						else
							echo("No upload was found.");
					}
					else
					{
						echo("You need to be logged in to edit past uploads.");
					}
				?>	
			</div>
			<div class="parentDiv" id="rightMargin">
				<?php
					include_once("scripts/rightInit.php");
				?>
			</div>
	</body>
</html>