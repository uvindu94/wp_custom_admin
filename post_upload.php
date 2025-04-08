<?php
session_start();
require_once '../wp-load.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle Post Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_post'])) {
    $title = sanitize_text_field($_POST['title']);
    $content = wp_kses_post($_POST['content']);
    $categories = isset($_POST['categories']) ? array_map('intval', $_POST['categories']) : [];
    $schedule = isset($_POST['schedule']) ? $_POST['schedule'] : '';
    $status = empty($schedule) ? 'publish' : 'future';
    $date = empty($schedule) ? current_time('mysql') : date('Y-m-d H:i:s', strtotime($schedule));

    $post_data = [
        'post_title'   => $title,
        'post_content' => $content,
        'post_status'  => $status,
        'post_author'  => $_SESSION['user_id'],
        'post_type'    => 'post',
        'post_category'=> $categories,
        'post_date'    => $date
    ];

    $post_id = wp_insert_post($post_data);

    // Handle Thumbnail Upload
    if ($post_id && !empty($_FILES['thumbnail']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        $attachment_id = media_handle_upload('thumbnail', $post_id);
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($post_id, $attachment_id);
        }
    }
}

// Fetch Categories
$categories = get_categories(['hide_empty' => false]);

// Fetch Recent 50 Posts
$args = [
    'post_type'      => 'post',
    'posts_per_page' => 50,
    'post_status'    => ['publish', 'future'],
];
$recent_posts = get_posts($args);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Management</title>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
    <script>
        tinymce.init({ selector: '#content' });
    </script>
    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { margin: 20px; padding: 0; background: #f4f4f4; }
        .container { display: flex; gap: 20px; }
        .left, .right { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .left { flex: 1; }
        .right { flex: 1; overflow-x: auto; }
        h2 { margin-bottom: 15px; }
        input, select, textarea { width: 100%; padding: 8px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #0073aa; color: white; padding: 10px; border: none; cursor: pointer; width: 100%; border-radius: 5px; }
        button:hover { background: #005177; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background: #0073aa; color: white; }
        .delete-btn { background: red; color: white; padding: 5px 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <!-- Left Side: Post Form -->
    <div class="left">
        <h2>Add New Post</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Post Title" required>
            <textarea id="content" name="content" placeholder="Post Content"></textarea>
            <label>Categories:</label>
            <select name="categories[]" multiple>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat->term_id; ?>"><?= $cat->name; ?></option>
                <?php endforeach; ?>
            </select>
            <label>Thumbnail:</label>
            <input type="file" name="thumbnail" accept="image/*">
            <label>Schedule (Optional):</label>
            <input type="datetime-local" name="schedule">
            <button type="submit" name="submit_post">Publish Post</button>
        </form>
    </div>

    <!-- Right Side: Recent Posts -->
    <div class="right">
        <h2>Recent Posts</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_posts as $post): ?>
                <tr>
                    <td><?= esc_html($post->post_title); ?></td>
                    <td><?= esc_html($post->post_date); ?></td>
                    <td><?= esc_html($post->post_status); ?></td>
                    <td>
                        <a href="edit_post.php?id=<?= $post->ID; ?>">Edit</a>
                        <form method="POST" action="delete_post.php" style="display:inline;">
                            <input type="hidden" name="post_id" value="<?= $post->ID; ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
