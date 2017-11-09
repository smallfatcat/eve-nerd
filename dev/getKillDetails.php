<?php
	if(isset($_GET["killmail_id"])){
		include 'db_auth.php';
		$solar_system_id = (int)$_GET["killmail_id"];
		//$solar_system_id = 30002354;
	  

		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}

		$ksql = $conn->prepare("SELECT typeName, character_name, damage_done FROM nerdDB.attackers JOIN eve_sde.invTypes ON nerdDB.attackers.ship_type_id = eve_sde.invTypes.typeID JOIN nerdDB.characters ON nerdDB.attackers.character_id = nerdDB.characters.character_id WHERE killmail_id = ? ORDER BY damage_done DESC");
		$res = $ksql -> execute(array($solar_system_id));

		//$arr = $ksql->fetchAll();
		$resultArray[0] = Array('typeName','character_name','damage_done');
		for($i=1;$i<=$ksql->rowCount();$i++){
			$row = $ksql->fetch(PDO::FETCH_ASSOC);
			$resultArray[$i] = Array($row['typeName'],$row['character_name'],$row['damage_done']);
		}
		echo json_encode($resultArray);
	}
?>