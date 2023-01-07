<?php
session_start();
require_once "pdo.php";

if (! isset($_SESSION['email'])) {
	die('Not logged in');
}

if ( isset($_SESSION["success"])) {
echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}

if ( isset($_SESSION["error"])){
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION["error"]);
}
$error = false;
$addSuccess = false;

if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}


if (! isset($_POST['make']) && isset($_POST['mileage'])
	&& isset($_POST['year'])){
            $error = "Make is required";
}
if (isset($_POST['make']) && isset($_POST['mileage'])
	&& isset($_POST['year'])){
	
	if (! is_numeric($_POST['mileage']) || ! is_numeric($_POST['year'])) {
		$error = "Mileage and year must be numeric";
	} else if (strlen($_POST['make']) < 1) {
		$error = "Make is required";
	}else {
		$stmt = $pdo->prepare('INSERT INTO autos
						(make, year, mileage) VALUES (:mk, :yr, :mi)');
		$stmt->execute(array(
			':mk' => $_POST['make'],			
			':yr' => $_POST['year'],
			':mi' => $_POST['mileage']
		));
		$addSuccess = "Record Inserted";
	}

}
?>

<html>
<head>
<title>Peter Mwansa</title>
</head>
<body>
<h1>Autos Database</h1>
<?php
if ( isset($_REQUEST['name']) ) {
    echo "<p>Welcome: ";
    echo htmlentities($_REQUEST['name']);
    echo "</p>\n";
}
$stmtrow = $pdo->query("SELECT make, mileage, year FROM autos");
$rows = $stmtrow->fetchAll(PDO::FETCH_ASSOC);
?>
<table border="1">
<?php

    echo "<tr><th>Make</th>";
    echo "<th>Mileage</th>";
    echo "<th>Year</th></tr>";
    foreach ($rows as $row) {
    echo "<tr><td>";
    echo($row['make']);
    echo("</td><td>");
    echo($row['mileage']);
    echo("</td><td>");
    echo($row['year']);
    echo("</td></tr>\n");
    }
?>

<form method="post">
<p>
<a href="add.php">Add New</a><a href="logout.php"> | Logout</a>
</p>
</form>

</table>
</body>
</html>