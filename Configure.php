<?php 
 
// Site Settings 
$siteName = 'Demo Site'; 
$siteEmail = 'sender@example.com'; 
 
// Database configuration 
define('DB_HOST', 'MySQL_Database_Host'); 
define('DB_USERNAME', 'MySQL_Database_Username'); 
define('DB_PASSWORD', 'MySQL_Database_Password'); 
define('DB_NAME', 'MySQL_Database_Name'); 
 
 
/* Changes are not required, used for internal purpose */ 
$siteURL = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")?'https://':'http://'; 
$siteURL = $siteURL.$_SERVER["SERVER_NAME"].dirname($_SERVER['REQUEST_URI']).'/'; 
 
?>