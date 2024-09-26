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

// Function to fetch user details for a specific event where r_status is active
function fetchUsersForEvent($db, $event_id) {
    $stmt = $db->prepare(
        "SELECT v.username, v.u_email, v.u_phone, v.u_gender, v.u_age
         FROM user v
         JOIN register r ON v.v_id = r.v_id
         WHERE r.e_id = ? AND r.r_status = 'Active'"
    );
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    return $stmt->get_result();
}
?>

<style>
    .user-column {
        max-width: 450px; /* Set a fixed width for the user details column */
        white-space: pre-wrap; /* Allow the text to wrap within the column */
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
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Gender</th>
                                            <th scope="col">Age</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $userList = fetchUsersForEvent($db, $event_id);
                                        if ($userList->num_rows > 0) {
                                            $userSno = 1;
                                            while ($user = $userList->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $userSno++; ?></td>
                                                    <td><?php echo $user['username']; ?></td>
                                                    <td><?php echo $user['u_email']; ?></td>
                                                    <td><?php echo $user['u_phone']; ?></td>
                                                    <td><?php echo $user['u_gender']; ?></td>
                                                    <td><?php echo $user['u_age']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <tr>
                                                <td colspan="6">No registered users.</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Gender</th>
                                            <th scope="col">Age</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $userList = fetchUsersForEvent($db, $event_id);
                                        if ($userList->num_rows > 0) {
                                            $userSno = 1;
                                            while ($user = $userList->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $userSno++; ?></td>
                                                    <td><?php echo $user['username']; ?></td>
                                                    <td><?php echo $user['u_email']; ?></td>
                                                    <td><?php echo $user['u_phone']; ?></td>
                                                    <td><?php echo $user['u_gender']; ?></td>
                                                    <td><?php echo $user['u_age']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <tr>
                                                <td colspan="6">No registered users.</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">User</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Gender</th>
                                            <th scope="col">Age</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $userList = fetchUsersForEvent($db, $event_id);
                                        if ($userList->num_rows > 0) {
                                            $userSno = 1;
                                            while ($user = $userList->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $userSno++; ?></td>
                                                    <td><?php echo $user['username']; ?></td>
                                                    <td><?php echo $user['u_email']; ?></td>
                                                    <td><?php echo $user['u_phone']; ?></td>
                                                    <td><?php echo $user['u_gender']; ?></td>
                                                    <td><?php echo $user['u_age']; ?></td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <tr>
                                                <td colspan="6">No registered users.</td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
