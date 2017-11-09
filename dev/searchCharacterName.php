<?php
	if(isset($_GET["term"])){
		include 'db_auth.php';
		$character_name = $_GET["term"];
		//$character_name = 'SmallFatCat';
	  

		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}

		$csql = $conn->prepare("SELECT character_name FROM characters WHERE character_name LIKE '". $character_name . "%'");
		$res = $csql -> execute(array($solar_system_id));

		$resultArray = Array();
		for($i=0;$i<$csql->rowCount();$i++){
			$row = $csql->fetch(PDO::FETCH_ASSOC);
			$resultArray[$i] = $row['character_name'];
		}
		echo json_encode($resultArray);
	}
?>