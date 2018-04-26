<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<style>
		body {
			background-image: url("background.jpg");
		}
	</style>
</head>

<body>
<?php include 'databaseinfo.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    $readerId = $_POST["id"];
          
    $sql = "SELECT readerId, rname FROM reader WHERE readerId = '" . $readerId . "'";
    $result = $conn->query($sql);

	if ($result->num_rows === 1) {
		while($row = $result->fetch_assoc()) {
			$_SESSION['readerId'] = $row['readerId'];
			$_SESSION['rname'] = $row['rname'];
    	}
    }

    $conn->close();
}

echo '<div class="container">';
echo '<div class="row" style="margin-top:50px;">';
echo '<div class="col-lg-8 col-lg-offset-3">';
echo '<ul style="margin-bottom: 30px;" class="nav nav-pills">';

if (isset($_SESSION['readerId'])) {
	echo '<br><h2>Welcome ' . $_SESSION['rname'] . ' (Reader)</h2><br>';
	echo '<a class="btn-link btn-lg" href="searchbook.php">Search a book</a> <br>';
	echo '<a class="btn-link btn-lg" href="bookreserve.php">Book reserve</a> <br>';
	echo '<a class="btn-link btn-lg" href="bookcheckout.php">Book checkout</a> <br>';
	echo '<a class="btn-link btn-lg" href="bookreturn.php">Book return</a> <br>';
	echo '<a class="btn-link btn-lg" href="fine.php">Fine for books borrowed as of today</a> <br>';
	echo '<a class="btn-link btn-lg" href="reservestatus.php">Books reserved with status</a> <br>';
	echo '<a class="btn-link btn-lg" href="booksbypublisher.php">Books published by publisher</a><br>';
	echo '<br><a class="btn btn-primary" text-center href="index.php">Quit</a>';
} else {
	echo "<h2>Invalid Reader</h2>";
	echo '<a href="index.php">Go back to menu</a>';
}

echo '</ul>';
echo '</div>';
echo '</div>';
echo '</div>';
?>
</body>
</html>