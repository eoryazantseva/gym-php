<?php
include_once 'data/get_classes.php';
$classes = getClasses();
?>

<?php include "partials/header.php";?>

<div class="container mt-5">
    <h1 class="mb-4">Available Classes</h1>
    <?php foreach ($classes as $class): ?>
        <div class="card mt-4 mb-4">
            <div class="card-header">
                <h5 class=" font-bold"><?= htmlspecialchars($class['name']) ?></h5>
            </div>
            <div class="card-body">
                <p><?= nl2br(htmlspecialchars($class['description'])) ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<?php include "partials/footer.php"; ?>