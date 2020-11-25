<html>
<body>
	<h1>HW 8 Output</h1>
	<?php
		/*
 		* Carter Mooring
 		* hw8output.php
 		* CPSC321 Databases
 		* Nov. 24th, 2020
 		* Description: This program connects to a database previously made on ada.
		* 				It will provide the user with a dropdown box with options from the  database
		*				to be selected and output by hw8output.php
		* Info:	This is used on https://barney.gonzaga.edu/~cmooring
 		*/

		// Access config file to grab info to connect to DB
		$config = parse_ini_file("../private/config.ini");
		$server = $config["servername"];
		$username = $config["username"];
		$password = $config["password"];
		$database = "cmooring_DB";

		// Connect to DB
		$conn = mysqli_connect($server, $username, $password, $database);

		if (!$conn) {
		  die("Connection failed: " . mysqli_connect_error()); 
		}
		
		//Prepare Query
		$country_code = $_POST["country_code"];
		$query = "SELECT c.country_code, c.country_name, c.gdp, c.inflation, COUNT(*) FROM country c JOIN province p USING(country_code) WHERE country_code=?";
		$stmt = $conn->stmt_init();
		$stmt->prepare($query);
		$stmt->bind_param("s", $country_code);

		// Query Created
		$stmt->execute();
		$stmt->bind_result($country_code, $country_name, $gdp, $inflation, $province_count);
		$stmt->fetch();
		
		//Display
		echo '<p>Country name: ' . $country_name . '</p>';
		echo '<p>Country gdp: ' . $gdp . '</p>';
		echo '<p>Country inflation: ' . $inflation . '%</p>';
		echo '<p>Number of provinces: ' . $province_count . '</p>';
		
		//Finished
		$stmt->close();
		$conn->close();
	?>
	<?php
		$config = parse_ini_file("../private/config.ini");
		$server = $config["servername"];
		$username = $config["username"];
		$password = $config["password"];
		$database = "cmooring_DB";

		// Connect to DB
		$conn = mysqli_connect($server, $username, $password, $database);
		// Test connection
		if (!$conn) {
		  die("Connection failed: " . mysqli_connect_error()); 
		}
		
		//Prepare Query
		$country_code = $_POST["country_code"];
		$query = "SELECT city_name, province_name, population FROM city WHERE country_code=?;";
		$stmt = $conn->stmt_init();
		$stmt->prepare($query);
		$stmt->bind_param("s", $country_code);

		// Query Created
		$stmt->execute();
		$stmt->bind_result($city_name, $province_name, $population);
		
		// For interation
		$stmt->store_result();
		$rows_left = $stmt->num_rows();
		
		if($rows_left < 1) {
			echo '<h2>No cities in this country</h2>';
		} else {
			//Display
			echo '<table>';
			echo '<tr> <th>City Name  |  </th> <th>Province Name  |  </th> <th>Population</th></tr>';
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
