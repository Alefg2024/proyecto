<?php

if (!isset($_SESSION["client_id"])) {
	$user = $_POST['username'];
	$pass = sha1(md5($_POST['password']));

	$base = new Database();
	$con = $base->connect();
	$sql = "select * from person where (email1= \"" . $user . "\") and password= \"" . $pass . "\" and is_active_access=1";
	$query = $con->query($sql);
	$found = false;
	$userid = null;
	while ($r = $query->fetch_array()) {
		$found = true;
		$userid = $r['id'];
	}

	if ($found == true) {
		$_SESSION['client_id'] = $userid;
		print "Cargando ... $user";
		print "<script>window.location='index.php?view=clienthome';</script>";
	} else {
		print "<script>window.location='index.php?view=clientaccess';</script>";
	}
} else {
	print "<script>window.location='index.php?view=clienthome';</script>";
}
