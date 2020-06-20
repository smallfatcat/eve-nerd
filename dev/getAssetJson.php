<?php
		include 'db_auth.php';

		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}
		//echo "SELECT character_name FROM characters WHERE character_name LIKE '?'";
		//echo $character_name;
		$csql = $conn->prepare("SELECT is_blueprint_copy, is_singleton, item_id, location_flag, location_id, location_type, quantity, type_id FROM corp_assets");
		$res = $csql -> execute();

		$resultArray = Array();
		for($i=0;$i<$csql->rowCount();$i++){
			$row = $csql->fetch(PDO::FETCH_ASSOC);
			/*$resultArray[$i] = Array($row['is_blueprint_copy'],$row['is_singleton'],$row['item_id'],$row['location_flag'],$row['location_id'],$row['location_type'],$row['quantity'],$row['type_id']);*/
			$res_object = (object) [
    		'is_blueprint_copy' => $row['is_blueprint_copy'],
    		'is_singleton' => $row['is_singleton'],
    		'item_id' => $row['item_id'],
    		'location_flag' => $row['location_flag'],
    		'location_id' => $row['location_id'],
    		'location_type' => $row['location_type'],
    		'quantity' => $row['quantity'],
    		'type_id' => $row['type_id']
  		];
  		$resultArray[$i] = $res_object;
		}
		echo json_encode($resultArray);
		//var_dump($resultArray);

?>