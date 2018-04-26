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
<br><h2>Fine for books borrowed as per today</h2><br>

<?php include 'databaseinfo.php';
// Open session to get the readerId
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT a.readerid, a.rname, datediff(returnDateTime,datetobereturned) * 0.20 as fine, a.bookid, DATE(DATETOBERETURNED), DATE(CURRENT_DATE), DATEDIFF(returnDateTime, DATETOBERETURNED) AS DAYS
from reader b, book c,
(SELECT a.readerid, rname, date_add(borrowDateTime, INTERVAL 20 day) as datetobereturned, bookid, a.returnDateTime
FROM reserveborrow  a, reader  b
where  a.readerid = b.readerid
and date(returnDateTime) is not null) a
where b.readerid = a.readerid
and b.readerId = '" . $_SESSION['readerId'] . "'
and datediff(returnDateTime, datetobereturned) > 0 
and c.bookid = a.bookid

UNION

SELECT a.readerid, a.rname, datediff(CURRENT_DATE,datetobereturned) * 0.20 as fine, a.bookid, DATE(DATETOBERETURNED), DATE(CURRENT_DATE), DATEDIFF(CURRENT_DATE, DATETOBERETURNED) AS DAYS
from reader b, book c,
(SELECT a.readerid, rname, date_add(borrowDateTime, INTERVAL 20 day) as datetobereturned, bookid
FROM reserveborrow a, reader b
where a.readerid = b.readerid
and date(returnDateTime) is null) a
where b.readerid = a.readerid
and b.readerId = '" . $_SESSION['readerId'] . "'
and datediff(CURRENT_DATE, datetobereturned) > 0 
and c.bookid=a.bookid;";

$result = $conn->query($sql);

echo "<table class='table table-condensed'>\n";
echo "<tr class='info'><th>Reader ID</th><th>Reader Name</th><th>Book ID</th><th>Fine</th><th>Days</th></td>\n";

while($row = $result->fetch_assoc()) {
    echo "<tr><td>". $row['readerid'] . "</td><td>" . $row['rname'] . "</td><td>" . $row['bookid']. "</td><td>" . $row['fine'] . "</td><td>" . $row['DAYS'] . "</td></tr>\n";
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