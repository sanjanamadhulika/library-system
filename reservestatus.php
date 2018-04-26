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
<br><h2>List of books reserved with status</h2><br>

<?php include 'databaseinfo.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT reserveId, reserveDateTime, borrowDateTime, returnDateTime, title, copyNo, bname FROM reserveborrow, book, branch WHERE 
    reserveborrow.readerId = '" . $_SESSION['readerId'] . "' and
    reserveborrow.bookId = book.bookId and
    reserveborrow.libId = branch.libId";
        
$result = $conn->query($sql);

echo "<table class='table table-condensed'>\n";
echo "<tr class='info'><th>Reservation Id</th><th>Book Title</th><th>Copy Number</th><th>Branch</th><th>Reservation Time</th><th>Status</th></tr>\n";

while($row = $result->fetch_assoc()) {
	$reserveDateTime = $row['reserveDateTime'];
	$borrowDateTime = $row['borrowDateTime'];
	$returnDateTime = $row['returnDateTime'];

	$status = "Unknown";

	if ($reserveDateTime !== null) {
		if ($borrowDateTime !== null) {
			if ($returnDateTime !== null) {
				$status = "Returned";
			} else {
				$status = "Borrowed";
			}
		} else {
			$startTime = null;
			$endTime = null;
			$tmpReserveTime = new DateTime($reserveDateTime);

			if (time() >= strtotime("18:00:00")) {
				$startTime = new DateTime();
				$startTime->setTime(18, 00, 00);

				$endTime = new DateTime();
				$endTime->setTime(18, 00, 00);
				$endTime->modify('+1 day');			  	
			} else {
				$startTime = new DateTime();
				$startTime->setTime(18, 00, 00);
				$startTime->modify('-1 day');

				$endTime = new DateTime();
				$endTime->setTime(18, 00, 00);
			}

			if ($tmpReserveTime > $startTime and $tmpReserveTime <= $endTime) {
				$status = "Reserved";
			} else {
				$status = "Cancelled";
			}			
		}
	}
    echo "<tr><td>" . $row['reserveId'] . "</td><td>" . $row['title'] . "</td><td>" . $row['copyNo'] . "</td><td>" . $row['bname'] . "</td><td>" . $row['reserveDateTime'] . "</td><td>" . $status . "</td></tr>\n";
}

echo "</table>";

$conn->close();
?>

<br>
<a href="reader.php">Go back to menu</a>
<br><br>

</div>
</body>
</html>