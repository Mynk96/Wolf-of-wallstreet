<?php
require_once("./basic.php");

$file = fopen("users.txt","w");
$result = mysqli_query($conn, "SELECT * FROM users");
  while($row = mysqli_fetch_assoc($result)){
  	fwrite($file,$row['id']." - ".$row['password']."\n\n");
  }
 fclose($file);

?>