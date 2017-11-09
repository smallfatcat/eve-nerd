<?php

  include 'db_auth.php';
  $batchSize = 100;
  $killExists = false;
  $killmail_inserts = 0;
  $item_inserts = 0;
  $attacker_inserts = 0;

  $time = -microtime(true);
     // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT * FROM zkill_history WHERE killID NOT IN (SELECT killmail_id FROM killmails) ORDER BY killID DESC LIMIT ".$batchSize;
  $queue = $conn->query($sql);
  $queueLength = mysqli_num_rows($queue);

  $counter =0;
  

  $master = curl_multi_init();

  
  $curl_arr = array();
  $hash_arr = array();

  for($i = 0; ($i < $batchSize && $counter < $queueLength); $i++)
  {
      $row = $queue->fetch_assoc();
      //echo "killID: " . $row["killID"]. " - hash: " . $row["hash"]."<br>";
      $hash_arr[$i] =  $row["hash"];
      $counter ++;
      $curl_arr[$i] = curl_init('https://esi.tech.ccp.is/latest/killmails/' . $row["killID"] . '/' . $row["hash"] . '/?datasource=tranquility');
      curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
      curl_multi_add_handle($master, $curl_arr[$i]);
  }

  do {
      curl_multi_exec($master,$running);
  } while($running > 0);


  for($i = 0; $i < count($curl_arr); $i++)
  {
      
    $killdata = json_decode(curl_multi_getcontent  ( $curl_arr[$i]  ), false);
    /*
    $sql = 'SELECT * FROM `killmails` WHERE `killmail_id` = '. $killdata->killmail_id;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo 'Kill in table<br>';
        $killExists = true;
    }
    else{
        echo 'Kill not in table<br>';
        $killExists = false;
    }
    */

    $sql = "INSERT INTO killmails (killmail_id"
        .", killmail_hash"
        .", killmail_time"
        .", character_id"
        .", damage_taken"
        .", ship_type_id"
        .", corporation_id"
        .", alliance_id"
        .", solar_system_id"
        .", position)"
        ." VALUES (". $killdata->killmail_id
        .", '" . $hash_arr[$i] . "'"
        .", '" . $killdata->killmail_time . "'"
        .", " . (!isset($killdata->victim->character_id)?0:$killdata->victim->character_id)
        .", " . $killdata->victim->damage_taken
        .", " . $killdata->victim->ship_type_id
        .", " . (!isset($killdata->victim->corporation_id)?0:$killdata->victim->corporation_id)
        .", " . (!isset($killdata->victim->alliance_id)?0:$killdata->victim->alliance_id)
        .", " . $killdata->solar_system_id
        .", '" . "x:".$killdata->victim->position->x. "y:".$killdata->victim->position->y. "z:".$killdata->victim->position->z . "'"
        .")";

    //echo $sql . '<br>';
    if(!$killExists){
        if ($conn->query($sql) === TRUE) {
            //echo 'New record created successfully<br>';
            $killmail_inserts++;
        } else {
            //echo 'Error: ' . $sql . '<br>' . $conn->error.'<br>';
        }
    }

      foreach($killdata->attackers as $attacker) {
        $sql = "INSERT INTO attackers (character_id"
          .", corporation_id"
          .", alliance_id"
          .", faction_id"
          .", security_status"
          .", damage_done"
          .", final_blow"
          .", ship_type_id"
          .", weapon_type_id"
          .", killmail_id)"
          ." VALUES (". (!isset($attacker->character_id)?0:$attacker->character_id)
          .", " . (!isset($attacker->corporation_id)?0:$attacker->corporation_id)
          .", " . (!isset($attacker->alliance_id)?0:$attacker->alliance_id)
          .", " . (!isset($attacker->faction_id)?0:$attacker->faction_id)
          .", " . $attacker->security_status
          .", " . $attacker->damage_done
          .", " . ($attacker->final_blow?1:0)
          .", " . (!isset($attacker->ship_type_id)?0:$attacker->ship_type_id)
          .", " . (!isset($attacker->weapon_type_id)?0:$attacker->weapon_type_id)
          .", " . $killdata->killmail_id
          .")";
        
        //echo $sql . '<br>';
        if(!$killExists){
          if ($conn->query($sql) === TRUE) {
              //echo 'New record created successfully<br>';
              $attacker_inserts++;
          } else {
              //echo 'Error: ' . $sql . '<br>' . $conn->error.'<br>';
          }
        }
      }

      foreach($killdata->victim->items as $item) {

        $sql = "INSERT INTO items (item_type_id"
            .", singleton"
            .", flag"
            .", quantity_destroyed"
            .", quantity_dropped"
            .", killmail_id)"
            ." VALUES (". (!isset($item->item_type_id)?0:$item->item_type_id)
            .", " . ($item->singleton?1:0)
            .", " . (!isset($item->flag)?0:$item->flag)
            .", " . (!isset($item->quantity_destroyed)?0:$item->quantity_destroyed)
            .", " . (!isset($item->quantity_dropped)?0:$item->quantity_dropped)
            .", " . $killdata->killmail_id
            .")";
            //echo $sql . '<br>';
                        if(!$killExists){
        if ($conn->query($sql) === TRUE) {
            //echo 'New record created successfully<br>';
            $item_inserts++;
        } else {
            //echo 'Error: ' . $sql . '<br>' . $conn->error.'<br>';
        }
      }
    }
  }

  foreach($curl_arr as $ch){
      curl_close($ch);
  }

  $conn->close();

  $time_end = microtime();
  $time += microtime(true);

  echo 'Time: ' . $time 
      .' batch: ' . $batchSize
      .' Memory: ' . memory_get_peak_usage()
      .' killmails: ' . $killmail_inserts
      .' attackers: ' . $attacker_inserts
      .' items: ' . $item_inserts;
?>