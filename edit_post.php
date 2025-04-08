<?php
session_start();
require_once '../wp-load.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: post_upload.php");
    exit;
}

$post_id = intval($_GET['id']);
$post = get_post($post_id);
if (!$post) {
    die("Post not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title      = $_POST['title'];
    $content    = $_POST['content'];
    $categories = isset($_POST['categories']) ? array_map('intval', $_POST['categories']) : [];

    $update_data = [
        'ID'           => $post_id,
        'post_title'   => $title,
        'post_content' => $content,
        'post_category' => $categories
    ];

    wp_update_post($update_data);
    
    if (!empty($_FILES['thumbnail']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $upload = wp_handle_upload($_FILES['thumbnail'], ['test_form' => false]);
        if (!isset($upload['error'])) {
            $file_path = $upload['file'];
            $attach_id = wp_insert_attachment([
                'post_mime_type' => mime_content_type($file_path),
                'post_title' => basename($file_path),
                'post_content' => '',
                'post_status' => 'inherit'
            ], $file_path, $post_id);

            wp_generate_attachment_metadata($attach_id, $file_path);
            set_post_thumbnail($post_id, $attach_id);
        }
    }

    echo "<script>alert('Post Updated!');window.location.href='post_upload.php';</script>";
}

$categories = get_categories(['hide_empty' => false]);
$selected_cats = wp_get_post_categories($post_id);
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" value="<?= esc_attr($post->post_title) ?>" required>
    <textarea name="content"><?= esc_textarea($post->post_content) ?></textarea>

    <label>Thumbnail:</label>
    <input type="file" name="thumbnail">

    <label>Categories:</label>
    <select name="categories[]" multiple>
        <?php foreach ($categories as $category) : ?>
            <option value="<?= $category->term_id ?>" <?= in_array($category->term_id, $selected_cats) ? 'selected' : '' ?>><?= $category->name ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Update Post</button>
</form>
