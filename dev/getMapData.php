<?php
	if(isset($_GET["region_id"])){
		include 'db_auth.php';
		$region_id = (int)$_GET["region_id"];
		//$region_id = 30000001;
	  

		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}

		$ksql = $conn->prepare("SELECT solar_system_id, solar_system_name, constellation_id, constellation_name, region_id, region_name, x, y, z, render_x, render_y FROM nerdDB.map WHERE map.region_id = ?");
		//$ksql = $conn->prepare("SELECT solar_system_id, solar_system_name, constellation_id, constellation_name, region_id, region_name, x, y, z, render_x, render_y FROM nerdDB.map WHERE solar_system_id < 31000001");
		$lsql = $conn->prepare("SELECT toSolarSystemID FROM eve_sde.mapSolarSystemJumps WHERE fromSolarSystemID = ?");
		$res = $ksql -> execute(array($region_id));

		//$resultArray[0] = Array('solar_system_id','solar_system_name','constellation_id','constellation_name','region_id','region_name','x','y','z','render_x','render_y','linkList');
		for($i=0;$i<$ksql->rowCount();$i++){
			$row = $ksql->fetch(PDO::FETCH_ASSOC);
			$resLink = $lsql -> execute(array($row['solar_system_id']));
			$linkArray = Array();
			for($j=0;$j<$lsql->rowCount();$j++){
				$linkRow = $lsql->fetch(PDO::FETCH_ASSOC);
				$linkArray[$j] = Array($linkRow['toSolarSystemID']); 
			}
			$resultArray[$i] = Array($row['solar_system_id'], $row['solar_system_name'], $row['constellation_id'], $row['constellation_name'], $row['region_id'], $row['region_name'], $row['x'],$row['y'], $row['z'],$row['render_x'], $row['render_y'], $linkArray);
		}
		echo json_encode($resultArray);
	}
?>