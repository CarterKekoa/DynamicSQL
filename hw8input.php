<html>
<body>
	<h1> HW 8 Input </h1>
	<form action='hw8output.php' method='post'>
		<select name="country_code">
			<?php
				/*
				* Carter Mooring
				* hw8output.php
				* CPSC321 Databases
				* Nov. 24th, 2020
				* Description: This program connects to a database previously made on ada.
				* 				It will provide the user with DB information on their selection from 
				*				hw8input.php. 
				* Info:	This is displayed on https://barney.gonzaga.edu/~cmooring/hw8output.php
				*/
				$config = parse_ini_file("../private/config.ini");
				$server = $config["servername"];
				$username = $config["username"];
				$password = $config["password"];
				$database = "cmooring_DB";

				$conn = mysqli_connect($server, $username, $password, $database); // Database Coneection

				// Runs if connection fails
				if (!$conn) {
				die("Connection failed: " . mysqli_connect_error()); 
				}
					
				$query = "SELECT country_code, country_name FROM country";
				$stmt = $conn->stmt_init();
				$stmt->prepare($query);

				// Execute query above
				$stmt->execute();
				$stmt->bind_result($country_code, $country_name);
				$stmt->fetch();
				
				while($row = $stmt->fetch()) {
				echo '<option value=' . $country_code . '>' . $country_name . '</option>';
				}
				
				// finished so close
				$stmt->close();
				$conn->close();
			?>
		</select>
		<input type='submit' value='submit'></input>
	</form>
</body>
</html>
