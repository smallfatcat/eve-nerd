<?php
	session_start();
	if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]){
		include 'db_auth.php';
		$character_id = $_SESSION["character_id"];
		$solar_system_id = $_GET["solar_system_id"];
		$ship_type_id = $_GET["ship_type_id"];

		// Calculate update time
		$t = time();
		$update_time = gmdate("Y-m-d H:i:s",$t);

		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}

		$ksql = $conn->prepare("INSERT INTO location_history (character_id, solar_system_id, ship_type_id, update_time) VALUES (?, ? ,?, ?)");
		$res = $ksql -> execute(array($character_id, $solar_system_id, $ship_type_id, $update_time));
	}
?>