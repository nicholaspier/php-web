<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="refresh" content="60">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

  <!-- Custom CSS -->
  <style>
    :root {
      --dark-gray: #59595b;
      --light-green: #a0cd4e;
      --silver: #d2d3d4;
      --lightblue: #96d2f3;
      --pale-teal: #8cd2ce;
      --dark-green: #56883d;
      --medium-green: #80b141;
      --cornflower-blue: #6497d0;
      --blue-green: #0e8e97;
    }
  </style>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

	<title><?php echo gethostname(); ?></title>
</head>
<body style="background-color: var(--dark)">
  <div class="container-fluid">
    <div class="row flex-xl-nowrap">
      <!-- Main Content -->
      <div class="col-sm-12 bd-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="background-color: var(--dark)">
          <a class="navbar-brand" href="#">
            <code>Hostname: <?php echo gethostname(); ?></code>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link" href="https://docs.docker.com/engine/reference/run/" target="_blank">Docker Docs</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="https://github.com/nicholaspier/php-web" target="_blank">Github</a>
              </li>
            </ul>
          </div>
        </nav>
        <div class="accordion" id="accordian">
          <div class="card">
            <div class="card-header" id="heading1">
              <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapseOne">
                  Step 1: PHP
                </button>
              </h2>
            </div>
            <div id="collapse1" class="collapse show" aria-labelledby="heading1" data-parent="#accordian">
              <div class="card-body">
                      <?php
                      // boolean for tracking if we have the required env variables
                      $env_check = 1;
                      if (!isset($_ENV["MYSQL_HOST"], $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"])) {
                        echo '<div class="alert alert-danger" role="alert">'."Make sure to set the DB environment variables.</div>";
                        // set env_check to 0 to affect future if statements
                        $env_check = 0;
                      }
                      else {
                      ?>
                
                      <table class="table">
                        <thead class="thead-dark">
                        <tr>
                        <th scope="col">env variable</th>
                        <th scope="col">value</th>
                        </tr>
                        </thead>
                    <?php
                      // Print Env Variables as html table rows
                      array_walk($_ENV, function(&$value, $key) {
                        echo "<tr> <td>$key</td><td>$value</td></tr>";
                      });
                    }
                  ?>
                </table> 
              </div>
            </div>
          </div><!--accordian-card-->
          <div class="card">
            <div class="card-header" id="heading2">
              <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
                  Step 2: MySQL Connection
                </button>
              </h2>
            </div>
            <div id="collapse2" class="collapse show" aria-labelledby="heading2" data-parent="#accordian">
              <div class="card-body">
                    <?php
                    // Create connection
                    $conn_check = 1;

                    if ($env_check) {
                      $conn = new mysqli($_ENV["MYSQL_HOST"], $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DATABASE"], 3306);

                      // Check connection
                      if ($conn->connect_error) {
                        die('<div class="alert alert-danger" role="alert">' . "Connection failed: " . $conn->connect_error . "</div>");
                        $conn_check = 0;
                      }
                      else {
                        echo '<div class="alert alert-success" role="alert">Connected successfully</div>';
                      }
                    }
                    else {
                      echo '<div class="alert alert-danger" role="alert">Connection failed</div>';
                    }
                    ?>
              </div>
            </div>
          </div><!--accordian-card-->
          <div class="card">
            <div class="card-header" id="heading3">
              <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
                  Step 3: MySQL Query Results
                </button>
              </h2>
            </div>
            <div id="collapse3" class="collapse show" aria-labelledby="heading3" data-parent="#accordian">
              <div class="card-body">
                <?php
                  // instantiate query
                  $sql = "SELECT * FROM staff_list;";

                  // Check connection
                  if (!$env_check OR !$conn_check) {
                      die('<div class="alert alert-danger" role="alert">' . "Connection failed: Connection failed.</div>");
                  }
                  else {
                    // Execute query
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                      // Our query has rows
                      echo '<div class="alert alert-success" role="alert">' . "Query Succeeded.</div>";
                    ?>
                    <table class="table">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">id</th>
                          <th scope="col">name</th>
                          <th scope="col">address</th>
                          <th scope="col">city</th>
                          <th scope="col">country</th>
                        </tr>
                      </thead>
                      <?php
                        // Print query rows as table rows
                        while($row = $result->fetch_assoc()) { 
                      ?>
                        <tr><td><?php echo $row["ID"]; ?></td><td><?php echo $row["name"]; ?></td><td><?php echo $row["address"]; ?></td><td><?php echo $row["city"]; ?></td><td><?php echo $row["country"]; ?></td></tr>
                      <?php } ?>
                      </table>
                  <?php
                    }
                    else {
                        echo "0 results";
                    }
                    // Close Connection
                    $conn->close();
                  }
                  ?> 
              </div>
            </div>
          </div><!--accordian-card-->
        </div><!--accordian-->
      </div><!--column-->
    </div><!--row-->
  </div><!--container-->
</body>
</html>
