<?php

include_once '/usr/lib/includes/db_connect.php';
include_once '/usr/lib/includes/functions.php';

sec_session_start(); // Our custom secure way of starting a PHP session.

if (isset($_POST['user'], $_POST['p'])) {
    $username = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
    $password = $_POST['p']; // The hashed password.
    
    if (login($username, $password, $mysqli) == true) {
        // Login success 
        header("Location: ../welcome.php");
        exit();
    } else {
        // Login failed 
        header('Location: ../index.php?error=1');
        exit();
    }
} else {
    // The correct POST variables were not sent to this page. 
    header('Location: ../error.php?err=Impossible de se connecter.');
    exit();
}