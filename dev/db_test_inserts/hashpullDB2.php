<?php

    include 'db_auth.php';
    $batchSize = 10000;
    $error_counter = 0;

try {
    $conn = new PDO( "mysql:" . "host=".$servername.";" . "dbname=".$dbname, $username, $password);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}
    $sql_total = "SELECT COUNT(killID) AS total FROM zkill_history WHERE killID NOT IN (SELECT killmail_id FROM killmails)";
    $total_res = $conn->query($sql_total);
    $total_left_row = $total_res->fetch(PDO::FETCH_ASSOC);
    $total_left = $total_left_row["total"];
    
    $sql = "SELECT zkill_history.killID, zkill_history.hash FROM zkill_history LEFT JOIN killmails ON zkill_history.killID = killmails.killmail_id WHERE killmails.killmail_id IS NULL ORDER BY killID DESC LIMIT ".$batchSize;
    $queue = $conn->query($sql);

    $counter = 0;
    $time_start = microtime(true);

    $master = curl_multi_init();
    echo "### " . $queue->rowCount() . " records in Queue. Total Left: " . $total_left;

    $ksql = $conn->prepare("INSERT INTO killmails (killmail_id, killmail_hash, killmail_time, character_id, damage_taken, ship_type_id, corporation_id, alliance_id, solar_system_id, position, faction_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    $asql = $conn->prepare("INSERT INTO attackers (character_id, corporation_id, alliance_id, faction_id, security_status, damage_done, final_blow, ship_type_id, weapon_type_id, killmail_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $isql = $conn->prepare("INSERT INTO items (item_type_id, singleton, flag, quantity_destroyed, quantity_dropped, killmail_id) VALUES (?, ?, ?, ?, ?, ?)");

    while($counter < $queue->rowCount()) {
        $curl_arr = array();
        $hashes = array();
        for($i = 0; ($i < 100 && $counter < $queue->rowCount()); $i++)
        {
            $row = $queue->fetch(PDO::FETCH_ASSOC);
            $counter ++;
            $hashes[$i] = $row["hash"];
            $curl_arr[$i] = curl_init('https://esi.evetech.net/v1/killmails/' . $row["killID"] . '/' . $row["hash"] . '/?datasource=tranquility');
            curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($master, $curl_arr[$i]);
        }

        do {
            curl_multi_exec($master,$running);
        } while($running > 0);

        
        for($i = 0; $i < count($curl_arr); $i++)
        {
            $json_data = curl_multi_getcontent  ( $curl_arr[$i]  );
            if (strpos($json_data, '502 Bad Gateway') !== false) {
                echo 'E';
                $error_counter = $error_counter + 1;
                continue;
            }
            $killdata = json_decode(curl_multi_getcontent  ( $curl_arr[$i]  ), false);

            $res = $ksql -> execute(array( 
                $killdata->killmail_id,
                $hashes[$i],
                $killdata->killmail_time,
                (!isset($killdata->victim->character_id)?0:$killdata->victim->character_id),
                $killdata->victim->damage_taken,
                (!isset($killdata->victim->ship_type_id)?0:$killdata->victim->ship_type_id),
                (!isset($killdata->victim->corporation_id)?0:$killdata->victim->corporation_id),
                (!isset($killdata->victim->alliance_id)?0:$killdata->victim->alliance_id),
                $killdata->solar_system_id,
                "x:".$killdata->victim->position->x. "y:".$killdata->victim->position->y. "z:".$killdata->victim->position->z,
                (!isset($killdata->victim->faction_id)?0:$killdata->victim->faction_id)
                ));
            
            if(!$res){
                echo $json_data;
                die(sprintf("Error: %s\n", $ksql->errorInfo()));
            }
            echo "K";

            foreach($killdata->attackers as $attacker) {
     
                $res = $asql -> execute(array(
                    (!isset($attacker->character_id)?0:$attacker->character_id),
                    (!isset($attacker->corporation_id)?0:$attacker->corporation_id),
                    (!isset($attacker->alliance_id)?0:$attacker->alliance_id),
                    (!isset($attacker->faction_id)?0:$attacker->faction_id),
                    $attacker->security_status,
                    $attacker->damage_done,
                    ($attacker->final_blow?1:0),
                    (!isset($attacker->ship_type_id)?0:$attacker->ship_type_id),
                    (!isset($attacker->weapon_type_id)?0:$attacker->weapon_type_id),
                    $killdata->killmail_id
                    ));
            if(!$res){
                echo $json_data;
                die(sprintf("Error: %s\n", $asql->errorInfo()));
            }
                    echo "A";
            }
     
            foreach($killdata->victim->items as $item) {
     
                $res = $isql -> execute(array(
                    (!isset($item->item_type_id)?0:$item->item_type_id),
                    ($item->singleton?1:0),
                    (!isset($item->flag)?0:$item->flag),
                    (!isset($item->quantity_destroyed)?0:$item->quantity_destroyed),
                    (!isset($item->quantity_dropped)?0:$item->quantity_dropped),
                    $killdata->killmail_id
                    ));
            if(!$res){
                echo $json_data;
                die(sprintf("Error: %s\n", $isql->errorInfo()));
            }
                    echo "I";
            }

        }
        if($counter == $queue->rowCount()) break;

    }

    $time_end = microtime(true);
    $time = $time_end - $time_start;

    echo "<br><br><br><b>FINISHED</b>";
    echo 'Time: ' . $time . '<br>';
    echo 'Memory: ' . memory_get_peak_usage(). '<br>';
    echo 'Errors: ' . $error_counter. '<br>';
 
?>