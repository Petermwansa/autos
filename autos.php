<?php
require_once "pdo.php";
session_start();

// if we don't have POST data
$failure = false;
// if we didn't enter any record yet
$success = false;

// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

// If the user left the input fields blank
if (isset($_POST['add'])) {
  if ($_POST['make'] === "") {
    $failure = "Make is Required !";
  }
  elseif (!is_numeric($_POST['year'])) {
    $failure = "Input Year must be Numeric !!";
  }
  elseif (!is_numeric($_POST['mileage'])) {
    $failure = "Input Mileage must be Numeric !!";
  }
  else {
    $sql = "INSERT INTO autos (make, year, mileage)
              VALUES (:make, :year, :mileage)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage']));

        $success = true;
  }
}

$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>

    <head>
        <title>Peter Mwansa</title>
        <?php require_once "bootstrap.php"; ?>
    </head>

    <body>
    <?php 
    if ( isset($_SESSION["success"]) ) {
        echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
        unset($_SESSION["success"]);
    }  
    ?> 
        <div class="container">
            <h1>Tracking Autos for <?php echo $_GET['name']?></h1>
            <?php
              // Note triple not equals and think how badly double
              // not equals would work here...
              if ( $failure !== false ) {
                  // Look closely at the use of single and double quotes
                  echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
              }
              if ( $success !== false) {
                echo '<p style="color:green;">' . "Record Inserted Sucessfully!" .
                "</p>\n";
              }
            ?>

            <h1>Automobiles </h1>
            <ul>
              <?php
                if (isset($rows)) {
                  foreach ($rows as $row) {
                    echo "<li>" . $row['year'] . " " . "<b>" . $row['make'] .
                    "</b>" . "/" . $row['mileage']
                    . "</li>";
                  }
                }
               ?>
            </ul>

            <a href="add.php" name="add">Add New</a>
            <a href="logout.php" name="add">Logout</a>
        </div>
    </body>

</html>