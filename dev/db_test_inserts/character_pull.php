<?php

    include 'db_auth.php';
    $batchSize = 500;

try {
    $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

    $sql = "SELECT DISTINCT character_id FROM attackers WHERE character_id NOT IN (SELECT character_id FROM characters) ORDER BY character_id LIMIT ".$batchSize;
    $queue = $conn->query($sql);

    $counter = 0;
    $time_start = microtime(true);

    $master = curl_multi_init();
    echo "### " . $queue->rowCount() . " records in Queue. ###<br> ";

    $csql = $conn->prepare("INSERT INTO characters (character_id, character_name) VALUES (?, ?)");
    
    while($counter < $queue->rowCount()) {
        $curl_arr = array();
        $char_list = '';
        //$hashes = array();
        for($i = 0; ($i < $batchSize && $counter < $queue->rowCount()); $i++)
        {
            $row = $queue->fetch(PDO::FETCH_ASSOC);
            $counter ++;
            //$hashes[$i] = $row["hash"];
            //if($row["character_id"]!=0){
                    $char_list =  $row["character_id"].($i==0?'':',').$char_list;
            //}
        }
        echo $char_list;
        $characterTest = '['.$char_list.']';

        echo '------------';
        echo $characterTest;
        // https://esi.evetech.net/latest/characters/
        // https://esi.tech.ccp.is/latest/characters/names/?character_ids=90098579,90096827,90096643&datasource=tranquility
        // curl -X POST "https://esi.evetech.net/latest/universe/names/?datasource=tranquility" -H "accept: application/json" -H "Content-Type: application/json" -d "[ 90159655,90159583,90159041]"
        $curl_arr[0] = curl_init();
        $headers = [
            'accept: application/json',
            'Content-Type: application/json'
        ];
        
        //curl_setopt($curl_arr[0], CURLOPT_HTTPHEADER, $headers);
        
        curl_setopt($curl_arr[0], CURLOPT_URL,"https://esi.evetech.net/latest/universe/names/?datasource=tranquility");
        curl_setopt($curl_arr[0], CURLOPT_POST, 1);
        curl_setopt($curl_arr[0], CURLOPT_POSTFIELDS, $characterTest);
        curl_setopt($curl_arr[0], CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($master, $curl_arr[0]);

        do {
            curl_multi_exec($master,$running);
        } while($running > 0);


        for($i = 0; $i < count($curl_arr); $i++)
        {
            
            $rawdata = curl_multi_getcontent  ( $curl_arr[0]  );
            var_dump($rawdata);
            $chardata = json_decode($rawdata, false);
            //echo $chardata;
            var_dump($chardata);
            foreach ($chardata as $character) {
            	$res = $csql -> execute(array( 
                $character->id,
                $character->name
            	));
            	if(!$res){
                die(sprintf("Error: %s\n", $csql->errorInfo()));
            	}
            	echo "C";
            }
            
            

	      }
        if($counter == $queue->rowCount()) break;

    }

    $time_end = microtime(true);
    $time = $time_end - $time_start;

    echo "<br><br><br><b>FINISHED</b>";
    echo 'Time: ' . $time . '<br>';
    echo 'Memory: ' . memory_get_peak_usage(). '<br>';
 
?>