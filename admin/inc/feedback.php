<?php 
require_once("inc/header.php");
require_once("inc/navigation.php");

// Function to fetch events based on status
function fetchEventsByStatus($db, $status) {
    $stmt = $db->prepare("SELECT * FROM event WHERE e_status = ?");
    $stmt->bind_param("s", $status);
    $stmt->execute();
    return $stmt->get_result();
}

// Function to fetch feedback and user details for a specific event
function fetchFeedbackAndUserDetails($db, $event_id) {
    $stmt = $db->prepare(
        "SELECT f.feed, v.username, v.u_email, v.u_phone, v.u_gender, v.u_age 
         FROM feedback f
         JOIN user v ON f.v_id = v.v_id
         WHERE f.e_id = ?"
    );
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>

<style>
    .feedback-column pre {
        max-width: 450px; /* Set a fixed width for the feedback column */
        white-space: pre-wrap; /* Allow the text to wrap within the pre tag */
        word-wrap: break-word; /* Ensure long words will break and wrap properly */
    }
</style>

<div class="row my-3">
    <div class="col-12">
        <h3 style="background-color: black; color: white;">Active Events</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Event Name</th>
                    <th scope="col">Event Location</th>
                    <th scope="col">Starting Date</th>
                    <th scope="col">Ending Date</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $activeEvents = fetchEventsByStatus($db, 'Active');
                if ($activeEvents->num_rows > 0) {
                    $sno = 1;
                    while ($row = $activeEvents->fetch_assoc()) {
                        $event_id = $row['e_id'];
                        $photo_path = '../manager/uploads/' . $row['e_photo'];
                ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><img src="<?php echo $photo_path; ?>" alt="Event Photo" width="180"></td>
                            <td><?php echo $row['e_topic']; ?></td>
                            <td><?php echo $row['e_location']; ?></td>
                            <td><?php echo $row['start_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                            <td><?php echo $row['e_status']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <?php 
                                $feedbackData = fetchFeedbackAndUserDetails($db, $event_id);
                                if ($feedbackData->num_rows > 0) {
                                    $feedbackCount = 1;
                                    echo "<h5>Feedback</h5>";
                                    echo '<table class="table">';
                                    echo '<thead><tr><th scope="col">#</th><th scope="col">Feedback</th><th scope="col">User</th><th scope="col">Email</th><th scope="col">Phone</th><th scope="col">Gender</th><th scope="col">Age</th></tr></thead>';
                                    echo '<tbody>';
                                    while ($feedbackRow = $feedbackData->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $feedbackCount++ . "</td>";
                                        echo "<td class='feedback-column'><pre>" . $feedbackRow['feed'] . "</pre></td>";
                                        echo "<td>" . $feedbackRow['username'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_email'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_phone'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_gender'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_age'] . "</td>";
                                        echo "</tr>";
                                    }
                                    echo '</tbody></table>';
                                } else {
                                    echo "No feedback available.";
                                }
                                ?>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="7"><h3>No Active Events</h3></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row my-3">
    <div class="col-12">
        <h3 style="background-color: black; color: white;">Inactive Events</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Event Name</th>
                    <th scope="col">Event Location</th>
                    <th scope="col">Starting Date</th>
                    <th scope="col">Ending Date</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $inactiveEvents = fetchEventsByStatus($db, 'Inactive');
                if ($inactiveEvents->num_rows > 0) {
                    $sno = 1;
                    while ($row = $inactiveEvents->fetch_assoc()) {
                        $event_id = $row['e_id'];
                        $photo_path = '../manager/uploads/' . $row['e_photo'];
                ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><img src="<?php echo $photo_path; ?>" alt="Event Photo" width="180"></td>
                            <td><?php echo $row['e_topic']; ?></td>
                            <td><?php echo $row['e_location']; ?></td>
                            <td><?php echo $row['start_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                            <td><?php echo $row['e_status']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <?php 
                                $feedbackData = fetchFeedbackAndUserDetails($db, $event_id);
                                if ($feedbackData->num_rows > 0) {
                                    $feedbackCount = 1;
                                    echo "<h5>Feedback</h5>";
                                    echo '<table class="table">';
                                    echo '<thead><tr><th scope="col">#</th><th scope="col">Feedback</th><th scope="col">User</th><th scope="col">Email</th><th scope="col">Phone</th><th scope="col">Gender</th><th scope="col">Age</th></tr></thead>';
                                    echo '<tbody>';
                                    while ($feedbackRow = $feedbackData->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $feedbackCount++ . "</td>";
                                        echo "<td class='feedback-column'><pre>" . $feedbackRow['feed'] . "</pre></td>";
                                        echo "<td>" . $feedbackRow['username'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_email'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_phone'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_gender'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_age'] . "</td>";
                                        echo "</tr>";
                                    }
                                    echo '</tbody></table>';
                                } else {
                                    echo "No feedback available.";
                                }
                                ?>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="7"><h3>No Inactive Events</h3></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row my-3">
    <div class="col-12">
        <h3 style="background-color: black; color: white;">Expired Events</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Event Name</th>
                    <th scope="col">Event Location</th>
                    <th scope="col">Starting Date</th>
                    <th scope="col">Ending Date</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $expiredEvents = fetchEventsByStatus($db, 'Expired');
                if ($expiredEvents->num_rows > 0) {
                    $sno = 1;
                    while ($row = $expiredEvents->fetch_assoc()) {
                        $event_id = $row['e_id'];
                        $photo_path = '../manager/uploads/' . $row['e_photo'];
                ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><img src="<?php echo $photo_path; ?>" alt="Event Photo" width="180"></td>
                            <td><?php echo $row['e_topic']; ?></td>
                            <td><?php echo $row['e_location']; ?></td>
                            <td><?php echo $row['start_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                            <td><?php echo $row['e_status']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="7">
                                <?php 
                                $feedbackData = fetchFeedbackAndUserDetails($db, $event_id);
                                if ($feedbackData->num_rows > 0) {
                                    $feedbackCount = 1;
                                    echo "<h5>Feedback</h5>";
                                    echo '<table class="table">';
                                    echo '<thead><tr><th scope="col">#</th><th scope="col">Feedback</th><th scope="col">User</th><th scope="col">Email</th><th scope="col">Phone</th><th scope="col">Gender</th><th scope="col">Age</th></tr></thead>';
                                    echo '<tbody>';
                                    while ($feedbackRow = $feedbackData->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $feedbackCount++ . "</td>";
                                        echo "<td class='feedback-column'><pre>" . $feedbackRow['feed'] . "</pre></td>";
                                        echo "<td>" . $feedbackRow['username'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_email'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_phone'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_gender'] . "</td>";
                                        echo "<td>" . $feedbackRow['u_age'] . "</td>";
                                        echo "</tr>";
                                    }
                                    echo '</tbody></table>';
                                } else {
                                    echo "No feedback available.";
                                }
                                ?>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="7"><h3>No Expired Events</h3></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once("inc/footer.php");
?>
