<?php
session_start();
include_once 'data/blog_helper.php';
$posts = getPosts(getConnection());
include "header.php";
?>

<main class="container mt-5">
    <h1 class="mb-5 text-center font-bold">Fitness News</h1>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h2 class="font-bold text-center mb-2"><?= htmlspecialchars($post['title']) ?></h2>
            <div class="text-center mb-2">
                <small>POSTED ON <?= date('F j, Y', strtotime($post['created_at'])) ?> BY <?= htmlspecialchars($post['author_name']) ?></small>
            </div>
            <img class="rounded" src="<?= htmlspecialchars($post['post_image_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">

            <?php 
            $lines = explode("\n", htmlspecialchars($post['content']));  // Split content into lines
            $preview = implode("\n", array_slice($lines, 0, 2));  // Join the first two lines
            ?>

            <p class="mb-3 mt-3"><?= nl2br($preview) ?>...</p>
            <div class="text-center">
                <a href="post.php?post_id=<?= $post['post_id'] ?>" class="btn btn-secondary mt-2">CONTINUE READING →</a>
            </div>
        </div>
    <?php endforeach; ?>
</main>

<?php include "footer.php"; ?>
