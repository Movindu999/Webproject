<?php
session_start(); 

session_unset();

// Destroy the session
session_destroy();

// Redirect to login page after logging out
header("Location: index.html");
exit();
?>