<?php
	session_start();
?>

<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
<meta http-equiv='content-type' content='text/html; charset=ISO-8859-1'>
  <title>eve-nerd.com</title>
  <link href="https://fonts.googleapis.com/css?family=Cuprum" rel="stylesheet">
  
  <link rel='stylesheet' href='libs/jquery-ui.css' type='text/css'>
  <link rel='stylesheet' href='main.css' type='text/css'>

  <style>
  	.ui-autocomplete-loading {
    	background: white url("libs/images/ui-anim_basic_16x16.gif") right center no-repeat;
  	}
  </style>

  <script src='libs/jquery-3.2.1.js'></script>
  <script src='libs/jquery-ui.js'></script>
  <script src='util.js'></script>
  <script src='bigVars.js'></script>
  <script src='main.js'></script>
 
	<?php
		// If logged in and authed on ESI

		if(isset($_SESSION["logged_in"])  /*&& isset($_SESSION["auth_status"])*/){
			if(isset($_SESSION["auth_status"]) && $_SESSION["auth_status"] && $_SESSION["logged_in"]){
				echo 	'<script>'
					.'g_user_name = "' . $_SESSION["login_name"] . '";'
					.'g_access_token = "' . $_SESSION["access_token"] . '";'
					.'g_expires = "' . $_SESSION["expires"] . '";'
					.'g_character_id = "'   . $_SESSION["character_id"] . '";'
					.'</script>'
					.'<script src="location.js"></script>'
					.'<script>'
					.'getLocation();'
					.'var myVar = setInterval(function(){ getLocation() }, 10000);'
					.'</script>';
			}
		}
	?>

</head>
<body>
<div id="login_form" class="ui-widget">
<?php
	// if logged in
	
	if(isset($_SESSION["logged_in"])){
		if($_SESSION["logged_in"]){
			echo 'User: ' . $_SESSION["login_name"];
			if(isset($_SESSION["character_name"])){
				echo '<br>Character: ' . $_SESSION["character_name"];
				//https://imageserver.eveonline.com/Character/1460129480_32.jpg
				echo '<br><img src="https://imageserver.eveonline.com/Character/'.$_SESSION["character_id"].'_32.jpg">';
				echo '<p class="s2" id="text_ship"></p>';
				echo '<p class="s2" id="text_location"></p>';
			}
			// logout button
			echo '<form action="/dev/login.php" method="post">';
			echo '<input type="hidden" name="logout" value="1">';
			echo '<input type="submit" value="Logout">';
			echo '</form>';
			if(!isset($_SESSION["auth_status"]) || !$_SESSION["auth_status"]){
				$sso_link = 'https://login.eveonline.com/oauth/authorize/?response_type=code&redirect_uri=http%3A%2F%2Feve-nerd.com%2Fdev%2Fauth.php&client_id=bde392cd64294a879685ad2d9f45a08a&scope=esi-location.read_location.v1%20esi-location.read_ship_type.v1%20esi-location.read_online.v1%20esi-fleets.write_fleet.v1%20esi-fleets.read_fleet.v1%20esi-assets.read_corporation_assets.v1&state='.$_SESSION["state"];
				echo '<a href="'.$sso_link.'"><img src="./EVE_SSO_Login_Buttons_Small_Black.png"></a>';
				//https://www.eve-nerd.com/dev/main.php?code=F4UPlvIKQ3sczvesm9nOAOCVJqwPWxbV5pG-5o6P-b_sChG__GqHY6tI4L9GZXcM0&state=uniquestate123
			}
		}
	}

	// if logged out
		if(!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]){
				// login form
				echo '<form action="/dev/login.php" method="post">';
				echo '<label for="login_name">Username:</label>';
				echo '<input type="text" name="login_name">';
				echo '<label for="login_pass">Password:</label>';
				echo '<input type="password" name="login_pass">';
				echo '<input type="hidden" name="login" value="1">';
				echo '<input type="submit" value="Login">';
				echo '</form>';
				echo '<a href="register.php">Register</a>';
		}
?>
</div>
<p class="s4">eve-nerd.com</p>
<p class="s2">New tools for a new eve. Battle reports, intel, mapping and more</p>
<p class="s2">Coming soon...</p>
<div id="searchbox_solar_system" class="ui-widget">
	<label for="input_solar_system">Solar System: </label>
	<input id="input_solar_system" onkeypress="handle_solar_system_keypress(event)">
	<button class="ui-button ui-widget" onclick="input_solar_system_click()">Search</button>
</div>
<div id="searchbox_ship_type" class="ui-widget">
	<label for="input_ship_type">Ship Type: </label>
	<input id="input_ship_type" onkeypress="handle_ship_type_keypress(event)">
	<button class="ui-button ui-widget" onclick="input_ship_type_click()">Search</button>
</div>
<div id="searchbox_character_name" class="ui-widget">
	<label for="input_character_name">Character name: </label>
	<input id="input_character_name" onkeypress="handle_character_name_keypress(event)">
	<button class="ui-button ui-widget" onclick="input_character_name_click()">Search</button>
</div>
<div id="table_result" class="left">
	
</div>
<div id="table_kill_detail" class="left">
	
</div>
<div id="table_kill_items" class="left">
	
</div>
</body>
<footer>
<!-- <span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=GpjqsUHZBOORWqtTHxqwn92eBTPUSe8EGuPfscLLpSoVOA2Nbuyt378i7GAA"></script></span>
 --></footer>
</html>