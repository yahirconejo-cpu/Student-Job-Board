<?php
session_start();
// Destroy session
session_unset();
session_destroy();
// Redirect to homepage or login page
header("Location: ../../index.php");
exit();