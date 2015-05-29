<?php
ini_set('max_execution_time', 300);
header('Content-Type: application/json');
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
	//echo cleanname($_SESSION['focusrun']);
	if($_GET["prev"] == 1){
		$database =$_SESSION['focusrun'];
		if (count($_SESSION['focusrefnames']) >= 1){
			$aligned =1;
		}else{
			$aligned = 0;
		}
	}else {
		
		$database =$_SESSION['active_run_name'];
		if (count($_SESSION['activerefnames']) >= 1){
			$aligned =1;
		}else{
			$aligned = 0;
		}
	}
	//echo $_SESSION['active_run_name'];
	//echo $database;
	/**
	* Script:    DataTables server-side script for PHP 5.2+ and MySQL 4.1+
	* Notes:     Based on a script by Allan Jardine that used the old PHP mysql_* functions.
	*            Rewritten to use the newer object oriented mysqli extension.
	* Copyright: 2010 - Allan Jardine (original script)
	*            2012 - Kari Söderholm, aka Haprog (updates)
	* License:   GPL v2 or BSD (3-point)
	*/
	mb_internal_encoding('UTF-8');
 
	/**
	* Array of database columns which should be read and sent back to DataTables. Use a space where
	* you want to insert a non-database field (for example a counter or static image)
	*/
	//$aColumns = array( 'basename', 'length(basecalled_template.sequence)' , 'length(basecalled_complement.sequence)','length(basecalled_2d.sequence)','read_id','channel' );
  	$aColumns = array( 'Channel','read_id','basename','Template','Template_align','Template_length','Template_Start','Template_Duration','Complement','Complement_align','Complement_length','Complement_Start','Complement_Duration','2d','2d_align','2d_length');
	// Indexed column (used for fast and accurate table cardinality)
	$sIndexColumn = 'basename';
  
	// DB table to use
	$sTable = 'tracking_id';
	$sTable2 = 'config_general';
	$sTable3 = 'basecalled_template';
	$sTable4 = 'basecalled_complement';
	$sTable5 = 'basecalled_2d';
	
	//select basename, if(basecalled_template.seqid is null,"N","Y") as Template,if(last_align_basecalled_template_5prime.seqid is null,"N","Y") as Template_align,length(basecalled_template.sequence) as Template_length, if(basecalled_complement.seqid is null,"N","Y") as Complement,if(last_align_basecalled_complement_5prime.seqid is null,"N","Y") as Complement_align, length(basecalled_complement.sequence) as Complement_length, if(basecalled_2d.seqid is null,"N","Y") as 2d,if(last_align_basecalled_2d_5prime.seqid is null,"N","Y") as 2d_align, length(basecalled_2d.sequence) as 2d_length from config_general left join basecalled_template using (basename) left join last_align_basecalled_template_5prime on basecalled_template.seqid=last_align_basecalled_template_5prime.seqid  left join basecalled_complement using (basename) left join last_align_basecalled_complement_5prime on basecalled_complement.seqid=last_align_basecalled_complement_5prime.seqid left join basecalled_2d using (basename) left join last_align_basecalled_2d_5prime on basecalled_2d.seqid=last_align_basecalled_2d_5prime.seqid group by basename
	
  
	// Database connection information
	$gaSql['user']     = DB_USER;
	$gaSql['password'] = DB_PASS;
	$gaSql['db']       = $database;
	$gaSql['server']   = DB_HOST;
	$gaSql['port']     = DB_PORT;// 3306 is the default MySQL port
 
	// Input method (use $_GET, $_POST or $_REQUEST)
	$input =& $_GET;
 
	/** * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* If you just want to use the basic configuration for DataTables with PHP server-side, there is
	* no need to edit below this line
	*/
 
	/**
	* Character set to use for the MySQL connection.
	* MySQL will return all strings in this charset to PHP (if the data is stored correctly in the database).
	*/
	$gaSql['charset']  = 'utf8';
 
	/**
	* MySQL connection
	*/
	$db = new mysqli($gaSql['server'], $gaSql['user'], $gaSql['password'], $gaSql['db'], $gaSql['port']);
	if (mysqli_connect_error()) {
		die( 'Error connecting to MySQL server (' . mysqli_connect_errno() .') '. mysqli_connect_error() );
	}
 
	if (!$db->set_charset($gaSql['charset'])) {
		die( 'Error loading character set "'.$gaSql['charset'].'": '.$db->error );
	}
  
  
	/**
	* Paging
	*/
	$sLimit = "";
	if ( isset( $input['iDisplayStart'] ) && $input['iDisplayLength'] != '-1' ) {
		$sLimit = " LIMIT ".intval( $input['iDisplayStart'] ).", ".intval( $input['iDisplayLength'] );
	}
  
  
	/**
	* Ordering
	*/
	$aOrderingRules = array();
	if ( isset( $input['iSortCol_0'] ) ) {
		$iSortingCols = intval( $input['iSortingCols'] );
		for ( $i=0 ; $i<$iSortingCols ; $i++ ) {
			if ( $input[ 'bSortable_'.intval($input['iSortCol_'.$i]) ] == 'true' ) {
				$aOrderingRules[] =
					"`".$aColumns[ intval( $input['iSortCol_'.$i] ) ]."` "
						.($input['sSortDir_'.$i]==='asc' ? 'asc' : 'desc');
			}
		}
	}
 
	if (!empty($aOrderingRules)) {
		$sOrder = " ORDER BY ".implode(", ", $aOrderingRules);
	} else {
		$sOrder = "";
	}
  
 
	/**
	* Filtering
	* NOTE this does not match the built-in DataTables filtering which does it
	* word by word on any field. It's possible to do here, but concerned about efficiency
	* on very large tables, and MySQL's regex functionality is very limited
	*/
	$iColumnCount = count($aColumns);
 
	if ( isset($input['sSearch']) && $input['sSearch'] != "" ) {
		$aFilteringRules = array();
		for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
			if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' ) {
				$aFilteringRules[] = "".$aColumns[$i]." LIKE '%".$db->real_escape_string( $input['sSearch'] )."%'";
			}
		}
		if (!empty($aFilteringRules)) {
			$aFilteringRules = array('('.implode(" OR ", $aFilteringRules).')');
		}
	}
  
	// Individual column filtering
	for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
		if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' && $input['sSearch_'.$i] != '' ) {
			$aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$db->real_escape_string($input['sSearch_'.$i])."%'";
		}
	}
 
	if (!empty($aFilteringRules)) {
		$sWhere = " WHERE ".implode(" AND ", $aFilteringRules);
	} else {
		$sWhere = "";
	}
  
  
	/**
	* SQL queries
	* Get data to display
	*/
	$aQueryColumns = array();
	foreach ($aColumns as $col) {
		if ($col != ' ') {
			$aQueryColumns[] = $col;
		}
	}
	if ($aligned == 1) {
	 	$sQuery = "select SQL_CALC_FOUND_ROWS config_general.channel as Channel, config_general.read_id , basename, if (basecalled_template.basename_id is null, 'N', 'Y') as Template, if (last_align_basecalled_template_5prime.basename_id is null, 'N', 'Y') as Template_align, length(basecalled_template.sequence) as Template_length,round(basecalled_template.start_time,2) as Template_Start,round(basecalled_template.duration,2) as Template_Duration, if (basecalled_complement.basename_id is null, 'N', 'Y') as Complement, if (last_align_basecalled_complement_5prime.basename_id is null, 'N', 'Y') as Complement_align, length(basecalled_complement.sequence) as Complement_length, round(basecalled_complement.start_time,2) as Complement_Start,round(basecalled_complement.duration,2) as Complement_Duration, if (basecalled_2d.basename_id is null, 'N', 'Y') as 2d, if (last_align_basecalled_2d_5prime.basename_id is null, 'N', 'Y') as 2d_align, length(basecalled_2d.sequence) as 2d_length from config_general left join basecalled_template using (basename_id) left join last_align_basecalled_template_5prime using (basename_id) left join basecalled_complement using (basename_id) left join last_align_basecalled_complement_5prime using (basename_id) left join basecalled_2d using (basename_id) left join last_align_basecalled_2d_5prime using (basename_id) ".$sWhere." group by basename ".$sOrder.$sLimit;
	 }else {
	 	$sQuery = "select SQL_CALC_FOUND_ROWS config_general.channel as Channel, config_general.read_id , basename, if (basecalled_template.basename_id is null, 'N', 'Y') as Template, 'N' as Template_align, length(basecalled_template.sequence) as Template_length,round(basecalled_template.start_time,2) as Template_Start,round(basecalled_template.duration,2) as Template_Duration, if (basecalled_complement.basename_id is null, 'N', 'Y') as Complement, 'N' as Complement_align, length(basecalled_complement.sequence) as Complement_length, round(basecalled_complement.start_time,2) as Complement_Start,round(basecalled_complement.duration,2) as Complement_Duration, if (basecalled_2d.basename_id is null, 'N', 'Y') as 2d, 'N' as 2d_align, length(basecalled_2d.sequence) as 2d_length from config_general left join basecalled_template using (basename_id) left join basecalled_complement using (basename_id) left join basecalled_2d using (basename_id)  ".$sWhere." group by basename ".$sOrder.$sLimit;
	 }
	 
	//$sQuery = "SELECT SQL_CALC_FOUND_ROWS basename, if(basecalled_template.seqid is null,'N','Y') as Template,if(last_align_basecalled_template_5prime.seqid is null,'N','Y') as Template_align,length(basecalled_template.sequence) as Template_length, if(basecalled_complement.seqid is null,'N','Y') as Complement,if(last_align_basecalled_complement_5prime.seqid is null,'N','Y') as Complement_align, length(basecalled_complement.sequence) as Complement_length, if(basecalled_2d.seqid is null,'N','Y') as 2d,if(last_align_basecalled_2d_5prime.seqid is null,'N','Y') as 2d_align, length(basecalled_2d.sequence) as 2d_length from config_general left join basecalled_template using (basename) left join last_align_basecalled_template_5prime on basecalled_template.seqid=last_align_basecalled_template_5prime.seqid  left join basecalled_complement using (basename) left join last_align_basecalled_complement_5prime on basecalled_complement.seqid=last_align_basecalled_complement_5prime.seqid left join basecalled_2d using (basename) left join last_align_basecalled_2d_5prime on basecalle//d_2d.seqid=last_align_basecalled_2d_5prime.seqid ".$sWhere." group by basename ".$sOrder.$sLimit;
 	//echo $sQuery . "\n\n";
	$rResult = $db->query( $sQuery ) or die($db->error);
  
	// Data set length after filtering
	$sQuery = "SELECT FOUND_ROWS()";
	$rResultFilterTotal = $db->query( $sQuery ) or die($db->error);
	list($iFilteredTotal) = $rResultFilterTotal->fetch_row();
 
	// Total data set length
	$sQuery = "SELECT COUNT(`".$sIndexColumn."`) FROM `".$sTable."`";
	$rResultTotal = $db->query( $sQuery ) or die($db->error);
	list($iTotal) = $rResultTotal->fetch_row();
  
  
	/**
	* Output
	*/
	$output = array(
		"sEcho"                => intval($input['sEcho']),
		"iTotalRecords"        => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData"               => array(),
	);
  
	while ( $aRow = $rResult->fetch_assoc() ) {
		$row = array();
		for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
			if ( $aColumns[$i] == 'version' ) {
				// Special output formatting for 'version' column
				$row[] = ($aRow[ $aColumns[$i] ]=='0') ? '-' : $aRow[ $aColumns[$i] ];
			} elseif ( $aColumns[$i] != ' ' ) {
				// General output
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$output['aaData'][] = $row;
	}
  
	//$jsonstring = json_encode( $output );
	//$callback = $_GET['callback'];
	//echo $callback.'('.$jsonstring.');';

	echo json_encode( $output );
}else {
	echo "ERROR";
}
?>