<?php
session_start();
include_once 'data/get_trainers.php'; // Ensure this file exists and contains a function to fetch trainer data
$trainers = getTrainers();
include "partials/header.php";
?>

<main class="py-5">
    <div class="container">
        <h2 class="font-bold">Our Schedule</h2>
        <?php include('partials/message.php'); ?>
        <div class="mb-3">
            <label for="trainer_select" class="form-label">Select a Trainer:</label>
            <select id="trainer_select" class="form-control">
                <option value="">All Trainers</option>
                <?php foreach ($trainers as $trainer): ?>
                    <option value="<?= $trainer['trainer_id']; ?>">
                        <?= htmlspecialchars($trainer['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="schedule_table" class="mt-3">Loading schedules...</div>
    </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    function fetchSchedule(trainerId = '') {
        $.ajax({
            url: 'data/fetch_schedule.php', // This will be your server-side script that fetches schedules
            type: 'GET',
            data: {trainer_id: trainerId},
            success: function(data) {
                $('#schedule_table').html(data); // Update the schedule table with the data received from the server
            },
            error: function() {
                $('#schedule_table').html('Failed to fetch data. Please try again.');
            }
        });
    }

    // Initial load to fetch schedules for all trainers
    fetchSchedule();

    // Handle change event on dropdown
    $('#trainer_select').change(function() {
        var selectedTrainerId = $(this).val();
        fetchSchedule(selectedTrainerId);
    });
});
</script>

<?php include "partials/footer.php"; ?>
