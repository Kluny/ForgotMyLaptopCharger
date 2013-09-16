<?php

//bcvreb/a73/delete_old_files.php

//connect to database
require_once 'up_link.inc';


function getIds( $table, $agency ) {
	$where_clause = '';
	if ($table == 'summary') {
		$where_clause = "lister_office_code = '$agency'";
	}
	if ($table == 'vreb_exclusives') {
		$where_clause = "agency_id = '$agency'";
	}

	$sql = '
	
	SELECT id
	FROM ' . $table . ' 
	WHERE ' . $where_clause . '	
	AND status != "C"
	';
	
	$res = mysql_query($sql);
	
	if ( $res == FALSE ) {
		echo "query failed \n\n";
		echo $sql . "\n";
		die();
	}
	
	while ( $row = mysql_fetch_array( $res ) ) {
		$results_array[] = $row['id'];
	}
	//store results as a array
	return $results_array;
}

function getFilesToMove ( $oldDir ) {
	$extensions 		= array( 'jpg', 'pdf' );
	$files[] = '';
	if( $handle = opendir( $oldDir ) ) {
		while( false !== ( $file = readdir( $handle ) ) ) {
			if( in_array( strtolower( pathinfo( $file , PATHINFO_EXTENSION ) ) , $extensions ) ) {
				$files[]	= $file;
			}
		}
		closedir( $handle );
	}
	return $files;
}

function move( $ids, $candidates, $oldDir, $newDir ) {
	foreach ( $ids AS $K => $V) {
		foreach ( $candidates AS $l => $candidate ) { 
			if ( strpos( $candidate,  (string)$V ) !== FALSE ){
				$ok = rename($oldDir . '/' . $candidate, $newDir . '/' . $candidate);
				$return .= ($ok !== false) ? "$candidate moved \n\n " : "failed to move $candidate \n\n ";
			}
		}
	}
	
	$return .= "  done moving files  ";
	return $return;	
}

function clearDirectory($directory) {
	if( $handle = opendir( $directory ) ) {
		while( false !== ( $file = readdir( $handle ) ) ) {
			if ($file == '.' || $file == '..') {
				continue;
			}
		
			$ok = unlink ( $directory . '/' . $file );
			$return .= ($ok === true) ? "$file deleted \n\n " : "failed to delete $file \n\n ";
		}
	}
	$return .= "  done deleting files  ";
	return $return;
}

function file_put_contents(  $filename ,  $data ) {
	if (!$handle = fopen($filename, 'a')) {
        exit;
    }
	if (fwrite($handle, $data) === FALSE) {
        exit;
    }
	fclose($handle);
}


$oldDir 			= '/bcvreb/a73';
$newDir 			= '/a73/hold';

$ids 				= array_merge( getIds('summary', '73') , getIds('vreb_exclusives', '73') );
$candidates 		= getFilesToMove($oldDir);

//to make this script delete the files permanently,
//simply uncomment the line that begins '$logDeletes...', 
//uncomment the second line that begins 'file_put_contents...',
//and comment out the first line that beings 'file_put_contents...'.


$timestamp = date("D M j G:i:s T Y");

//clear the contents of the previous hold period from the directory
#$logDeletes = clearDirectory($newDir);
//move files into the directory for the next hold period.
$logMoves = move($ids, $candidates, $oldDir, $newDir);
file_put_contents('/bcvreb/a73/delete_old_files.log', "\n" . $timestamp . $logMoves );
#file_put_contents('/bcvreb/a73/delete_old_files.log', "\n" . $timestamp . $logDeletes . $logMoves );

?>
