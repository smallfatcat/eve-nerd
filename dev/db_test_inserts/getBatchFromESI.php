<?php
  include 'db_auth.php';

	$killID = $_GET['k'];
  $hash 	= $_GET['h'];
  $killExists = true;
  $batchSize = 10;
  $totalTime = 0.0;

  $timeTaken = -microtime(true);

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
	}

	$sql = 'SELECT * FROM zkill_history WHERE killID NOT IN (SELECT killmail_id FROM killmails) ORDER BY killID DESC LIMIT '.$batchSize;
	$result = $conn->query($sql);
	$batchH = array();
	$batchK = array();
	$i =0;
	while($row = $result->fetch_assoc()) {
    echo 'killID: ' . $row['killID']. ' - hash: ' . $row['hash'].'<br>';
    $batchH[$i] = $row['hash'];
    $batchK[$i] = $row['killID'];
    $i++;
  }
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'eve-nerd.com');
	curl_setopt($ch,CURLOPT_ENCODING , 'gzip');
  
  for($i=0;$i<$batchSize;$i++){
  	$killID = $batchK[$i];
  	$hash 	= $batchH[$i];
  	echo 'Batch: ' . $i . ' killmail_id: ' . $killID. ' ';

		$sql = 'SELECT * FROM `killmails` WHERE `killmail_id` = '. $killID;
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			echo 'Kill in table<br>';
			$killExists = true;
		}
		else{
			echo 'Kill not in table<br>';
			$killExists = false;
		}
		
		//$url = 'https://esi.tech.ccp.is/latest/killmails/58764611/adce0198973a4b913da6fadca62f184bb248f19d/?datasource=tranquility';
		//$url = 'https://35.186.214.164/latest/killmails/' . $killID . '/' . $hash . '/?datasource=tranquility';
		$url = 'https://esi.tech.ccp.is/latest/killmails/' . $killID . '/' . $hash . '/?datasource=tranquility';
		
		
		curl_setopt($ch, CURLOPT_URL, $url);
		$timeCurl = -microtime(true);
		$json_data = curl_exec($ch);

		 

		//echo '<b>json_data DUMP</b><br>';
		//var_dump($json_data);
		
		$killdata = json_decode($json_data, false);
		
		//$killdata = json_decode(file_get_contents($url), false);
		$timeCurl += microtime(true);
		$totalTime += $timeCurl;
		echo 'Time: ' . $timeCurl.'TotalTime: '.$totalTime.'<br>';
		var_dump($killdata);
		//echo '<br>error: ' . json_last_error() . '<br>';
		/*
		echo '<br><b>killdata DUMP</b><br>';
		
		echo '<br><br>';
		echo 'killmail_id: ' . $killdata->killmail_id . '<br>';
		echo 'killmail_time: ' . $killdata->killmail_time . '<br>';
		echo 'solar_system_id: ' . $killdata->solar_system_id . '<br>';
		echo '<br><b>Victim</b><br>';
		echo 'damage_taken: ' . $killdata->victim->damage_taken . '<br>';
		echo 'ship_type_id: ' . $killdata->victim->ship_type_id . '<br>';
		echo 'character_id: ' . $killdata->victim->character_id . '<br>';
		echo 'corporation_id: ' . $killdata->victim->corporation_id . '<br>';
		echo 'alliance_id: ' . $killdata->victim->alliance_id . '<br>';
		echo 'position->x: ' . $killdata->victim->position->x . '<br>';
		echo 'position->y: ' . $killdata->victim->position->y . '<br>';
		echo 'position->z: ' . $killdata->victim->position->z . '<br>';
		*/

		$sql = 'INSERT INTO killmails (killmail_id'
																	.', killmail_hash'
																	.', killmail_time'
																	.', character_id'
																	.', damage_taken'
																	.', ship_type_id'
																	.', corporation_id'
																	.', alliance_id'
																	.', solar_system_id'
																	.', position)'
																	.' VALUES ('. $killdata->killmail_id
																	.", '" . $hash . "'"
																	.", '" . $killdata->killmail_time . "'"
																	.', ' . (!isset($killdata->victim->character_id)?0:$killdata->victim->character_id)
																	.', ' . $killdata->victim->damage_taken
																	.', ' . $killdata->victim->ship_type_id 
																	.', ' . (!isset($killdata->victim->corporation_id)?0:$killdata->victim->corporation_id)
																	.', ' . (!isset($killdata->victim->alliance_id)?0:$killdata->victim->alliance_id)
																	.', ' . $killdata->solar_system_id 
																	.", '" . 'x:'.$killdata->victim->position->x. 'y:'.$killdata->victim->position->y. 'z:'.$killdata->victim->position->z . "'" 
																	.')';
		echo 'SQL: '.$sql;
		echo '<br><br>';
		if(!$killExists){
			if ($conn->query($sql) === TRUE) {
				echo 'New record created successfully<br>';
			} else {
				echo 'Error: ' . $sql . '<br>' . $conn->error.'<br>';
			}
		}
		

		//echo '<br><b>Attackers</b><br>';
		//var_dump($killdata->attackers);
		foreach($killdata->attackers as $attacker) {
			/*
			echo 'security_status: ' . $attacker->security_status . '<br>';
			echo 'final_blow: ' . ($attacker->final_blow?'true':'false') . '<br>';
			echo 'damage_done: ' . $attacker->damage_done . '<br>';
			echo 'character_id: ' . $attacker->character_id . '<br>';
			echo 'corporation_id: ' . $attacker->corporation_id . '<br>';
			echo 'alliance_id: ' . $attacker->alliance_id . '<br>';
			echo 'faction_id: ' . $attacker->faction_id . '<br>';
			echo 'ship_type_id: ' . $attacker->ship_type_id . '<br>';
			echo 'weapon_type_id: ' . $attacker->weapon_type_id . '<br>';
			*/

			$sql = 'INSERT INTO attackers (character_id'
																	.', corporation_id'
																	.', alliance_id'
																	.', faction_id'
																	.', security_status'
																	.', damage_done'
																	.', final_blow'
																	.', ship_type_id'
																	.', weapon_type_id'
																	.', killmail_id)'
																	.' VALUES ('. (!isset($attacker->character_id)?0:$attacker->character_id)
																	.', ' . (!isset($attacker->corporation_id)?0:$attacker->corporation_id)
																	.', ' . (!isset($attacker->alliance_id)?0:$attacker->alliance_id)
																	.', ' . (!isset($attacker->faction_id)?0:$attacker->faction_id)
																	.', ' . $attacker->security_status
																	.', ' . $attacker->damage_done 
																	.', ' . ($attacker->final_blow?1:0)
																	.', ' . (!isset($attacker->ship_type_id)?0:$attacker->ship_type_id)
																	.', ' . (!isset($attacker->weapon_type_id)?0:$attacker->weapon_type_id)
																	.', ' . $killdata->killmail_id
																	.')';
			echo 'SQL: '.$sql;
			echo '<br><br>';
			if(!$killExists){
				if ($conn->query($sql) === TRUE) {
					echo 'New record created successfully<br>';
				} else {
					echo 'Error: ' . $sql . '<br>' . $conn->error.'<br>';
				}
			}
		}

		//echo '<br><b>Items</b><br>';
		//var_dump($killdata->victim->items);
		foreach($killdata->victim->items as $item) {
			/*
			echo 'item_type_id: ' . $item->item_type_id . '<br>';
			echo 'singleton: ' . ($item->singleton?'true':'false') . '<br>';
			echo 'flag: ' . $item->flag . '<br>';
			echo 'quantity_destroyed: ' . $item->quantity_destroyed . '<br>';
			echo 'quantity_dropped: ' . $item->quantity_dropped . '<br>';
			echo '<br>';
			*/

			$sql = 'INSERT INTO items (item_type_id'
															.', singleton'
															.', flag'
															.', quantity_destroyed'
															.', quantity_dropped'
															.', killmail_id)'
															.' VALUES ('. (!isset($item->item_type_id)?0:$item->item_type_id)
															.', ' . ($item->singleton?1:0)
															.', ' . (!isset($item->flag)?0:$item->flag)
															.', ' . (!isset($item->quantity_destroyed)?0:$item->quantity_destroyed)
															.', ' . (!isset($item->quantity_dropped)?0:$item->quantity_dropped)
															.', ' . $killdata->killmail_id
															.')';
			echo 'SQL: '.$sql;
			echo '<br><br>';
			if(!$killExists){
				if ($conn->query($sql) === TRUE) {
					echo 'New record created successfully<br>';
				} else {
					echo 'Error: ' . $sql . '<br>' . $conn->error.'<br>';
				}
			}
		}
	}
	//curl_close($ch);

	$conn->close();
	$timeTaken += microtime(true);
	echo '<br><br><br><b>FINISHED --- TimeTaken: '.$timeTaken.'</b>';

?>