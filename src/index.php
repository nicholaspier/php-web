<html>
<head>
	<title><?php echo gethostname(); ?></title>
</head>
<body>
	<h1>Welcome to <?php echo gethostname(); ?></h1>
	<hr />
	<h2>Step 1: PHP</h2>
        <?php
	// Verify that we're getting environment variables (debugging)
	//print_r(array_values($_ENV));

	// Pull MySQL variables from  environment variables
       	$servername = $_ENV["APP_DB_SRV"];
	$username = $_ENV["APP_DB_USER"];
	$password = $_ENV["APP_DB_PASS"];
	$dbname = $_ENV["APP_DB_NAME"];
        ?>

	<?php echo phpinfo(1); ?>
	<hr />
	<h2>Step 2: MySQL Connection</h2>
	 <?php

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);

		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		}
		echo "<p>Connected successfully</p>";
	?>
	<hr />
	<h2>Step 3: MySQL Query</h2>
	<?php
		$sql = "SELECT * FROM staff_list;";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "id: " . $row["ID"]. " - Name: " . $row["name"]. " - Address:" . $row["address"]. ' ' . $row["city"]. ', ' .$row["country"]. "<br>";
		}
		} else {
			echo "0 results";
		}
		$conn->close();
	?> 
</body>
</html>
