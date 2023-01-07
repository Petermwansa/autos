<?php 
#$oldemail = isset($_POST[]) ? $_POST[] : '';
#$oldpass= isset($_POST[]) ? $_POST[] : '';
session_start();
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  

if ( isset($_SESSION["success"])) {
echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}


if ( isset($_POST['cancel'] ) ) {
    header("Location: index.php");
    return;
}

if ( isset($_POST['email']) && isset($_POST['pass']) ) {
	unset($_SESSION['email']);
        if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
		$_SESSION["error"] = "Email and password are required";
		header('Location: login.php');
		return;
	} else if (strpos(($_POST['email']), '@') == false){
		$_SESSION["error"] = "Email must have an at-sign (@)";
		header('Location: login.php');
		return;
	} else {
            $check = hash('md5', $salt.$_POST['pass']);
	    if ( $check == $stored_hash ) {
		error_log("Login Success ".$_POST['email']);
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['success'] = "Logged in.";
                header("Location: view.php?name=".urlencode($_POST['email']));
                return;
	    } else {
		    $_SESSION["error"] = "Incorrect Password.";
		    error_log("Login fail ". $_POST['email']. " $check");
		    header('Location: login.php');
		    return;
	    }
	}
}



?>
<!DOCTYPE html>
<html>
<head>
	<title>Peter Mwansa</title>
</head>
<body>
	<h1>Please Log In</h1>

	<?php
		if ( isset($_SESSION["error"])){
			echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
			unset($_SESSION["error"]);
		}
	?>

	<form method="POST">
		<label for="nam">Email: </label>
		<input type="text" name="email" id="nam"><br/>
		<label for="id_1723">Password</label>
		<input type="password" name="pass" id="id_1723"><br/>
		<br>
		<input type="submit" value="Log In">
	</form>
	<br>
	<form method="POST">
		<input type="submit" name="cancel" value="Cancel">
	</form>
</body>
</html>