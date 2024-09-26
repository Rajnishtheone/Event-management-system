<?php

// Check if the Accept button was clicked
if (isset($_POST['acceptuser'])) {
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];
    mysqli_query($db, "UPDATE register SET r_status = 'Active' WHERE v_id = '$user_id' AND e_id = '$event_id'") or die(mysqli_error($db));
    ?>  
    <div class="alert alert-success my-3" role="alert">
        Participant verified successfully!
    </div>
    <?php
}

// Check if the Delete button was clicked
if (isset($_POST['deleteuser'])) {
    $user_id = $_POST['user_id'];
    $event_id = $_POST['event_id'];
    mysqli_query($db, "DELETE FROM register WHERE v_id = '$user_id' AND e_id = '$event_id'") or die(mysqli_error($db));
    ?>  
    <div class="alert alert-danger my-3" role="alert">
        Participant deleted successfully!
    </div>
    <?php
}
?>

<div class="row my-3">
    <div class="col-12">
        <h3>Participants List</h3>
        <?php 
        if(isset($_GET['accepted'])) {
        ?>
            <div class="alert alert-success my-3" role="alert">
                Participant accepted successfully                                          
            </div>  
        <?php 
        } elseif(isset($_GET['deleted'])) {
        ?>
            <div class="alert alert-success my-3" role="alert">
                Participant deleted successfully                                          
            </div>  
        <?php 
        } 
        ?>

<h4 style="border: 2px solid black; color: white; background-color: black; padding: 10px; ">Active Events</h4>
        <?php
        // Fetch active events
        $activeEvents = mysqli_query($db, "SELECT * FROM event WHERE e_status = 'Active'") or die(mysqli_error($db));
        if (mysqli_num_rows($activeEvents) > 0) {
            while ($event = mysqli_fetch_assoc($activeEvents)) {
                $photo_path = '../manager/uploads/' . $event['e_photo'];
                ?>
                <h5>Event Name:<?php echo $event['e_topic']; ?>   </h5>
                <img src="<?php echo $photo_path; ?>" alt="Event Photo" width="180">
                <h5>Event Timing:(<?php echo $event['start_date']; ?> to <?php echo $event['end_date']; ?>)</h5>
                <h5>Event Location: <?php echo $event['e_location']; ?></h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Age</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch participants for the active event
                        $fetchingData = mysqli_query($db, 
                            "SELECT v.v_id, v.username, v.u_email, v.u_phone, v.u_gender, v.u_age 
                             FROM register r 
                             JOIN user v ON r.v_id = v.v_id 
                             WHERE r.r_status = 'InActive' 
                             AND r.e_id = '{$event['e_id']}'") or die(mysqli_error($db)); 

                        $isAnyuserAdded = mysqli_num_rows($fetchingData);

                        if($isAnyuserAdded > 0) {
                            $sno = 1;
                            while($row = mysqli_fetch_assoc($fetchingData)) {
                                $user_id = $row['v_id'];
                                $event_id = $event['e_id'];
                        ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['u_email']; ?></td>
                                    <td><?php echo $row['u_phone']; ?></td>
                                    <td><?php echo $row['u_gender']; ?></td>
                                    <td><?php echo $row['u_age']; ?></td>
                                    <td> 
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                            <button class="btn btn-sm btn-success" type="submit" name="acceptuser"> Accept </button>
                                        </form>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                            <button class="btn btn-sm btn-danger" type="submit" name="deleteuser"> Delete </button>
                                        </form>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr> 
                                <td colspan="7"><h3>No Pending Participants</h3></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>    
                </table>
                <?php
            }
        } else {
            echo "<p>No active events found.</p>";
        }
        ?>

<h4 style="border: 2px solid black; color: white; background-color: black; padding: 10px; ">Inactive Events</h4>
        <?php
        // Fetch inactive events
        $inactiveEvents = mysqli_query($db, "SELECT * FROM event WHERE e_status = 'Inactive'") or die(mysqli_error($db));
        if (mysqli_num_rows($inactiveEvents) > 0) {
            while ($event = mysqli_fetch_assoc($inactiveEvents)) {
               $photo_path = '../manager/uploads/' . $event['e_photo'];
                ?>
                <h5>Event Name:<?php echo $event['e_topic']; ?>   </h5>
                <img src="<?php echo $photo_path; ?>" alt="Event Photo" width="180">
                <h5>Event Timing:(<?php echo $event['start_date']; ?> to <?php echo $event['end_date']; ?>)</h5>
                <h5>Event Location: <?php echo $event['e_location']; ?></h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Age</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch participants for the inactive event
                        $fetchingData = mysqli_query($db, 
                            "SELECT v.v_id, v.username, v.u_email, v.u_phone, v.u_gender, v.u_age 
                             FROM register r 
                             JOIN user v ON r.v_id = v.v_id 
                             WHERE r.r_status = 'InActive' 
                             AND r.e_id = '{$event['e_id']}'") or die(mysqli_error($db)); 

                        $isAnyuserAdded = mysqli_num_rows($fetchingData);

                        if($isAnyuserAdded > 0) {
                            $sno = 1;
                            while($row = mysqli_fetch_assoc($fetchingData)) {
                                $user_id = $row['v_id'];
                                $event_id = $event['e_id'];
                        ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['u_email']; ?></td>
                                    <td><?php echo $row['u_phone']; ?></td>
                                    <td><?php echo $row['u_gender']; ?></td>
                                    <td><?php echo $row['u_age']; ?></td>
                                    <td> 
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                            <button class="btn btn-sm btn-success" type="submit" name="acceptuser"> Accept </button>
                                        </form>
                                        <form method="POST" style="display:inline-block;">
                                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                            <button class="btn btn-sm btn-danger" type="submit" name="deleteuser"> Delete </button>
                                        </form>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                        ?>
                            <tr> 
                                <td colspan="7"><h3>No Pending Participants</h3></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>    
                </table>
                <?php
            }
        } else {
            echo "<p>No inactive events found.</p>";
        }
        ?>
    </div>
</div>
