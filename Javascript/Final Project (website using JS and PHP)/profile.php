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
					include_once("scripts/leftInit.php");
				?>
			</div>
			<div class="parentDiv" id="center">
				<?php
					if(!isset($_GET['target']) && $_GET['target'] != null)
					{
						echo("<h1 class=\"alert\">No account was found.</h1>");
					}
					else
					{
						$conn = dbConnect();
						$cmd = "select uid, email, coalesce(concat(first_name,' ',last_name), email) as \"name\", access_level, pw, active from users where uid='" . $_GET['target'] . "';";
						$statement = $conn->prepare($cmd);
						$statement->execute();
						if($statement->rowCount() == 0)
							echo("<h1 class=\"alert\">No account was found.</h1>");
						else
						{
							$result = $statement->fetch();
							echo("
								<div class=\"header\">User: ". $result["name"] ."</div>
								<div class=\"header\">Access Level: ". ucfirst($result["access_level"]) ."</div>
								");
							if($result['access_level'] != 'basic')
							{
								$cmd = "select cid, data_type, source, title, description from content where uploader = " . $_GET['target'] . " order by cid desc LIMIT 10;";
								$statement = $conn->prepare($cmd);
								$statement->execute();
								if($statement->rowcount()>0)
								{
									$result = $statement->fetchAll();
									echo("Recent Uploads: <br />");
									foreach($result as $row)
									{
										echo("<div class=\"contentContainer\"><a class=\"title\">".$row['title'] . "</a><br />");
										if($row['data_type'] == "image")
											echo("<img class=\"content\" src = \"" .$row['source'] . "\" />");
										else
											echo($row['source']);
										echo("<span class=\"description\">".$row['description'] . "</span><hr /></div>");
									}
								}
							}
						}
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