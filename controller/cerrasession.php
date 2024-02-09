<?php
session_start();
$_SESSION['session'] = false;
header("Location: ../index.php");
session_destroy();
exit();