<?php
	session_start();
	include 'db_auth.php';

	function save_auth_code($auth_code, $state){
		$c = curl_init('https://login.eveonline.com/oauth/token');
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array('Authorization: Basic YmRlMzkyY2Q2NDI5NGE4Nzk2ODVhZDJkOWY0NWEwOGE6NXYxSHJzQmdlUW9xeWJVenBlNm5ET20zQkk2ZHpzZ2g2MUlma0pUQg==', 'Content-Type: application/x-www-form-urlencoded', 'Host: login.eveonline.com'));
		curl_setopt($c, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&code='.$auth_code);

		$page = curl_exec($c);
		curl_close($c);
		
		$json_data = json_decode($page);
		if(isset($json_data->error)){
			echo $json_data->error.'<br>';
			echo $json_data->error_description.'<br>';
		}
		else{
			/*echo $json_data->access_token.'<br>';
			echo $json_data->token_type.'<br>';
			echo $json_data->expires_in.'<br>';
			echo $json_data->refresh_token.'<br>';*/
			$t = time();
			$t +=  (int)$json_data->expires_in;
			$expires = date("Y-m-d H:i:s",$t);
			try {
				  $conn = new PDO( "mysql:" . "host=".$GLOBALS['servername'].";" . "dbname=".$GLOBALS['dbname'], $GLOBALS['username'], $GLOBALS['password']);
			} catch (PDOException $e) {
			  die('Connection failed: ' . $e->getMessage());
			}
			//UPDATE login SET auth_code = 'xxx' WHERE login_name = 'sfc' AND state = 'state123';
			$csql = $conn->prepare("UPDATE nerdDB.login SET auth_code = ?, expires = ?, access_token = ?, refresh_token = ? WHERE login_name = ? AND state = ?");
			$res = $csql -> execute(array($auth_code, $expires, $json_data->access_token, $json_data->refresh_token, $_SESSION["login_name"], $state));
		}
	}

	// handle eve SSO callback
	if(isset($_GET["code"])&&$_SESSION["logged_in"]){
		save_auth_code($_GET["code"],$_GET["state"]);
		header("Location:main.php");
  	exit();
	}
?>