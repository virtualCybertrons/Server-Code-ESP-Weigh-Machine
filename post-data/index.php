<?php
error_reporting( E_ALL ); 
//date_default_timezone_set("Asia/Kolkata");

//mysql installation
$servername = "";
$dbname = "";
$username = "";
$password = "";

// Keep this API Key value to be compatible with the ESP32 code provided in the project page. If you change this value, the ESP32 sketch needs to match
$api_key_value = "asus_123";

$api_key = $temp = $humid = $id = $time_from_device = "";
function abs_diff($v1, $v2) {
    $diff = $v1 - $v2;
    return $diff < 0 ? (-1) * $diff : $diff;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["key"]);
    if($api_key == $api_key_value) {
        $temp = test_input($_POST["temp"]);
        $humid = test_input($_POST["humid"]);
        $id = test_input($_POST["id"]);
		$time_from_device = test_input($_POST["time"]);
        
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
if(abs_diff($old_val ,$temp) > 1)
{
        $sql = "INSERT INTO sensor (temp, humid, dev_id, dev_time)
        VALUES ('" . $temp . "', '" . $humid . "', '" . $id . "', '" . $time_from_device . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
}
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}