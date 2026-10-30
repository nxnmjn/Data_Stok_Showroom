//Fitur Logout dari user admin  

<?php

session_start();
session_destroy();
setcookie("welcome_msg", "", time() - 3600, "/");
header("Location: login.php");
exit;

?>