<html>
<body>
	<h1>HW 8 output</h1>
	<?php
		$config = parse_ini_file("../private/config.ini");
		$server = $config["servername"];
		$username = $config["username"];
		$password = $config["password"];
		$database = "cmooring_DB";

		// connect
		$conn = mysqli_connect($server, $username, $password, $database);

		// check connection
		if (!$conn) {
		  die("Connection failed: " . mysqli_connect_error()); 
		}
		
		$country_code = $_POST["country_code"];
		$query = "SELECT c.country_code, c.country_name, c.gdp, c.inflation, COUNT(*) FROM Country c JOIN Province p USING(country_code) WHERE country_code=?";
		
		$stmt = $conn->stmt_init();
		$stmt->prepare($query);
		
		$stmt->bind_param("s", $country_code);

		// execute the statement and bind the result
		$stmt->execute();
		$stmt->bind_result($country_code, $country_name, $gdp, $inflation, $province_count);
		$stmt->fetch();
		
		echo '<p>Country name: ' . $country_name . '</p>';
		echo '<p>Country gdp: ' . $gdp . '</p>';
		echo '<p>Country inflation: ' . $inflation . '%</p>';
		echo '<p>Number of provinces: ' . $province_count . '</p>';
		
		$stmt->close();
		$conn->close();
	?>
	
	<?php
		$config = parse_ini_file("../private/config.ini");
		$server = $config["servername"];
		$username = $config["username"];
		$password = $config["password"];
		$database = "cmooring_DB";

		// connect
		$conn = mysqli_connect($server, $username, $password, $database);

		// check connection
		if (!$conn) {
		  die("Connection failed: " . mysqli_connect_error()); 
		}
		
		$country_code = $_POST["country_code"];
		
		$query = "SELECT city_name, province_name, population FROM City WHERE country_code=?;";
		
		$stmt = $conn->stmt_init();
		$stmt->prepare($query);
		
		$stmt->bind_param("s", $country_code);

		// execute the statement and bind the result
		$stmt->execute();
		$stmt->bind_result($city_name, $province_name, $population);
		
		// get number of rows so we can check if there are none
		$stmt->store_result();
		$rows_left = $stmt->num_rows();
		
		if($rows_left < 1) { // no cities in this country
			echo '<h2>No cities in this country</h2>';
		} else { // list city information in a table
			echo '<table>';
			echo '<tr> <th>City Name</th> <th>Province Name</th> <th>Population</th></tr>';
			while($stmt->fetch()) {
				echo '<tr>';
				echo '<td>' . $city_name . '</td>';
				echo '<td>' . $province_name . '</td>';
				echo '<td>' . $population . '</td>';
				echo '</tr>';
			}
			echo '</table>';
		}
		
		$stmt->close();
		$conn->close();
	?>

</body>
</html>
