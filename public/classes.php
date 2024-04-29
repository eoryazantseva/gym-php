<?php
session_start();
include_once 'data/get_schedule.php';
$classSchedules = getClassSchedules();
include "header.php";
?>

<main class="py-5">
    <div class="container">
        <h2 class="font-bold">Our Schedule</h2>
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
                                <?php if (isset($_SESSION['email'])): ?>
                                <th>Book</th>
                                <?php endif; ?>
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
                    <?php if (isset($_SESSION['email'])): ?>
                    <td>
                            <a href="book_class.php?schedule_id=<?= $schedule['schedule_id']; ?>" class="btn btn-primary">Book Now</a>
                    </td>
                    <?php endif; ?>
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

<?php include "footer.php"; ?>
