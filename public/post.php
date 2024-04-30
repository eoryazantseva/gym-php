<?php
session_start();
include_once 'data/blog_helper.php';
$post_id = $_GET['post_id'] ?? 0;
$conn = getConnection();
$post = getPost($conn, $post_id);
if (!$post) {
    echo "Post not found.";
    exit;
}
$comments = getComments($conn, $post_id);
require "header.php";

$post_id = $_GET['post_id'] ?? 0; // make sure to validate this
$post = getPost(getConnection(), $post_id);
$comments = getComments(getConnection(), $post_id);

if (isset($_POST['submit_comment'])) {
    if (isset($_SESSION['user_id'])) {  // Check if user is logged in
        $comment = mysqli_real_escape_string(getConnection(), $_POST['comment']);
        $user_id = $_SESSION['user_id'];
        // Insert comment
        $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES ('$post_id', '$user_id', '$comment')";
        mysqli_query(getConnection(), $sql);
        header("Location: post.php?post_id=$post_id");  // Refresh to show new comment
    } else {
        $error = "You must be logged in to comment.";
    }
}
?>

<div class="post">
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    <small>Posted on: <?= $post['created_at'] ?></small>
</div>

<!-- Comments section -->
<h3>Comments</h3>
<?php foreach ($comments as $comment): ?>
    <div class="comment">
        <p><?= htmlspecialchars($comment['comment']) ?></p>
        <small>Commented by: <?= $comment['username'] ?> at <?= $comment['created_at'] ?></small>
    </div>
<?php endforeach; ?>

<?php if (isset($_SESSION['user_id'])): ?>
    <form action="" method="post">
        <textarea name="comment" required></textarea>
        <button type="submit" name="submit_comment">Add Comment</button>
    </form>
<?php else: ?>
    <p>You must be logged in to add comments.</p>
<?php endif; ?>

<?php include "footer.php"; ?>
