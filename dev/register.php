<?php
	session_start();
	include 'db_auth.php';
	// Connect to DB
	try {
	  $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
	} catch (PDOException $e) {
	  die('Connection failed: ' . $e->getMessage());
	}

	// Check if password was retpyed
	$password_retyped = false;
	if( isset($_POST["login_pass1"]) && isset($_POST["login_pass2"])){
		if($_POST["login_pass1"] == $_POST["login_pass2"]){
			$password_retyped = true;
		}
	}

	// Check if username is taken
	$username_taken = false;
	if(isset($_POST["login_name"])){
		$userChecksql = $conn->prepare("SELECT login_name FROM login WHERE login_name = ?");
		$userCheckres = $userChecksql -> execute( array( $_POST["login_name"]) );
		if($userChecksql->rowCount() > 0){
			$username_taken = true;
		}
	}

	if(isset($_POST["register"]) && $password_retyped){
		$login_name = $_POST["login_name"];
		$login_pass = password_hash($_POST["login_pass1"],PASSWORD_DEFAULT);
		$state = random_int(0, 60000);
		if(!$username_taken){
			//$csql = $conn->prepare("INSERT INTO nerdDB.login (login_name, login_pass) VALUES ('sfc','letmein')");
			$csql = $conn->prepare("INSERT INTO nerdDB.login (login_name, login_pass, state) VALUES (?, ?, ?)");
			$res = $csql -> execute(array($login_name, $login_pass, $state));
			$_SESSION["login_name"] = $login_name;
			$_SESSION["state"] = $state;
			$_SESSION["logged_in"] = true;
			header("Location:main.php");
	  	exit();
  	}
	}
	if( !isset($_POST["register"]) || $username_taken || !$password_retyped ){
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
  <!-- <script src='bigVars.js'></script> -->
  <!-- <script src='main.js'></script> -->
  
</head>
<body>
<div id="register_form" class="ui-widget">
	<?php
		if(!$password_retyped && isset($_POST["register"]) ){
			echo 'Password fields did not match<br>';
		}
		if($username_taken && isset($_POST["register"]) ){
			echo 'Username Taken<br>';
		}
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
		echo '<a href="main.php">Cancel Registration</a>';
	}
?>
</div>

</body>
<footer>
<!-- <span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=GpjqsUHZBOORWqtTHxqwn92eBTPUSe8EGuPfscLLpSoVOA2Nbuyt378i7GAA"></script></span>
 --></footer>
</html>