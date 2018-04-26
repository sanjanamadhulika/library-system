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
<br><h2>Average fine paid per reader</h2><br>

<?php include 'databaseinfo.php';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT a.readerid, avg(fine) as fine, a.rname
FROM reserveborrow b,
(SELECT a.readerid, a.rname, datediff(returnDateTime,datetobereturned) * 0.20 as fine, a.bookid, DATE(DATETOBERETURNED), DATE(CURRENT_DATE), DATEDIFF(returnDateTime,DATETOBERETURNED) AS DAYS
FROM reader b, book c,
(SELECT a.readerid, rname, date_add(borrowDateTime, INTERVAL 20 day) as datetobereturned, bookid,a.returnDateTime
FROM reserveborrow a, reader b
WHERE a.readerid = b.readerid
and date(returnDateTime) is not null) a
WHERE b.readerid = a.readerid
and datediff(returnDateTime,datetobereturned) > 0 
and c.bookid=a.bookid) a
WHERE a.readerid = b.readerid
GROUP BY a.readerid, a.rname;";

$result = $conn->query($sql);

echo "<table class='table table-condensed'>\n";
echo "<tr class='info'><th>Reader ID</th><th>Reader Name</th><th>Average fine</th></tr>\n";

while($row = $result->fetch_assoc()) {
    echo "<tr><td>". $row['readerid'] . "</td><td>" . $row['rname'] . "</td><td>" . $row['fine'] . "</td></tr>\n";
}
echo "</table>";

$conn->close();
?>

<br>
<a href="admin.php">Go back to menu</a>

</div>
</body>
</html>