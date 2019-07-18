<?php
	require("conn.php");
?>
<table border="1">
<thead><tr><td>ID</td><td>URL</td><td>Click</td><td>Preview</td><td>Time</td><td>Shorten</td></tr></thead>
<tbody>
<?php
	$rows=$dbh->query('SELECT id,url,click,preview,time FROM url;');
	foreach($rows as $row){
		echo "<tr><td>$row[0]</td><td><a href='$row[1]'>$row[1]</a></td><td>$row[2]</td><td>$row[3]</td><td><a href='detail.php?q=$row[0]' title='查看详细信息'>$row[4]</a></td><td><input size='30' value='".$config["baseurl"].base_convert($row[0],10,36)."' onmouseover='this.select();'></td></tr>";
	}
?>
</tbody>
</table>