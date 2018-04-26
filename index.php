<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    session_unset();
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
<br><br>
<div class="container text-center">
	<h2>Admin Menu</h2>
	<form class="form-horizontal" method="post" action="admin.php">
		<div class="form-group">
			<label class="control-label col-sm-5" for="id">Id:</label>
			<div class="col-sm-2">
				<input class="form-control" id="id" type="text" name="id">
			</div>
			<div class="col-sm-5"></div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-5" for="password">Password:</label>
			<div class="col-sm-2">
				<input class="form-control" id="password" type="password" name="password">
			</div>
			<div class="col-sm-5"></div>
		</div>

		<input class="btn btn-primary" type="submit" name="submit" value="Login">  
	</form>

	<br>

	<h2>Reader Menu</h2>
	<form class="form-horizontal" method="post" action="reader.php">
		<div class="form-group">
		<label class="control-label col-sm-5" for="card">Card Number:</label>
			<div class="col-sm-2">
				<input class="form-control" id="card" type="text" name="id">
			</div>
			<div class="col-sm-5"></div>
		</div>

		<input class="btn btn-primary" type="submit" name="submit" value="Login">
	</form>
</div>
</body>
</html>
