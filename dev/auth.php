<?php
	session_start();
	include 'db_auth.php';

	function save_auth_code($auth_code, $state){
		$c = curl_init('https://login.eveonline.com/oauth/token');
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, array(	'Authorization: Basic YmRlMzkyY2Q2NDI5NGE4Nzk2ODVhZDJkOWY0NWEwOGE6NXYxSHJzQmdlUW9xeWJVenBlNm5ET20zQkk2ZHpzZ2g2MUlma0pUQg==',
																								'Content-Type: application/x-www-form-urlencoded',
																								'Host: login.eveonline.com'));
		curl_setopt($c, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&code='.$auth_code);

		$auth_page = curl_exec($c);
		curl_close($c);
		
		$auth_data = json_decode($auth_page);
		if(isset($auth_data->error)){
			echo $auth_data->error.'<br>';
			echo $auth_data->error_description.'<br>';
		}
		else{
			$access_token = $auth_data->access_token;

			$c = curl_init('https://login.eveonline.com/oauth/verify');
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_HTTPHEADER, array(	'Authorization: Bearer '.$access_token,
																									'Content-Type: application/x-www-form-urlencoded',
																									'User-Agent: eve-nerd.com',
																									'Host: login.eveonline.com'));
			$verify_page = curl_exec($c);
			curl_close($c);
			
			$verify_data = json_decode($verify_page);

			/*echo 'access_token: '				.$auth_data->access_token.'<br>';
			echo 'token_type: '					.$auth_data->token_type.'<br>';
			echo 'expires_in: '					.$auth_data->expires_in.'<br>';
			echo 'refresh_token: '			.$auth_data->refresh_token.'<br>';

			echo 'CharacterID: '				.$verify_data->CharacterID.'<br>';
			echo 'CharacterName: '			.$verify_data->CharacterName.'<br>';
			echo 'ExpiresOn: '					.$verify_data->ExpiresOn.'<br>';
			echo 'Scopes: '							.$verify_data->Scopes.'<br>';
			echo 'TokenType: '					.$verify_data->TokenType.'<br>';
			echo 'CharacterOwnerHash: '	.$verify_data->CharacterOwnerHash.'<br>';*/
			$t = time();
			$t +=  (int)$auth_data->expires_in;
			$expires = date("Y-m-d H:i:s",$t);
			try {
				  $conn = new PDO( "mysql:" . "host=".$GLOBALS['servername'].";" . "dbname=".$GLOBALS['dbname'], $GLOBALS['username'], $GLOBALS['password']);
			} catch (PDOException $e) {
			  die('Connection failed: ' . $e->getMessage());
			}
			//UPDATE login SET auth_code = 'xxx' WHERE login_name = 'sfc' AND state = 'state123';
			$csql = $conn->prepare("UPDATE nerdDB.login SET auth_code = ?, expires = ?, access_token = ?, refresh_token = ? , character_id = ?, character_name = ?, character_owner_hash = ?, token_type = ? WHERE login_name = ? AND state = ?");
			$res = $csql -> execute(array($auth_code, 
																		$expires,
																		$auth_data->access_token,
																		$auth_data->refresh_token,
																		$verify_data->CharacterID,
																		$verify_data->CharacterName,
																		$verify_data->CharacterOwnerHash,
																		$verify_data->TokenType,
																		$_SESSION["login_name"],
																		$state));
			$_SESSION["auth_status"] = true;
			$_SESSION["character_name"] = $verify_data->CharacterName;
			$_SESSION["character_id"] = $verify_data->CharacterID;
			$_SESSION["access_token"] = $auth_data->access_token;
			$_SESSION["expires"] = $expires;
		}
	}

	// handle eve SSO callback
	if(isset($_GET["code"])&&$_SESSION["logged_in"]){
		save_auth_code($_GET["code"],$_GET["state"]);
		header("Location:main.php");
  	exit();
	}
?>