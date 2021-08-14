<?php
//mysql installation
$servername = "private-ilidb-do-user-8237642-0.b.db.ondigitalocean.com";
$dbname = "iot";
$username = "doadmin";
$password = "uvyvqf31u11t4pgv";

$api_key_value = "asus_123";

$api_key = $temp = $humid = $id = $time_from_device = "";

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
        
        $sql = "INSERT INTO sensor (temp, humid, dev_id, dev_time)
        VALUES ('" . $temp . "', '" . $humid . "', '" . $id . "', '" . $time_from_device . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
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