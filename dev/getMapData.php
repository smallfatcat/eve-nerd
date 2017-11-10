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

		$ksql = $conn->prepare("SELECT solar_system_id, solar_system_name, constellation_id, constellation_name, region_id, region_name, x, y, z FROM nerdDB.map WHERE map.region_id = ?");
		$res = $ksql -> execute(array($region_id));

		$resultArray[0] = Array('solar_system_id','solar_system_name','constellation_id','constellation_name','region_id','region_name','x','y','z');
		for($i=1;$i<=$ksql->rowCount();$i++){
			$row = $ksql->fetch(PDO::FETCH_ASSOC);
			$resultArray[$i] = Array($row['solar_system_id'], $row['solar_system_name'], $row['constellation_id'], $row['constellation_name'], $row['region_id'], $row['region_name'], $row['x'],$row['y'], $row['z']);
		}
		echo json_encode($resultArray);
	}
?>