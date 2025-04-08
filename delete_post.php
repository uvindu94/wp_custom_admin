<?php
session_start();
require_once '../wp-load.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: post_upload.php");
    exit;
}

$post_id = intval($_GET['id']);
wp_delete_post($post_id, true);
header("Location: post_upload.php");
exit;
?>
 