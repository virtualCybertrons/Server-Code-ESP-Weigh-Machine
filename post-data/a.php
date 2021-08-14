<?php
error_reporting( E_ALL ); 

//mysql installation
$servername = "";
$dbname = "";
$username = "";
$password = "";

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. If you change this value, the ESP32 sketch needs to match
$api_key_value = "asus_123";

$api_key = $temp = $humid = $id = $time_from_device = "";

        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
  if ($result = $conn -> query("SELECT temp FROM sensor WHERE id=(SELECT max(id) FROM sensor)")) {
  $old_val = $result -> fetch_assoc();
  $old_val = $old_val['temp'];
  echo $old_val;
  $result -> free_result();
}
        $conn->close();
    
