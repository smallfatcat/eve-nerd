<?php
 		// example curl setup from ESI
 		//curl -X GET "https://esi.evetech.net/latest/universe/system_kills/?datasource=tranquility" -H "accept: application/json"
 		//curl -X GET "https://esi.evetech.net/latest/universe/system_jumps/?datasource=tranquility" -H "accept: application/json"

 		// Setup DB
 		include 'db_auth.php';
 		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}
		$ksql = $conn->prepare("INSERT INTO universe_data_kills (solar_system_id, npc_kills, pod_kills, ship_kills, update_time) VALUES (?, ? ,?, ?, ?)");
		$jsql = $conn->prepare("INSERT INTO universe_data_jumps (solar_system_id, jumps, update_time) VALUES (?, ? ,?)");
		
		// Setup Curl
		$chk = curl_init();
		curl_setopt($chk, CURLOPT_URL,"https://esi.evetech.net/latest/universe/system_kills/?datasource=tranquility");
		curl_setopt($chk, CURLOPT_RETURNTRANSFER, true);
		$responsek = curl_exec ($chk);
		curl_close ($chk);
		$system_kills_data = json_decode($responsek, false);

		$chj = curl_init();
		curl_setopt($chj, CURLOPT_URL,"https://esi.evetech.net/latest/universe/system_jumps/?datasource=tranquility");
		curl_setopt($chj, CURLOPT_RETURNTRANSFER, true);
		$responsej = curl_exec ($chj);
		curl_close ($chj);
		$system_jumps_data = json_decode($responsej, false);

		// Calculate update time
		$t = time();
		$update_time = gmdate("Y-m-d H:i:s",$t);


		// Update DB
		foreach ($system_kills_data as $ss) {
			$res = $ksql -> execute(array($ss->system_id, $ss->npc_kills, $ss->pod_kills, $ss->ship_kills, $update_time));
			if(!$res){
	      die(sprintf("Error: %s\n", $ksql->errorInfo()));
      }
		}

		foreach ($system_jumps_data as $ss) {
			$res = $jsql -> execute(array($ss->system_id, $ss->ship_jumps, $update_time));
			if(!$res){
	      die(sprintf("Error: %s\n", $jsql->errorInfo()));
      }
		}
?>