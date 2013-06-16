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
		<script type="text/javascript">
			var currentContent = 10;
			function toggleFav(fid, button, cid)
			{
				if (window.XMLHttpRequest)
					var xmlhttp = new XMLHttpRequest();
				else
					var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				if(fid == null)
				{
					button.disabled = true; 
					button.innerHTML = "Processing...";
					xmlhttp.onreadystatechange=function()
					{
						if(xmlhttp.readyState==4 && xmlhttp.status==200)
						{
							button.setAttribute("onclick", "toggleFav(" + xmlhttp.responseText + ", this, " + cid + ")");
							button.innerHTML = "Unfavorite";
							button.disabled = false;
    					}
  					}
					xmlhttp.open("GET","scripts/favorite.php?cid="+cid,true);
					xmlhttp.send();
				}
				else
				{
					button.disabled = true; 
					button.innerHTML = "Processing...";
					xmlhttp.onreadystatechange=function()
					{
						if(xmlhttp.readyState==4 && xmlhttp.status==200)
						{
							button.setAttribute("onclick", "toggleFav(null, this," + cid + ")");
							button.innerHTML = "Favorite";
							button.disabled = false;
    					}
  					}
					xmlhttp.open("GET","scripts/unfavorite.php?fid="+fid,true);
					xmlhttp.send();
				}
			}
			function loadMore(button)
			{
				button.disabled = true; 
				button.innerHTML = "Processing...";
				if (window.XMLHttpRequest)
					var xmlhttp = new XMLHttpRequest();
				else
					var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				xmlhttp.onreadystatechange=function()
				{
					if(xmlhttp.readyState==4 && xmlhttp.status==200)
					{
						var div = document.createElement('div');
						div.innerHTML = xmlhttp.responseText;
						var elements = div.childNodes;
						while(elements.length>0)
						{
							centerVar.insertBefore(elements[0], document.getElementById("loadContainer"));
						}
						button.disabled = false; 
						button.innerHTML = "Load More";
						currentContent+=10;
    				}
  				}
				xmlhttp.open("GET","scripts/getUploads.php?type=index&num="+currentContent,true);
				xmlhttp.send();
			}
		</script>
		<style type="text/css">
			#home{display:none};
		</style>
	</head>
	<body id="body">
			<div class="parentDiv" id="leftMargin">
				<?php
					include_once("scripts/leftInit.php");
				?>
			</div>
			<div class="parentDiv" id="center">
				<?php
					$conn = dbConnect();
					if(!$_SESSION['logged_in'])
						$cmd = "select u.uid, c.cid, c.data_type, c.source, c.title, c.description, coalesce(concat(u.first_name,' ',u.last_name), u.email) as \"name\" from content c, users u where u.uid=c.uploader and u.access_level != 'basic' order by c.cid desc LIMIT 10;";
					else
						$cmd = "select f.fid, u.uid, c.cid, c.data_type, c.source, c.title, c.description, coalesce(concat(u.first_name,' ',u.last_name), u.email) as \"name\" from content c left join favorites f on c.cid = f.cid and f.uid = " . $_SESSION['uid'] . " join users u on u.uid=c.uploader where u.access_level != 'basic' order by c.cid desc LIMIT 10;";
					$statement = $conn->prepare($cmd);
					$statement->execute();
					$result = $statement->fetchAll();
					foreach($result as $row)
					{
						echo("<div class=\"contentContainer\"><a class=\"title\">".$row['title'] . "</a>");
						echo("<a href=\"profile.php?target=". $row["uid"] ."\" class=\"uploader\">By: ".$row['name']."</a>");
						if($row['data_type'] == "image")
							echo("<img class=\"content\" src = \"" .$row['source'] . "\" />");
						else
							echo($row['source']);
						if($_SESSION['logged_in'])
						{
							if($row['fid'] != null)
								echo("<div><input type=\"button\" onclick=\"toggleFav(" . $row['fid'] . ", this, " . $row['cid'] . ");\" value=\"Unfavorite\" /></div>");
							else
								echo("<div><input type=\"button\" value=\"Favorite\" onclick=\"toggleFav(null, this, " . $row['cid'] . ");\" /></div>");
						}
						echo("<span class=\"description\">".$row['description'] . "</span><hr /></div>");
					}
					echo("<div id=\"loadContainer\"><input type=\"button\" value=\"Load More\" onclick=\"loadMore(this)\" /></div>");
				?>
			</div>
			<div class="parentDiv" id="rightMargin">
				<?php
					include_once("scripts/rightInit.php");
				?>
			</div>
	</body>
</html>