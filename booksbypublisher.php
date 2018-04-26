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
 
$pname = "Invalid";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pname = $_POST["publisher"];
}
?>

<br><h2>Books published by publisher</h2><br>
<form class="form-horizontal" id="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-group">
        <label class ="control-label col-sm-5" for="publisher">Select Publisher:</label>
		<div class="col-sm-3">
			<select class ="form-control" name="publisher">
			<?php 
            $sql = "SELECT pname FROM publisher;";
            $result = $conn->query($sql);
            
            while($row = $result->fetch_assoc()) {
                if ($pname === $row['pname']) {
                    echo "<option value='" . $row['pname'] . "' selected='selected'>" . $row['pname'] . "</option>";
                }
                else {
                    echo "<option value='" . $row['pname'] . "'>" . $row['pname'] . "</option>";
                }
            }
            ?>
            </select>
		</div>
		<div class="col-sm-4"></div>
	</div>
     
    <input class="btn btn-primary" type="submit" name="submit" value="Submit">  
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {       
    $sql = "SELECT bookId, title FROM book where pname = '" . $pname . "'";
    $result = $conn->query($sql);

    echo "<br><table class='table table-condensed'>\n";
    echo "<tr class='info'><th>Book Id</th><th>Title</th></tr>\n";

    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['bookId'] . "</td><td>" . $row['title'] . "</td></tr>\n";
    }

    echo "</table>";
}
?>

<br>
<a href="reader.php">Go back to menu</a>

<?php $conn->close(); ?>

</div>
</body>
</html>