<?php
	session_start();
	include 'db_auth.php';

	// handle login form
	if(isset($_POST["login"])){
		$login_name = $_POST["login_name"];
		$login_pass = $_POST["login_pass"];

		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}

		//$csql = $conn->prepare("SELECT login_name FROM login WHERE login_name = '". $login_name . "' AND login_pass = '". $login_pass ."'");
		$csql = $conn->prepare("SELECT login_name, state, character_name, character_id, login_pass, access_token FROM login WHERE login_name = ?");
		$res = $csql -> execute(array($login_name));

		// if rowcount greater than 0 then username matched
		if($csql->rowCount() > 0){
			$row = $csql->fetch(PDO::FETCH_ASSOC);
			$password_hash = $row['login_pass'];
			// Check password against stored password hash
			if(password_verify($login_pass, $password_hash)){
				if(isset($row['access_token'])){
					$_SESSION["auth_status"] = true;
				}
				else{
					$_SESSION["auth_status"] = false;
				}
				$_SESSION["login_name"] = $row['login_name'];
				$_SESSION["state"] = $row['state'];
				$_SESSION["logged_in"] = true;
				$_SESSION["character_name"] = $row['character_name'];
				$_SESSION["character_id"] = $row['character_id'];
			}
			else{
				$_SESSION["logged_in"] = false;
				echo 'Username or Password not recognised';
			}
		}
		// empty result means username not found
		else{
			$_SESSION["logged_in"] = false;
			echo 'Username or Password not recognised';
		}
	}
	// handle logout
	if(isset($_POST["logout"])){
		// $_SESSION["logged_in"] = false;
		// $_SESSION["auth_status"] = false;
		session_unset();
	}
	header("Location:main.php");
	exit();
?>