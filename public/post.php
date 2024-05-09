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


if (isset($_POST['submit_comment'])) {
    if (isset($_SESSION['user_id'])) {
        $comment = mysqli_real_escape_string($conn, $_POST['comment']);
        $user_id = $_SESSION['user_id'];
        $sql = "INSERT INTO comments (post_id, user_id, comment) VALUES ('$post_id', '$user_id', '$comment')";
        mysqli_query($conn, $sql);
        header("Location: post.php?post_id=$post_id");
        exit();
    } else {
        $_SESSION['message'] = "You must be logged in to comment.";
        $error = "You must be logged in to comment.";
    }
}

require "partials/header.php";
?>

<main class="container mt-5">
    <article class="post">
        <h2 class="display-4"><?= htmlspecialchars($post['title']) ?></h2>
        <p class="text-muted">Posted on <?= date('F j, Y', strtotime($post['created_at'])) ?> by <?= htmlspecialchars($post['author_name']) ?></p>
        <img src="<?= htmlspecialchars($post['post_image_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="img-fluid rounded">
        <p class="mt-4"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
    </article>

    <section class="comments mt-5">
        <h3>Comments</h3>
        <?php if (!empty($comments)): ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment mb-3">
                    <p><?= htmlspecialchars($comment['comment']) ?></p>
                    <small class="text-muted">Commented by <?= htmlspecialchars($comment['username']) ?>
                        <?php if ($comment['role'] === 'admin'): ?>
                            <span class="text-danger">Admin</span>
                        <?php endif; ?>
                        on <?= date('F j, Y, g:i a', strtotime($comment['created_at'])) ?>
                    </small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet.</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="post.php?post_id=<?= htmlspecialchars($post_id) ?>" method="post" class="mt-4">
                <div class="mb-3">
                    <label for="comment" class="form-label">Your Comment</label>
                    <textarea name="comment" id="comment" rows="3" class="form-control" required></textarea>
                </div>
                <button type="submit" name="submit_comment" class="btn btn-primary mb-5">Add Comment</button>
            </form>
        <?php else: ?>
            <p class="text-danger">You must be logged in to add comments.</p>
        <?php endif; ?>
    </section>
</main>

<?php include "partials/footer.php"; ?>
