<?php
require 'init.php'; 


if (isset($_COOKIE['rememberEmail'])) {
    setcookie('rememberEmail', '', time() - 3600, '/'); // Include path if necessary
}


if (isset($_SESSION['logon_cookie'])) {
    unset($_SESSION['logon_cookie']);
}


session_destroy();

// Redirect to the login page after logout
header("Location: page_logon.php");
exit;
?>
