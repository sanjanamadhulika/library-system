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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $readerId = $_POST["readerId"];
    $rname = $_POST["rname"];
    $address = $_POST["address"];
    $phoneno = $_POST["phoneno"];

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql = "INSERT INTO reader VALUES ('". $readerId . "','" . $rname . "','" . $address . "'," . $phoneno . ")";

    if ($conn->query($sql) === TRUE) {
        echo "Reader '" . $rname . "' added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<br><h2>Add a reader</h2><br>
<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-group">
    	<label class="control-label col-sm-5" for="readerId">Reader Id:</label>
    	<div class="col-sm-2">
    		<input class="form-control" type="text" name="readerId">
    	</div>
    	<div class="col-sm-3"></div>
    </div>

    <div class="form-group">
    	<label class="control-label col-sm-5" for="rname">Reader Name:</label>
    	<div class="col-sm-2">
    		<input class="form-control" type="text" name="rname">
    	</div>
    	<div class="col-sm-3"></div>
    </div>

    <div class="form-group">
    	<label class="control-label col-sm-5" for="address">Address:</label>
    	<div class="col-sm-2">
    		<input class="form-control" type="text" name="address">
    	</div>
    	<div class="col-sm-3"></div>
    </div>

    <div class="form-group">
    	<label class="control-label col-sm-5" for="phoneno">Phone Number:</label>
    	<div class="col-sm-2">
    		<input class="form-control" type="number" name="phoneno">
    	</div>
    	<div class="col-sm-3"></div>
    </div>
    
    <input class="btn btn-primary" type="submit" name="submit" value="Submit">  
</form>

<br>
<a href="admin.php">Go back to menu</a>

</body>
</html>
