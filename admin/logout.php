<?php

session_start();
// unset semua variable session
unset($_SESSION['username']);
unset($_SESSION['id_users']);

// unset semua
session_unset();
// destroy
session_destroy();

header('location: ../login.php?pesan=logout');
exit;