<?php
// auth/logout.php – Logout | BrewMaster | ASB/2023/144
session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
exit();
?>
