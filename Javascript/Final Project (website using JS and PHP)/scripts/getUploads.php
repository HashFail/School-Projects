<?php
	session_start();
	include_once("connect.php");
	$conn = dbConnect();
	if($_GET['type']=="fav")
		$cmd = "select f.fid, u.uid, c.cid, c.data_type, c.source, c.title, c.description, coalesce(concat(u.first_name,' ',u.last_name), u.email) as \"name\" from content c left join favorites f on c.cid = f.cid join users u on u.uid=c.uploader where u.access_level != 'basic' and f.uid = " . $_SESSION['uid'] . " order by f.fid desc LIMIT " . $_GET['num'] . ", 10;";
	else if(!$_SESSION['logged_in'])
		$cmd = "select u.uid, c.cid, c.data_type, c.source, c.title, c.description, coalesce(concat(u.first_name,' ',u.last_name), u.email) as \"name\" from content c, users u where u.uid=c.uploader and u.access_level != 'basic' order by c.cid desc LIMIT " . $_GET['num'] . ", 10;";
	else
		$cmd = "select f.fid, u.uid, c.cid, c.data_type, c.source, c.title, c.description, coalesce(concat(u.first_name,' ',u.last_name), u.email) as \"name\" from content c left join favorites f on c.cid = f.cid and f.uid = " . $_SESSION['uid'] . " join users u on u.uid=c.uploader where u.access_level != 'basic' order by c.cid desc LIMIT " . $_GET['num'] . ", 10;";;
	$statement = $conn->prepare($cmd);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		$result = "";
		$result = $result . "<div class=\"contentContainer\"><a class=\"title\">".$row['title'] . "</a>";
		$result = $result . "<a href=\"profile.php?target=". $row["uid"] ."\" class=\"uploader\">By: ".$row['name']."</a>";
		if($row['data_type'] == "image")
			$result = $result . "<img class=\"content\" src = \"" .$row['source'] . "\" />";
		else
			$result = $result . $row['source'];
		if($_SESSION['logged_in'])
		{
			if($row['fid'] != null)
				$result = $result . "<div><input type=\"button\" onclick=\"toggleFav(" . $row['fid'] . ", this, " . $row['cid'] . ");\" value=\"Unfavorite\" /></div>";
			else
				$result = $result . "<div><input type=\"button\" value=\"Favorite\" onclick=\"toggleFav(null, this, " . $row['cid'] . ");\" /></div>";
		}
		$result = $result . "<span class=\"description\">".$row['description'] . "</span><hr /></div>";
		echo($result);
	}
?>