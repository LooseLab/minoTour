<?php


$db = $_GET["db"]; 
$jobtype = $_GET["job"];
$id = $db . "_" . $jobtype;
if (isset ($_GET["type"])) {
	$filename = $id . "." . $_GET["type"];
}else{
	$filename = $id . ".fasta";
}

header("Content-type: text/plain");
header("Content-Disposition: attachment; filename=$filename");
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("../libraries/password_compatibility_library.php");
}

// include the configs / constants for the database connection
require_once("../config/db.php");

// load the login class
require_once("../classes/Login.php");

// load the functions
require_once("../includes/functions.php");

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process. in consequence, you can simply ...
$login = new Login();

// ... ask if we are logged in here:
if ($login->isUserLoggedIn() == true) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
    //include("views/index_old.php");*/
	if($_GET["prev"] == 1){
		$mindb_connection = new mysqli(DB_HOST,DB_USER,DB_PASS,$_SESSION['focusrun']);
	}else{
		$mindb_connection = new mysqli(DB_HOST,DB_USER,DB_PASS,$_SESSION['active_run_name']);
	}
	//echo cleanname($_SESSION['active_run_name']);;

	//echo '<br>';

	if (!$mindb_connection->connect_errno) {
		
		if ($_GET["type"] == "fastq"){
			$querytable = "basecalled_" . $jobtype;
			$lasttable = "last_align_" . $querytable . "_5prime";
			if ($_GET["align"] == 1) {
				$sql = "select seqid, sequence, qual from $querytable inner join $lasttable using (seqid);";
			}elseif ($_GET["unalign"] == 1) {
				$sql = "select seqid,sequence, qual from $querytable where seqid not in (select seqid from $lasttable);";
			}elseif (isset($_GET["readname"])){
				$sql = "select seqid,sequence, qual from $querytable where basename_id = '" . $_GET["readname"] . "';";
				//echo $sql;
			}else {
				$sql = "select seqid,sequence,qual from $querytable;";
			}		

			$queryresult=$mindb_connection->query($sql);
			if ($queryresult->num_rows >= 1){
				foreach ($queryresult as $row) {
					echo "@" . $row['seqid'] . "\n";
					echo $row['sequence'] . "\n";
					echo "+\n";
					$qualscore= substr($row['qual'], 1, -1);
					$qualarray = str_split($qualscore);
					foreach ($qualarray as $value){
						echo chr(ord($value)-31);
					}
					print "\n";
				}
			}
			
		}else{
		
			$querytable = "basecalled_" . $jobtype;
			$lasttable = "last_align_" . $querytable . "_5prime";
			if ($_GET["align"] == 1) {
				$sql = "select seqid, sequence, qual from $querytable inner join $lasttable using (seqid);";
			}elseif ($_GET["unalign"] == 1) {
				$sql = "select seqid,sequence, qual from $querytable where seqid not in (select seqid from $lasttable);";
			}elseif (isset($_GET["readname"])){
				$sql = "select seqid,sequence, qual from $querytable where basename_id = '" . $_GET["readname"] . "';";
				//echo $sql;
			}	else {
				$sql = "select seqid,sequence,qual from $querytable;";
			}	
			
			
			$queryresult=$mindb_connection->query($sql);
			if ($queryresult->num_rows >= 1){
				foreach ($queryresult as $row) {
					echo ">" . $row['seqid'] . "\n";
					echo $row['sequence'] . "\n";
				}
			}	
		}
	}
} else {
	echo "ERROR";
}
?>