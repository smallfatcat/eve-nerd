<?php
	if(isset($_GET["character_name"])){
		include 'db_auth.php';
		$character_name = $_GET["character_name"];
		//$character_name = SmallFatCat;
	  

		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}

		$ksql = $conn->prepare("SELECT killmail_id, typeName, character_name, killmail_time FROM nerdDB.killmails JOIN eve_sde.invTypes ON nerdDB.killmails.ship_type_id = eve_sde.invTypes.typeID JOIN nerdDB.characters ON nerdDB.killmails.character_id = nerdDB.characters.character_id WHERE character_name = ? ORDER BY killmail_id DESC");
		$res = $ksql -> execute(array($character_name));

		//$arr = $ksql->fetchAll();
		$resultArray[0] = Array('killmail_id','typeName','character_name','killmail_time');
		for($i=1;$i<=$ksql->rowCount();$i++){
			$row = $ksql->fetch(PDO::FETCH_ASSOC);
			$resultArray[$i] = Array($row['killmail_id'],$row['typeName'],$row['character_name'],$row['killmail_time']);
		}
		echo json_encode($resultArray);
	}
?>