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
<div class="container text-center">
<br><h2>Branch Information</h2><br>

<?php include 'databaseinfo.php';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT bname, location FROM branch";
$result = $conn->query($sql);

echo "<table class='table table-condensed'>\n";
echo "<tr class='info'><th>Branch Name</th><th>Branch Location</th></tr>\n";

while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row['bname'] . "</td><td>" . $row['location'] . "</td></tr>\n";
}
echo "</table>";

$conn->close();
?>

<a href="admin.php">Go back to menu</a>
</div>
</body>
</html>