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

<?php include 'databaseinfo.php';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$libId = "Invalid";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $libId = $_POST["branch"];
} 
?>

<br><h2>Top 10 most borrowed books</h2><br>
<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-group">
		<label class ="control-label col-sm-5" for="branch">Select a branch:</label>
		<div class="col-sm-3">		
			<select class ="form-control" name="branch">
			<?php 
            $sql = "SELECT bname, libId FROM branch";
            $result = $conn->query($sql);
    
            while($row = $result->fetch_assoc()) {
                if ($libId === $row['libId']) {
                    echo "<option value='" . $row['libId'] . "' selected='selected'>" . $row['bname'] . "</option>";
                } else {
                    echo "<option value='" . $row['libId'] . "'>" . $row['bname'] . "</option>";            
                }
            }
            ?>
            </select>
        </div>
		<div class="col-sm-3"></div>
	</div>
  
	<input class="btn btn-primary" type="submit" name="submit" value="Submit">  
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "SELECT TITLE, COUNT(*) as total FROM reserveborrow, book
            WHERE libid = '" . $libId . "' and 
            reserveborrow.bookId = book.bookId and
            reserveborrow.borrowDateTime is not null
            GROUP by book.bookId
            ORDER BY COUNT(*) desc
            LIMIT 10";

    $result = $conn->query($sql);

    echo "<br><table class='table table-condensed'>\n";
    echo "<tr class='info'><th>Book Title</th><th>Times Borrowed</th></tr>\n";

    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['TITLE'] . "</td><td>" . $row['total'] . "</td></tr>\n";
    }

    echo "</table>";
}
?>

<br>
<a href="admin.php">Go back to menu</a>

<?php $conn->close(); ?>

</body>
</html>