<?php 
 
// Site Settings 
$siteName = 'Cloud Technology Computing'; 
$siteEmail = 'jhongil@cloudtechnologycomputing.com'; 
 
// Database configuration 
define('DB_HOST', 'Server: 127.0.0.1:3306'); 
define('DB_USERNAME', 'u249000411_Jhongil'); 
define('DB_PASSWORD', 'Spiderman8085$'); 
define('DB_NAME', 'u249000411_CloudHoneyPot'); 
 
 
/* Changes are not required, used for internal purpose */ 
$siteURL = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")?'https://':'http://'; 
$siteURL = $siteURL.$_SERVER["SERVER_NAME"].dirname($_SERVER['REQUEST_URI']).'/'; 
 
?>