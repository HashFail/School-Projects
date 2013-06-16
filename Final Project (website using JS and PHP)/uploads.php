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
			#uploads{display:none;}
			.editButton{font-size:1.2em; cursor:pointer; text-decoration:none; color:black;}
			.editButton:hover{text-decoration:underline;}
		</style>
		<script type="text/javascript">
			function deleteContent(cid)
			{
				if(confirm("Do really want to delete this upload?"))
				{
					if (window.XMLHttpRequest)
							var xmlhttp = new XMLHttpRequest();
					else
						var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
					
					xmlhttp.open("GET","scripts/deleteContent.php?cid="+cid,true);
					xmlhttp.send();
					location.reload(true);
				}
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
						$cmd = "select cid, data_type, source, title, description from content where uploader = " . $_SESSION['uid'] . " order by cid desc limit 10;";
						$statement = $conn->prepare($cmd);
						$statement->execute();
						if($statement->rowcount()>0)
						{
							$result = $statement->fetchAll();
							echo("Past Uploads: <br />");
							foreach($result as $row)
							{
								echo("<div class=\"contentContainer\"><a class=\"title\">".$row['title'] . "</a><br />");
								if($row['data_type'] == "image")
									echo("<img class=\"content\" src = \"" .$row['source'] . "\" />");
								else
									echo($row['source']);
								echo("<span class=\"description\">".$row['description'] . "</span>");
								echo("<div><span class=\"editButton\" type=\"button\" onclick=\"deleteContent(".$row['cid'].");\">Delete</span> <a class=\"editButton\" type=\"button\" href=\"edit.php?cid=" . $row['cid'] . "\">Edit</a></div><hr /></div>");
							}
						}
						else
							echo("You have no uploads.");
					}
					else
					{
						echo("You need to be logged in to view your past uploads.");
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