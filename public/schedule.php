<?php
session_start();
include_once 'data/get_trainers.php'; // Ensure this file exists and contains a function to fetch trainer data
include_once 'data/get_schedule.php';

// Fetch the trainer ID from the GET request if available
$selectedTrainerId = isset($_GET['trainer_id']) ? (int)$_GET['trainer_id'] : null;

// Fetch class schedules using the selected trainer ID
$classSchedules = getClassSchedules($selectedTrainerId);

// Fetch all trainers for the dropdown list
$trainers = getTrainers();

include "partials/header.php";
?>

<main class="py-5">
    <div class="container">
        <h2 class="font-bold">Our Schedule</h2>
        <form method="get" class="mb-3">
            <div class="mb-3">
                <label for="trainer_id" class="form-label">Select a Trainer:</label>
                <select name="trainer_id" id="trainer_id" class="form-control" onchange="this.form.submit()">
                    <option value="">All Trainers</option>
                    <?php foreach ($trainers as $trainer): ?>
                        <option value="<?= $trainer['trainer_id']; ?>" <?= $selectedTrainerId == $trainer['trainer_id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($trainer['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        <?php if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);  // Clear the message after displaying it
        }
        ?>
        <div class="table-responsive">
            <?php 
            $last_date = null;
            foreach ($classSchedules as $schedule):
                if ($last_date !== $schedule['formatted_date']): 
                    if ($last_date !== null):
            ?>
                        </tbody>
                    </table>
            <?php 
                    endif;
            ?>
                    <h4 class="mt-5"><?= htmlspecialchars($schedule['formatted_date']); ?></h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>Class Name</th>
                                <th>Trainer</th>
                                <th>Time</th>
                                <th>Level</th>
                                <th>Spots Available</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
            <?php
                endif;
            ?>
                <tr class="text-center">
                    <td><?= htmlspecialchars($schedule['class_name']); ?></td>
                    <td><?= htmlspecialchars($schedule['trainer_name']); ?></td>
                    <td><?= htmlspecialchars($schedule['start_time']); ?> - <?= htmlspecialchars($schedule['end_time']); ?></td>
                    <td><?= htmlspecialchars($schedule['level']); ?></td>
                    <td><?= htmlspecialchars($schedule['capacity']); ?></td>
                    <td>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="data/delete_class.php?schedule_id=<?= $schedule['schedule_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php
                $last_date = $schedule['formatted_date'];
            endforeach;
            if ($last_date !== null):
            ?>
                        </tbody>
                    </table>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include "partials/footer.php"; ?>
