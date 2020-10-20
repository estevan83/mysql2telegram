<?php

/*
Last version 20/10/2020
Author: Estefan Civera (estefan.civera@algoma.it - www.algoma.it)


php mysql2telegram.php [hst=host] [prt=port] dbn=name usr=user pwd=password bot=token dst=destination qry=query [fln=filename]
php mysql2telegram.php dbn=database usr=user pwd=password bot="123465789:qwertyuiopASDFGHJKLZXCVBNM-1qwer" dst=111222333 qry="select 1" tlt="Report ABC"
php mysql2telegram.php dbn=database usr=user pwd=password bot="123465789:qwertyuiopASDFGHJKLZXCVBNM-1qwer" dst=111222333 fln=test.sql tlt="Report SQL"

*/


$params = array(
     'hst'  =>  'localhost'
    ,'prt'  =>  3306
    ,'dbn'  =>  null
    ,'usr'  =>  null
    ,'pwd'  =>  null
    ,'bot'  =>  null
    ,'dst'  =>  null
    ,'qry'  =>  null
    ,'fln'  =>  null
    ,'tlt'  => '*** REPORT ***'
);

if ($argv) {

    // loop through each element in the $argv array
    foreach($argv as $value)
    {
        $value = trim($value);
        echo $value;
        $it = explode("=",$value);

        $k = $it[0];
        $v = $it[1];

        $params[$k] = $v;
    }
}

$params['sql'] = isset($params['fln']) ? file_get_contents($params['fln']) : $params['qry'];
//print_r($params);
//die();

// --------------------------------------------------------------------------------------------
$host = $params['hst'];
$db_name = $params['dbn'];
$db_user = $params['usr'];
$db_password = $params['pwd'];


$curl = curl_init();
$link = mysqli_connect($host, $db_user, $db_password, $db_name); 
  
if ($link === false) { 
    die("ERROR: Could not connect. ". mysqli_connect_error()); 
} 



if ($res = mysqli_query($link, $params['sql'])) { 
    
    

    $numrows = mysqli_num_rows($res);

  //  $telmsg .= " $numrows ROWS FOUND" . PHP_EOL;

    $telmsg = json_decode('"\uD83D\uDCCA"') . $params['tlt'] . json_decode('"\uD83D\uDCCA"') .  ' >> ' .  $numrows . ' <<' .PHP_EOL;

    $r = 1;
    if($numrows  > 0) { 

        $numfields = mysqli_num_fields($res);
      
        while ($row = mysqli_fetch_array($res,MYSQLI_ASSOC)) { 
            $telmsg.= json_decode('"\uD83C\uDFC1"'). " $r/$numrows ". PHP_EOL;

            foreach ($row as $key => $value) {
                
                $telmsg.= "$key => $value". PHP_EOL;
                //print_r($arr);
            }
            $r++;
            
        }
   
        
        mysqli_free_result($res); 
    } 
    else { 
        
    } 

// -------------------------------------------------------------------------------------------

    // Send telegram message
    $apitoken = $params['bot'];
    $message  = $telmsg;
    $chatid = $params['dst'];    
            
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.telegram.org/bot".$apitoken."/sendmessage",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "chat_id=".$chatid."&text=".urlencode($message),
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    if ($err) {
        echo $err;
    } else {

        print_r($response);
    }
} 
else { 
    echo "ERROR: Could not able to execute $sql. "  .mysqli_error($link); 
} 

mysqli_close($link);
curl_close($curl);
