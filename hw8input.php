<html>
<body>
	<h1> HW 8 input </h1>
	<form action='hw8output.php' method='post'>
	<select name="country_code">
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
				
			$query = "SELECT country_code, country_name FROM Country";
				
			$stmt = $conn->stmt_init();
			$stmt->prepare($query);

			// execute the statement and bind the result
			$stmt->execute();
			$stmt->bind_result($country_code, $country_name);
			$stmt->fetch();
			
			while($row = $stmt->fetch()) {
			   echo '<option value=' . $country_code . '>' . $country_name . '</option>';
			}
				
			$stmt->close();
			$conn->close();
		?>
	</select>
	<input type='submit' value='submit'></input>
	</form>
	
</body>
</html>
