<?php
//echo "1: ".$_SERVER["REMOTE_ADDR"];
//echo "2: ".$_SERVER["HTTP_POST"];
if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1"  || $_SERVER["REMOTE_ADDR"] == "::1"  || $_SERVER["HTTP_POST"] == "localhost" ) {
	$hostname_conn = "localhost";
	$database_conn = "db_armanda2"; //$database_conn = "zecorind_mitra2";
	$username_conn = "root"; //$username_conn = "zecorind_root";
	$password_conn = "admin";
}
else {
	$hostname_conn = "mysql.idhostinger.com";
	$database_conn = "u485477426_arman"; //$database_conn = "zecorind_mitra2";
	$username_conn = "u485477426_arman"; //$username_conn = "zecorind_root";
	$password_conn = "PresarioCQ43";
}
?>