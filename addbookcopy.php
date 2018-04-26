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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookId = $_POST["book"];
    $libId = $_POST["branch"];
    $copyNumber = $_POST["copyNumber"];
        
    $sql = "INSERT INTO bookcopy VALUES (". $copyNumber . ",'" . $bookId . "','" . $libId . "')";

    if ($conn->query($sql) === TRUE) {
        echo "Book copy added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<br><h2>Add a book copy</h2><br>
<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
   <div class="form-group">
		<label class ="control-label col-sm-5" for="book">Book:</label>
		<div class="col-sm-2">
			
		<select class ="form-control" name="book">
		<?php 
        $sql = "SELECT bookId, title FROM book;";
        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['bookId'] . "'>" . $row['title'] . "</option>";
        }
        ?>
        </select>
		
		</div>
		<div class="col-sm-3"></div>
	</div>
	
   <div class="form-group">
		<label class ="control-label col-sm-5" for="branch">Branch:</label>
		<div class="col-sm-2">
			
		<select class ="form-control" name="branch">
        <?php 
        $sql = "SELECT libId, bname FROM branch;";
        $result = $conn->query($sql);

        while($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['libId'] . "'>" . $row['bname'] . "</option>";
        }
        ?>
        </select>
		
		</div>
		<div class="col-sm-3"></div>
	</div>
	
    <div class="form-group">
		<label class="control-label col-sm-5" for="copyNumber">Copy Number:</label>
		<div class="col-sm-2">
			<input class="form-control" type="text" name="copyNumber">
		</div>
		<div class="col-sm-3"></div>
	</div>
     
    <input class="btn btn-primary" type="submit" name="submit" value="Submit">  
</form>

<br>
<a href="admin.php">Go back to menu</a>

<?php $conn->close(); ?>

</body>
</html>
