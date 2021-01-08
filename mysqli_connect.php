<?php
/*GRANT INSERT, SELECT, UPDATE on sportradar.*
-> TO 'user'@'localhost'
-> IDENTIFIED BY '->password';*/

/*database credentials required for db access, input below*/
$username = "root";
$servername = "localhost";
$password = "";
$database = "sportradar_calendar";
$dbc =  @mysqli_connect($servername, $username, $password, $database);
if (!$dbc) {
  DIE("Could not connect to the MySql database: " . mysqli_connect_error());
}
?>
