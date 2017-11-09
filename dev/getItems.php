<?php
	if(isset($_GET["killmail_id"])){
		include 'db_auth.php';
		$killmail_id = (int)$_GET["killmail_id"];
		//$killmail_id = 65693294;
	  

		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}

		$ksql = $conn->prepare("SELECT flagName, typeName, quantity_destroyed, quantity_dropped FROM nerdDB.items JOIN eve_sde.invFlags on nerdDB.items.flag = eve_sde.invFlags.flagID JOIN eve_sde.invTypes ON nerdDB.items.item_type_id = eve_sde.invTypes.typeID WHERE killmail_id = ?");
		$res = $ksql -> execute(array($killmail_id));

		$resultArray[0] = Array('flagName','typeName','quantity_destroyed','quantity_dropped');
		for($i=1;$i<=$ksql->rowCount();$i++){
			$row = $ksql->fetch(PDO::FETCH_ASSOC);
			$resultArray[$i] = Array($row['flagName'],$row['typeName'],$row['quantity_destroyed'],$row['quantity_dropped']);
		}
		echo json_encode($resultArray);
	}
?>