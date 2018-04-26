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
<div class="container text-center">

<?php include 'databaseinfo.php';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reserveId = $_POST["reserveId"];
        
    $sql = "UPDATE reserveborrow SET returnDateTime = current_time() WHERE reserveId = " . $reserveId;

    if ($conn->query($sql) === TRUE) {
        echo "Book returned successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<br><h2>Return a book</h2><br>
<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-group">
		<label class ="control-label col-sm-4" for="reserveId">Books Checked Out:</label>
		
        <div class="col-sm-4">
			<select class ="form-control" name="reserveId">
			<?php 
                $sql = "SELECT reserveId, title, copyNo, bname FROM reserveborrow, book, branch WHERE 
                    reserveborrow.readerId = '" . $_SESSION['readerId'] . "' and
                    reserveborrow.bookId = book.bookId and
                    reserveborrow.libId = branch.libId and
                    reserveDateTime is not null and
                    borrowDateTime is not null and
                    returnDateTime is null";
                
                $result = $conn->query($sql);

                while($row = $result->fetch_assoc()) {
                    $bookName = $row['title'] . " #" . $row['copyNo'] . " @ " . $row['bname'];
                    echo "<option value='" . $row['reserveId'] . "'>" . $bookName . "</option>";
                }
            ?>
	        </select>	
		</div>
        
		<div class="col-sm-4"></div>
	</div>
    
    <input class="btn btn-primary" type="submit" name="submit" value="Return">  
</form>

<br>
<a href="reader.php">Go back to menu</a>

<?php $conn->close(); ?>

</div>
</body>
</html>