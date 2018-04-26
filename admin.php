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
    
    $id = $_POST["id"];
    $password = $_POST["password"];
        
    $sql = "SELECT id FROM admin WHERE id = '" . $id . "' and password = '" . $password . "'";
    $result = $conn->query($sql);

	if ($result->num_rows === 1) {
		$_SESSION['adminId'] = $id;
	}

    $conn->close();
}

echo '<div class="container">';
echo '<div class="row" style="margin-top:50px;">';
echo '<div class="col-lg-8 col-lg-offset-3">';
echo '<ul style="margin-bottom: 30px;" class="nav nav-pills">';

if (isset($_SESSION['adminId'])) {
	echo '<br><h2>Welcome ' . $_SESSION['adminId'] . ' (Administrator)</h2></br>';
	echo '<a class="btn-link btn-lg" href="addbook.php">Add a book</a> <br>';
	echo '<a class="btn-link btn-lg" href="addbookcopy.php">Add a book copy</a> <br>';
	echo '<a class="btn-link btn-lg" href="searchstatus.php">Search book copy and check its status</a> <br>';
	echo '<a class="btn-link btn-lg" href="addreader.php">Add new reader</a> <br>';
	echo '<a class="btn-link btn-lg" href="branch_information.php">Print branch information</a> <br>';
	echo '<a class="btn-link btn-lg" href="top10borrowers.php">Print top 10 most frequent borrowers in a branch</a> <br>';
	echo '<a class="btn-link btn-lg" href="top10books.php">Print top 10 most borrowed books in a branch</a> <br>';
	echo '<a class="btn-link btn-lg" href="averagefine.php">Find the average fine paid per reader</a><br>';
	echo '<br><a class="btn btn-primary" href="index.php">Quit</a>';	
} else {
	echo "<h2>Invalid Administrator</h2>";
	echo '<a href="index.php">Go back to menu</a>';
}

echo '</ul>';
echo '</div>';
echo '</div>';
echo '</div>';
?>
</body>
</html>