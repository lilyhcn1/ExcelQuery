<?php
	require("conn.php");
	$id=$_GET['q'];
	if(!$id){
		header("Location: list.php");
	}
	$row=$dbh->query("SELECT url FROM url where id=$id;")->fetch();
?>
<table border="1">
<caption><?php echo $config["baseurl"].base_convert($id,10,36)." -> <a href='$row[0]'>$row[0]</a>";?></caption>
<thead><tr><td>ID</td><td>IP</td><td>UA</td><td>Referer</td><td>Language</td><td>Time</td></tr></thead>
<tbody>
<?php
	$rows=$dbh->query('SELECT id,ip,ua,referer,language,datetime(time,"localtime") FROM click where urlid='.$id);
	foreach($rows as $row){
		echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td></tr>";
	}
?>
</tbody>
</table>
<table border="1">
<caption>Preview</caption>
<thead><tr><td>ID</td><td>IP</td><td>UA</td><td>Referer</td><td>Language</td><td>Time</td></tr></thead>
<tbody>
<?php
	$rows=$dbh->query('SELECT id,ip,ua,referer,language,datetime(time,"localtime") FROM preview where urlid='.$id);
	foreach($rows as $row){
		echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td></tr>";
	}
?>
</tbody>
</table>