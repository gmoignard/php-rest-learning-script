<?php
/**
 * @author Graeme Moignard <graeme@willowsoftware.co.uk>
 * @since 01/07/2013
 *
 * Sample REST API Call
 *
 */

 
include('required_functions.php');
ob_start("ob_gzhandler");


// Variable initialization
$error = false;
$format = strtolower(htmlentities($_GET['format']));
$callback = htmlentities($_GET['callback']);


// This is only really required if you plan on validating an api key before you 
// proceed. How you validate this key is totally up to you.
$key = htmlentities($_GET['key']); 


// If no format is passed in, pick a default. Valid options are php, xml, json or test
if ($format == '') $format = 'xml';


// If you do any error checking above, set `$error` to true and uncomment below 
// to stop the script execution if an error was found.
/*
if ($error) {	
	$out['status'] = 'Error';
	$out['message'] = 'Bad Request';
	require_once('display_output.php');
	exit;
}
*/ 

// Setup the response header 
$out = array();
$out['status'] = '200';
$out['message'] = 'This is a sample api call. Change this to a description of what this API call does.';
$out['link'] = 'http://yourdomain.com/path-to-your-api-documentation';
$out['request']['format'] = $format;
$out['request']['key'] = $key;
$out['request']['date'] = date('Y-m-d h:i:s');

// If the format is json and there is a callback passed in, set this call up for a JSONP response.
if ($format == 'json') {
	if ($callback != '') $out['request']['callback'] = $callback;
}


// Perform the lookup of data in MySQL. This requires you to have an already setup mysql 
// connection available. You can also accept in different $_GET variables in order to 
// fine-tune your sql call and provide the appropriate data back. 
// This is just a ** sample ** sql statement:
$sql = "
SELECT *
FROM table
WHERE field = '".$_GET['field']."'
order by field ASC
";

// Make the SQL call
$result = mysql_query($sql);
if (! mysql_num_rows($result)) {
	
	// There were no results found, come back with a 500 error status
	$out['status'] = '500';
	$out['message'] = 'No Results Found';
	
} else {
	
	// Put all the resulting data into an array 
	while($set = mysql_fetch_assoc($result)) {
		$out['items'][] = $set;
	}
	
}


// Display output will take whatever is in the `$out` array and transform it into 
// the format that has been requested.
require_once('display_output.php');