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
<br><h2>Search a book</h2><br>

<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-group">
		<label class ="control-label col-sm-5" for="criteria">Search by:</label>
		<div class="col-sm-2">
			<select class ="form-control" name="criteria">
    			<option value='bookId'>Book Id</option>
                <option value='title'>Title</option>
                <option value='pname'>Publisher Name</option>
            </select>
        </div>
        <div class="col-sm-5"></div>
	</div>


    <div class="form-group">
        <label class ="control-label col-sm-5" for="searchText">Search criteria:</label>
        <div class="col-sm-3">  
            <input class="form-control" type="text" name="searchText">
        </div>
        <div class="col-sm-4"></div>
    </div>

    <input class="btn btn-primary" type="submit" name="submit" value="Submit">  
</form>

<?php include 'databaseinfo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
    
    $criteria = $_POST["criteria"];
    $searchText = $_POST["searchText"];
        
    $sql = "SELECT bookId, title, aname, pname, isbn FROM book, author WHERE book.authorId = author.authorId and " . $criteria . " like '%" . $searchText . "%'";
    $result = $conn->query($sql);

    echo "<br><table class='table table-condensed'>\n";
    echo "<tr class='info'><th>Book Id</th><th>Title</th><th>Author Name</th><th>Publisher Name</th><th>ISBN</th></tr>\n";

    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['bookId'] . "</td><td>" . $row['title'] . "</td><td>" . $row['aname'] . "</td><td>" . $row['pname'] . "</td><td>" . $row['isbn'] . "</td></tr>\n";
    }

    echo "</table>";
    $conn->close();
}
?>

<br>
<a href="reader.php">Go back to menu</a>
<br><br>

</div>
</body>
</html>