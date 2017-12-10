<?php
  session_start();
?>

<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
<meta http-equiv='content-type' content='text/html; charset=ISO-8859-1'>
  <title>location test</title>
  <link href="https://fonts.googleapis.com/css?family=Cuprum" rel="stylesheet">
  
  <link rel='stylesheet' href='libs/jquery-ui.css' type='text/css'>
  <link rel='stylesheet' href='main.css' type='text/css'>

  <script src='libs/jquery-3.2.1.js'></script>
  <script src='libs/jquery-ui.js'></script>
  <script src='util.js'></script>
  <script src='bigVars.js'></script>
  
<?php
  echo  '<script>g_user_name = "' . $_SESSION["login_name"] . '";'
          .'g_access_token = "'   . $_SESSION["access_token"] . '";'
          .'g_expires = "'        . $_SESSION["expires"] . '";'
          .'g_character_id = "'   . $_SESSION["character_id"] . '";'
          .'var myVar = setInterval(function(){ getLocation() }, 10000);'
          .'</script><script src='location.js'></script>';
?>
  

</head>
<body>
<p class="s4">Location test</p>
<p class="s2" id="text_location"></p>

</html>
