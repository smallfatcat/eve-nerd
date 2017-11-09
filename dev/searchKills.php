<?php
	if(isset($_GET["solar_system_id"])){
		include 'db_auth.php';
		$solar_system_id = (int)$_GET["solar_system_id"];
		//$solar_system_id = 30002354;
	  

		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}

		$ksql = $conn->prepare("SELECT killmail_id, typeName, character_name, killmail_time FROM nerdDB.killmails JOIN eve_sde.invTypes ON nerdDB.killmails.ship_type_id = eve_sde.invTypes.typeID JOIN nerdDB.characters ON nerdDB.killmails.character_id = nerdDB.characters.character_id WHERE solar_system_id = ? ORDER BY killmail_id DESC");
		$res = $ksql -> execute(array($solar_system_id));

		//$arr = $ksql->fetchAll();
		$resultArray[0] = Array('killmail_id','typeName','character_name','killmail_time');
		for($i=1;$i<=$ksql->rowCount();$i++){
			$row = $ksql->fetch(PDO::FETCH_ASSOC);
			$resultArray[$i] = Array($row['killmail_id'],$row['typeName'],$row['character_name'],$row['killmail_time']);
		}
		echo json_encode($resultArray);
	}
?>