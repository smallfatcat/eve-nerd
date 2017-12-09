<?php
	session_start();
	include 'db_auth.php';
	// Get refresh_token
	try {
	  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
	} catch (PDOException $e) {
	  die('Connection failed: ' . $e->getMessage());
	}

	$csql = $conn->prepare("SELECT access_token, expires, refresh_token FROM login WHERE login_name = ?");
	$res = $csql -> execute(array($_SESSION["login_name"]));
	if($csql->rowCount() > 0){
		$row = $csql->fetch(PDO::FETCH_ASSOC);
		$refresh_token = $row['refresh_token'];
	}

	// Get new access_token
	$c = curl_init('https://login.eveonline.com/oauth/token');
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_HTTPHEADER, array(	'Authorization: Basic YmRlMzkyY2Q2NDI5NGE4Nzk2ODVhZDJkOWY0NWEwOGE6NXYxSHJzQmdlUW9xeWJVenBlNm5ET20zQkk2ZHpzZ2g2MUlma0pUQg==',
																							'Content-Type: application/x-www-form-urlencoded',
																							'Host: login.eveonline.com'));
	curl_setopt($c, CURLOPT_POSTFIELDS, 'grant_type=refresh_token&refresh_token='.$refresh_token);
	$auth_page = curl_exec($c);
	curl_close($c);
	$auth_data = json_decode($auth_page);

	// Calculate expire time
	$t = time();
	$t +=  (int)$auth_data->expires_in;
	$expires = date("Y-m-d H:i:s",$t);

	// Update table with new access_token and refresh_token
	$update_access_sql = $conn->prepare("UPDATE nerdDB.login SET expires = ?, access_token = ?, refresh_token = ? WHERE login_name = ?");
	$update_access_res = $update_access_sql -> execute(array(
		$expires,
		$auth_data->access_token,
		$auth_data->refresh_token,
		$_SESSION["login_name"]
	));
	$_SESSION['access_token'] = $auth_data->access_token;
	$_SESSION['expires'] = $expires;
	echo $_SESSION['access_token'];
?>