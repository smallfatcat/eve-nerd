<?php
  $servername = "localhost";
	$username = "enAdmin";
	$password = "c64amigacat232323";
	$dbname = "nerdDB";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	}

  $day 		= str_pad($_GET["d"],2,'0',STR_PAD_LEFT);
  $month 	= str_pad($_GET["m"],2,'0',STR_PAD_LEFT);
  $year 	= $_GET["y"];
  // Create a stream
	$opts = [
    "http" => [
    "method" => "GET",
    "header" => //"Accept-Encoding: gzip\r\n" .
    "User-Agent: eve-nerd.com\r\n"
    ]
	];

	$context = stream_context_create($opts);

	//$url = "https://eve-nerd.com/zkill_history" . $year . $month . $day;
	$url = "https://zkillboard.com/api/history/" . $year . $month . $day . "/";
	$data = file_get_contents($url, false, $context);
	//$data = gzinflate($data);
	//var_dump($data);
	$jsondata = json_decode($data, true);
	//var_dump($jsondata);
	foreach($jsondata as $killID => $Hash) {
		echo "killID=" . $killID . ", Hash=" . $Hash;
		echo "<br>";
		$sql = "INSERT INTO zkill_history (killID, hash) VALUES (" . $killID . ", '" . $Hash . "')";

		if ($conn->query($sql) === TRUE) {
    	echo "New record created successfully";
		} else {
    	echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	$conn->close();

?>