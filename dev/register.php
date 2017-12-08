<?php
	session_start();
	include 'db_auth.php';
	if(isset($_POST["register"])){
		$login_name = $_POST["login_name"];
		$login_pass = $_POST["login_pass1"];
		$state = random_int(0, 60000);
		try {
		  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
		} catch (PDOException $e) {
		  die('Connection failed: ' . $e->getMessage());
		}

		//$csql = $conn->prepare("INSERT INTO nerdDB.login (login_name, login_pass) VALUES ('sfc','letmein')");
		$csql = $conn->prepare("INSERT INTO nerdDB.login (login_name, login_pass, state) VALUES (?, ?, ?)");
		$res = $csql -> execute(array($login_name, $login_pass, $state));
		$_SESSION["login_name"] = $login_name;
		$_SESSION["state"] = $state;
		$_SESSION["logged_in"] = true;
		header("Location:main.php");
  	exit();
	}
	else{
		echo '<form action="/dev/register.php" method="post">';
		echo '<label for="login_name">Username:</label>';
		echo '<input type="text" name="login_name">';
		echo '<label for="login_pass1">Password:</label>';
		echo '<input type="password" name="login_pass1">';
		echo '<label for="login_pass2">Re-Type Password:</label>';
		echo '<input type="password" name="login_pass2">';
		echo '<input type="hidden" name="register" value="1">';
		echo '<input type="submit" value="Register">';
		echo '</form>';
	}
?>