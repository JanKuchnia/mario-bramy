<?php
/**
 * ADMIN: logout.php
 * Destroys session and redirects to login
 */

include '../config/database.php';
include '../config/auth.php';

// Destroy session
destroy_session();

// Redirect to login
header('Location: /mario-bramy/admin/login.php');
exit;

?>
