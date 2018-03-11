<?php
/**
 * Aborts the session and redirect back to login
 */
session_start();
session_unset();
session_destroy();
header('Location: index.php');
?>