<?php
	session_start();
	include 'db_auth.php';
	// Get Corp Assets
	try {
	  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
	} catch (PDOException $e) {
	  die('Connection failed: ' . $e->getMessage());
	}

	$csql = $conn->prepare("SELECT access_token FROM login WHERE login_name = ?");
	$res = $csql -> execute(array($_SESSION["login_name"]));
	if($csql->rowCount() > 0){
		$row = $csql->fetch(PDO::FETCH_ASSOC);
		$_SESSION['access_token'] = $row['access_token'];
	}
	$assets_not_finished = True;
	$page_number = 1;
	while($assets_not_finished){
		// Get corp assets
		$c = curl_init('https://esi.evetech.net/latest/corporations/98015080/assets/?datasource=tranquility&page='.$page_number);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array(	'accept: application/json' ,
																								'authorization: Bearer '.$_SESSION['access_token']
																							));
		$asset_list = curl_exec($c);
		curl_close($c);
		$asset_data = json_decode($asset_list);
		//echo $_SESSION['access_token'];
		//var_dump($asset_data);
		echo $page_number.":";
		if(count($asset_data) == 0){
			$assets_not_finished = False;
		}
		$page_number = $page_number + 1;

		// Update table with asset data
		
		$update_assets_sql = $conn->prepare("INSERT INTO corp_assets (is_blueprint_copy, is_singleton, item_id, location_flag, location_id, location_type, quantity, type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		foreach($asset_data as $asset){
			$update_assets_res = $update_assets_sql -> execute(array(
				$asset->is_blueprint_copy,
				$asset->is_singleton,
				$asset->item_id,
				$asset->location_flag,
				$asset->location_id,
				$asset->location_type,
				$asset->quantity,
				$asset->type_id
			));
		}
	}
?>