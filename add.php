<?php
	session_start();
	require_once "pdo.php";

	$oldmake = isset($_POST['make']) ? $_POST['make'] : '';
	$oldyear = isset($_POST['year']) ? $_POST['year'] : '';
	$oldmiles = isset($_POST['mileage']) ? $_POST['mileage'] : '';

	if (! isset($_SESSION['email'])) {
		die('Not logged in');
	}

	if ( isset($_SESSION["error"])){

		echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
		unset($_SESSION["error"]);
	}


	if (! isset($_POST['make']) && isset($_POST['mileage'])
		&& isset($_POST['year'])){
		$_SESSION["error"] = "Make is required";
		header('Location: add.php');
		return;
	}

	if (isset($_POST['make']) && isset($_POST['mileage'])
		&& isset($_POST['year'])){
		
		if (! is_numeric($_POST['mileage']) || ! is_numeric($_POST['year'])) {
		$_SESSION["error"]= "Mileage and year must be numeric";
		header('Location: add.php');
		return;
		} else if (strlen($_POST['make']) < 1) {
			$_SESSION["error"] = "Make required";
			header('Location: add.php');
			return;
		}else {
			$stmt = $pdo->prepare('INSERT INTO autos
							(make, year, mileage) VALUES (:mk, :yr, :mi)');
			$stmt->execute(array(
				':mk' => htmlentities($_POST['make']),			
				':yr' => htmlentities($_POST['year']),
				':mi' => htmlentities($_POST['mileage'])
			));
			$_SESSION["success"]= "Record Inserted";
			header('Location: view.php');
			return;
		}
	}
?>

<html>
<head>
	<title>Peter Mwansa </title>
</head>
<body>
	<h1>Autos Database</h1>
	<?php
	if ( isset($_REQUEST['name']) ) {
		echo "<p>Welcome: ";
		echo htmlentities($_REQUEST['name']);
		echo "</p>\n";
	}
	?>
	<form method="post">
		<p>Add a new automobile</p>
		<p>Make:
		<input type="text" name="make" size="40" value="<?= htmlentities($oldmake)?>"></p>
		<p>Mileage:
		<input type="text" name="mileage" value="<?= htmlentities($oldmiles)?>"></p>
		<p>Year:
		<input type="text" name="year" value="<?= htmlentities($oldyear)?>"></p>

		<input type="submit" value="Add">
	</form>
	<form method="post">
		<?php

		if ( isset($_POST['cancel'])){
			header('Location: view.php');
			return;
		}
		?>
		<input type="submit" name="cancel" value="cancel">
	</form>
</body>
</html>
