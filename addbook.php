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
    $bookId = $_POST["bookId"];
    $authorId = $_POST["author"];
    $pname = $_POST["publisher"];
    $title = $_POST["title"];
    $isbn = $_POST["isbn"];
    $publicationDate = date('Y-m-d', strtotime($_POST["publicationDate"]));
        
    $sql = "INSERT INTO book VALUES ('". $bookId . "','" . $authorId . "','" . $pname. "','" . $title . "','" . $isbn . "','" . $publicationDate . "')";

    if ($conn->query($sql) === TRUE) {
        echo "Book '" . $title . "' added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<br><h2>Add a book</h2><br>
<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<div class="form-group">
		<label class="control-label col-sm-5" for="title">Title:</label>
		<div class="col-sm-2">
			<input class="form-control" type="text" name="title">
		</div>
		<div class="col-sm-5"></div>
	</div>
    
    <div class="form-group">
		<label class="control-label col-sm-5" for="bookId">Book Id:</label>
		<div class="col-sm-2">
			<input class="form-control" type="text" name="bookId">
		</div>
		<div class="col-sm-5"></div>
	</div>	
    
	<div class="form-group">
		<label class="control-label col-sm-5" for="isbn">ISBN:</label>
		<div class="col-sm-2">
			<input class="form-control" type="text" name="isbn">
		</div>
		<div class="col-sm-5"></div>
	</div>
       
    <div class="form-group">
		<label class ="control-label col-sm-5" for="author">Author:</label>
		<div class="col-sm-2">		
			<input class="form-control" type="text" name="author">
		</div>
		<div class="col-sm-5"></div>
	</div>
    
    <div class="form-group">
		<label class ="control-label col-sm-5" for="publisher">Publisher:</label>
		<div class="col-sm-2">
        <input class="form-control" type="text" name="publisher">
	     
	</div>
		<div class="col-sm-5"></div>
	</div>
    
    <div class="form-group">
		<label class="control-label col-sm-5" for="publicationDate">Publication Date:</label>
		<div class="col-sm-2">
			<input class="form-control" type="date" name="publicationDate">
		</div>
		<div class="col-sm-5"></div>
	</div>

    <input class="btn btn-primary" type="submit" name="submit" value="Submit">  
</form>

<br>
<a href="admin.php">Go back to menu</a>

<?php $conn->close(); ?>

</body>
</html>
