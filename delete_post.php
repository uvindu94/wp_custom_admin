<?php session_start();
require_once '../wp-load.php';

// echo $_SESSION['user_id'];
// echo "   d f  ";
// echo $_POST['post_id']; 

if (!isset($_SESSION['user_id']) || !isset($_POST['post_id'])) {
    header("Location: post_upload.php");
    exit;
}

$post_id = intval($_POST['post_id']);
wp_delete_post($post_id, true);
header("Location: post_upload.php");
exit;
?>
 