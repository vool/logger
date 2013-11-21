<?php

/*
 * A simple logger which stores all GET requests to a sqlLite db as key/value pairs
 * 
 * Usage:
 * 
 * Log values
 * <SERVER ADDR>/?temp=15&humidity=50&volume=11
 * 
 * Clear the db
 * <SERVER ADDR>/?clear
 * 
 * Note - Only for testing and debuging, no security or input sanitation etc.
 * 
 */




$db = new PDO('sqlite:logger.sqlite');

if(ISSET($_GET['clear'])){
	
	echo "data Cleared";
	
	$db->exec("DELETE FROM logs");
	
}else{

	$db->exec("CREATE TABLE IF NOT EXISTS logs (id INTEGER PRIMARY KEY  NOT NULL  UNIQUE  check(typeof(id) = 'integer') , key VARCHAR, value , time );");

	$now = time();
	
	foreach($_GET as $key => $value){

		$db->exec("INSERT INTO logs (key, value, time) VALUES ('$key', '$value', '$now');");

	}


	$sql = "SELECT * FROM logs;";

	$rs = $db->query($sql);
	
	if (!$rs) {
		
		echo "An SQL error occured.\n";
		
		exit;
		
	}

	echo'<table>
	<tr>
	<th>ID</th>
	<th>Key</th>
	<th>Value</th>
	<th>timestamp</th>
	</tr>';


	while($r = $rs->fetch(PDO::FETCH_ASSOC)) {

		echo '<tr>
		<td>'.$r['id'].'</td>
		<td>'.$r['key'].'</td>
		<td>'.$r['value'].'</td>
		<td>'.date("Y-m-d H:i:s",$r['time']).'</td>
		<tr>';

	}

	echo'<table>';

}

$db = NULL;

?>