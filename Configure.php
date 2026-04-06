<?php 
 
// Site Settings 
$siteName = 'Cloud Technology Computing'; 
$siteEmail = 'jhongil@cloudtechnologycomputing.com'; 
 
// Database configuration 
define('DB_HOST', '127.0.0.1'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', 'root'); 
define('DB_NAME', 'mydatabase'); 

 
/* Changes are not required, used for internal purpose */ 
$siteURL = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")?'https://':'http://'; 
$siteURL = $siteURL.$_SERVER["SERVER_NAME"].dirname($_SERVER['REQUEST_URI']).'/'; 
 
?>