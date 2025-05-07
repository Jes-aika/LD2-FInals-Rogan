<?php
function getDBConnection(){

$host = 'centerbeam.proxy.rlwy.net';
$port = 19463;
$user = 'root';
$password = 'hiuYHgPwyocMXgzlHAGbmAltlyjSTGdz';
$dbname = 'railway';

// Create connection
$connection = new mysqli($host, $user, $password, $dbname, $port);

if($connection->connect_error){
    die("Error: Failed to connect to MySQL. ".$connection->connect_error);
}

return $connection;
}

?>
