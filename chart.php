<?php
session_start();
if (isset($_SESSION['username']) && $_SESSION['valid'] == true) {
    echo "Welcome , " . $_SESSION['username'] . "!";
} else {
     header('Location: index.php');
}

//mysql installation
$servername = "";
$dbname = "";
$username = "";
$password = "";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$dev_id = "SELECT DISTINCT dev_id FROM sensor";
$dev_id_res = $conn->query($dev_id);
while ($dev_id_data = $dev_id_res->fetch_assoc()){
    $dev_id_data_lit[] = $dev_id_data;
}

$list = array_column($dev_id_data_lit, 'dev_id');

if(isset($_GET['sensorid']) && !empty($_GET['sensorid'])){
$select_sensor_id = $_GET['sensorid'];
} else {
    $select_sensor_id = $list[0];
}


if(isset($_GET['limit']) && !empty($_GET['limit'])){
	
	if($_GET['limit'] == 76)
	{
		$limit = "";
	}
	else
	{
	$limit = "LIMIT ".$_GET['limit'];
	}
} else {
    $limit = "LIMIT 50";
}



$sql = "SELECT id, temp, humid, dev_time ,timestamp FROM sensor WHERE dev_id = ".$select_sensor_id." order by id desc ".$limit;
//echo $sql;
$result = $conn->query($sql);

while ($data = $result->fetch_assoc()){
    $sensor_data[] = $data;
}

$readings_time = array_column($sensor_data, 'timestamp');
//var_dump($readings_time);
//print_r($value1);
//print_r($readings_time);
//$readings_time[$i] = date("Y-m-d H:i:s", strtotime($reading));
// ******* Uncomment to convert readings time array to your timezone ********
// $i = 0;
foreach ($readings_time as $reading){
    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
   //$readings_time[$i] = date("Y-m-d H:i:s", strtotime($reading));
    // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
    $readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading + 5 hours"));
    $i += 1;
}

$value1 = json_encode(array_reverse(array_column($sensor_data, 'temp')), JSON_NUMERIC_CHECK);
$value2 = json_encode(array_reverse(array_column($sensor_data, 'humid')), JSON_NUMERIC_CHECK);
$value3 = array_reverse(array_unique(array_column($sensor_data, 'dev_id')));
$reading_time = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);
//print_r($reading_time);

//$reading_time = json_encode(array_reverse(array_column($sensor_data, 'timestamp')), JSON_NUMERIC_CHECK);

$result->free();
$conn->close();
?>

<!DOCTYPE html>
<html>
<meta http-equiv="refresh" content="300"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <style>
    body {
      min-width: 310px;
    	max-width: 1280px;
    	height: 500px;
      margin: 0 auto;
    }
    h2 {
      font-family: Arial;
      font-size: 2.5rem;
      text-align: center;
    }
  </style>
  <body>
    <h2>Weight</h2>
	<form method="get">
    <select name='sensorid' class="form-control col-lg-3 form-control-sm"  onchange='if(this.value != 0) { this.form.submit(); }'>
	<option value="<?=$select_sensor_id?>" selected>Select sensor</option>
         
	<?php foreach ($list as $val)
	{
		echo '<option value="'.$val.'">'.$val.'</option>
		';
	}	?>
	     </select>
		 
		   <select name='limit' class="form-control col-lg-3 form-control-sm"  onchange='if(this.value != 0) { this.form.submit(); }'>
	<option value='0'>Select number of rows</option>
	<option value='100'>100</option>
	<option value='500'>500</option>
	<option value='76'>All</option>
         
	     </select>
</form>



    <div id="chart-temperature" class="container"></div>
    <!-- <div id="chart-humidity" class="container"></div> -->
<script>

var value1 = <?php echo $value1; ?>;
var value2 = <?php echo $value2; ?>;
var reading_time = <?php echo $reading_time; ?>;

var chartT = new Highcharts.Chart({
  chart:{ renderTo : 'chart-temperature' },
  title: { text: 'Weight' },
  series: [{
    showInLegend: false,
    data: value1
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#059e8a' }
  },
  xAxis: { 
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Weight (Kg)' }
    //title: { text: 'Temperature (Fahrenheit)' }
  },
  credits: { enabled: false }
});

var chartH = new Highcharts.Chart({
  chart:{ renderTo:'chart-humidity' },
  title: { text: 'Humidity' },
  series: [{
    showInLegend: false,
    data: value2
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    }
  },
  xAxis: {
    type: 'datetime',
    //dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Humidity (%)' }
  },
  credits: { enabled: false }
});


</script>
</body>
</html>