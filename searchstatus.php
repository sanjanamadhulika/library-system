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
<br><h2>Search a book with status</h2><br>

<form class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-group">
		<label class ="control-label col-sm-5" for="criteria">Search by:</label>
		<div class="col-sm-2">	
			<select class="form-control" name="criteria">
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
        
    $sql = "SELECT bookcopy.bookId, bookcopy.copyNo, reserveDateTime, borrowDateTime, returnDateTime, bname, aname, pname, title
			FROM bookcopy left outer join (
				SELECT * 
    			FROM reserveborrow 
    			WHERE reserveDateTime is not null and (
					((reserveDateTime > addtime(date_add(current_date(), interval -1 day), '18:00:00') and reserveDateTime <= addtime(date_add(current_date(), interval 0 day), '18:00:00')) and
						returnDateTime is null and borrowDateTime is null) or 
					(borrowDateTime is not null and returnDateTime is null))) as res 
			ON bookcopy.bookId = res.bookId and  bookcopy.libId = res.libId and bookcopy.copyNo = res.copyNo, book, author, branch
			WHERE bookcopy.bookId = book.bookId 
			and book.authorId = author.authorId 
			and bookcopy.libId = branch.libId
			and book." . $criteria . " like '%" . $searchText . "%'";
	
    $result = $conn->query($sql);
	
	echo "<br><table class='table table-condensed'>\n";
    echo "<tr class='info'><th>Book Id</th><th>Title</th><th>Branch</th><th>Copy No</th><th>Author Name</th><th>Publisher Name</th><th>Status</th></tr>\n";

    while($row = $result->fetch_assoc()) {
		$reserveDateTime = $row['reserveDateTime'];
		$borrowDateTime = $row['borrowDateTime'];
		$returnDateTime = $row['returnDateTime'];

		$status = "Available";

		# If we got joined data from reserveborrow
		if ($reserveDateTime !== null) {
			if ($borrowDateTime !== null) {
				$status = "Borrowed";
			} else {
				$status = "Reserved";		
			}
		}
	    echo "<tr><td>" . $row['bookId'] . "</td><td>" . $row['title'] . "</td><td>" . $row['bname'] . "</td><td>" . $row['copyNo'] . "</td><td>" . $row['aname'] . "</td><td>" . $row['pname'] . "</td><td>" . $status . "</td></tr>\n";
	}

	echo "</table>";
	$conn->close();
}
?>

<br>
<a href="admin.php">Go back to menu</a>
<br><br>

</div>
</body>
</html>