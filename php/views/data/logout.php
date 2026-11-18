<?php

session_start();
$_SESSION = [];
session_destroy();

header('Location: /utpl/php/views/login/login.php');
exit;
