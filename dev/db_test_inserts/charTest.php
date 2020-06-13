<?php
 		// curl -X POST "https://esi.evetech.net/latest/universe/names/?datasource=tranquility" -H "accept: application/json" -H "Content-Type: application/json" -d "[ 90159655,90159583,90159041]"
		$vars = '[90159655,90159583,90159041]';
		print $vars;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://esi.evetech.net/latest/universe/names/?datasource=tranquility");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);  //Post Fields
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);

		curl_close ($ch);

		print  $server_output ;
?>