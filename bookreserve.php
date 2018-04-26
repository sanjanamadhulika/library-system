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

$libId = "Invalid";
$bookId = "Invalid";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $libId = $_POST["branch"];
    $bookId = $_POST["book"];
    $copyNo = isset($_POST["copyNumber"]) ? $_POST["copyNumber"] : null;
    $submitType = $_POST["submitType"];

    if ($libId !== "Invalid" and $bookId !== "Invalid" and $copyNo and $submitType === 'DoReserve') {
    	$sql = "INSERT INTO reserveborrow VALUES (". time() . "," . $copyNo . ",'" . $bookId . "','" . $libId . "','" . $_SESSION['readerId'] . "',null,null,current_time())";

	    if ($conn->query($sql) === TRUE) {
	        echo "Book reserved successfully";
	    } else {
	        echo "Error: " . $sql . "<br>" . $conn->error;
	    }
    }
}

if (time() >= strtotime("18:00:00")) {
    $sql = "SELECT count(*) as total, readerId
            FROM reserveborrow 
            WHERE readerId = '" . $_SESSION['readerId'] . "' and 
                returnDateTime is null and
                reserveDateTime is not null and (
                    (reserveDateTime > addtime(date_add(current_date(), interval 0 day), '18:00:00') and 
                        reserveDateTime <= addtime(date_add(current_date(), interval 1 day), '18:00:00')) or 
                    borrowDateTime is not null)
            GROUP BY readerId";
} else {
    $sql = "SELECT count(*) as total, readerId
            FROM reserveborrow 
            WHERE readerId = '" . $_SESSION['readerId'] . "' and 
                returnDateTime is null and
                reserveDateTime is not null and (
                    (reserveDateTime > addtime(date_add(current_date(), interval -1 day), '18:00:00') and 
                        reserveDateTime <= addtime(date_add(current_date(), interval 0 day), '18:00:00')) or 
                    borrowDateTime is not null)
            GROUP BY readerId";
}

$reserveCount = 0;
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    $reserveCount = $row['total'];
}

// Only show the menu if the reserve/borrow count is less than 10
if ($reserveCount < 10) {
    echo "<br><h2>Reserve a book</h2> <h5>(reserved/borrowed: " . $reserveCount . ")</h5><br>"
?>

<script>
function loadData(submitType) {
    document.forms["myForm"].elements["submitType"].value = submitType;
    document.getElementById("myForm").submit();
}
</script>

<form id="myForm" class="form-horizontal" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-group">
		<label class ="control-label col-sm-5" for="branch">Branch:</label>
        <div class="col-sm-2">		
			<select class ="form-control" name="branch" onchange="loadData('Fetch')">
				<option value="Invalid">Please Select</option>
                <?php 
                $sql = "SELECT libId, bname FROM branch;";
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
		<div class="col-sm-5"></div>
	</div>

	<div class="form-group">
		<label class ="control-label col-sm-5" for="branch">Book:</label>
		<div class="col-sm-2">		
			<select class ="form-control" name="book" onchange="loadData('Fetch')">
    			<option value="Invalid">Please Select</option>
                <?php 
                $sql = "SELECT distinct(book.bookId), title FROM book, bookcopy where bookcopy.libId = '" . $libId . "' and book.bookId = bookcopy.bookId";
                $result = $conn->query($sql);
                
                while($row = $result->fetch_assoc()) {
                    if ($bookId === $row['bookId']) {
                        echo "<option value='" . $row['bookId'] . "' selected='selected'>" . $row['title'] . "</option>";
                    } else {
                        echo "<option value='" . $row['bookId'] . "'>" . $row['title'] . "</option>";
                    }
                }
                ?>
            </select>
		</div>
		<div class="col-sm-5"></div>
	</div>
     
    <div class="form-group">
		<label class ="control-label col-sm-5" for="copyNumber">Copy Number:</label>
		<div class="col-sm-2">
            <select class ="form-control" name="copyNumber">
            <?php 
            if (time() >= strtotime("18:00:00")) {
                $sql = "SELECT copyNo FROM bookcopy WHERE libId = '" . $libId . "' and bookId = '" . $bookId . "' and copyNo NOT IN (
                			SELECT copyNo from reserveborrow where
                				reserveborrow.libId = '" . $libId . "' and 
        				        reserveborrow.bookId = '" . $bookId . "' and
                                reserveborrow.borrowDateTime is null and
                                reserveborrow.returnDateTime is null and
        				        reserveborrow.reserveDateTime > addtime(date_add(current_date(), interval 0 day), '18:00:00') and
        				        reserveborrow.reserveDateTime <= addtime(date_add(current_date(), interval 1 day), '18:00:00'));";
            } else {
                $sql = "SELECT copyNo FROM bookcopy WHERE libId = '" . $libId . "' and bookId = '" . $bookId . "' and copyNo NOT IN (
                            SELECT copyNo from reserveborrow where
                                reserveborrow.libId = '" . $libId . "' and 
                                reserveborrow.bookId = '" . $bookId . "' and
                                reserveborrow.borrowDateTime is null and
                                reserveborrow.returnDateTime is null and
                                reserveborrow.reserveDateTime > addtime(date_add(current_date(), interval -1 day), '18:00:00') and
                                reserveborrow.reserveDateTime <= addtime(date_add(current_date(), interval 0 day), '18:00:00'));";            
            }

            $result = $conn->query($sql);

            while($row = $result->fetch_assoc()) {
            	echo "<option value='" . $row['copyNo'] . "'>" . $row['copyNo'] . "</option>";
            }
            ?>
            </select>
		</div>
		<div class="col-sm-5"></div>
	</div>
    
    <input type="hidden" name="submitType">
    <input class="btn btn-primary" type="button" onclick="loadData('DoReserve')" value="Reserve">  
</form>

<?php 
} else {
    echo "<h2>You have reserved or borrowed 10 books</h2>";
}

$conn->close();
?>

<br>
<a href="reader.php">Go back to menu</a>

</body>
</html>