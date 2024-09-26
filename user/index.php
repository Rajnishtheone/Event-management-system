<?php 
    require_once("inc/header.php");
    require_once("inc/navigation.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['register'])) {
            $event_id = $_POST['event_id'];
            $user_id = $_SESSION['user_id']; // Assuming you store user ID in session

            // Use prepared statements to prevent SQL injection
            $stmt = $db->prepare("SELECT * FROM register WHERE e_id = ? AND v_id = ?");
            $stmt->bind_param("ii", $event_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                $stmt = $db->prepare("INSERT INTO register (e_id, v_id, r_status) VALUES (?, ?, 'InActive')");
                $stmt->bind_param("ii", $event_id, $user_id);
                if ($stmt->execute()) {
                    ?>
                    <div class="alert alert-success my-3" role="alert">
                    Registration request submitted successfully.
                    </div>
                    <?php
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "You have already registered for this event.";
            }
            $stmt->close();
        } elseif (isset($_POST['submit_feedback'])) {
            $event_id = $_POST['event_id'];
            $feedback = $_POST['feedback_content'];
            $user_id = $_SESSION['user_id']; // Assuming you store user ID in session

            // Use prepared statements to prevent SQL injection
            $stmt = $db->prepare("INSERT INTO feedback (e_id, v_id, feed) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $event_id, $user_id, $feedback);
            if ($stmt->execute()) {
                ?>
                    <div class="alert alert-success my-3" role="alert">
                    Feedback submitted successfully.
                    </div>
                <?php
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
?>

<div class="col-12">
<h3 style="background-color: black; color: white;">Active Events</h3>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">S.No</th>
                <th scope="col">Photo</th>
                <th scope="col">Event Topic</th>
                <th scope="col">Event Location</th>
                <th scope="col">Starting Date</th>
                <th scope="col">Ending Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $activeEvents = $db->query("SELECT * FROM event WHERE e_status = 'Active'"); 
            if ($activeEvents->num_rows > 0) {
                $sno = 1;
                while ($row = $activeEvents->fetch_assoc()) {
                    $event_id = $row['e_id'];
                    $photo_path = '../manager/uploads/' . $row['e_photo'];

                    // Check registration status
                    $user_id = $_SESSION['user_id']; // Assuming you store user ID in session
                    $registerStatusQuery = $db->prepare("SELECT r_status FROM register WHERE e_id = ? AND v_id = ?");
                    $registerStatusQuery->bind_param("ii", $event_id, $user_id);
                    $registerStatusQuery->execute();
                    $registerStatus = $registerStatusQuery->get_result()->fetch_assoc();
                    $status = $registerStatus['r_status'] ?? 'Not Registered';
        ?>
                    <tr>
                        <td><?php echo $sno++; ?></td>
                        <td><img src="<?php echo $photo_path; ?>" alt="Event Photo" width="180"></td>
                        <td><?php echo $row['e_topic']; ?></td>
                        <td><?php echo $row['e_location']; ?></td>
                        <td><?php echo $row['start_date']; ?></td>
                        <td><?php echo $row['end_date']; ?></td>
                        <td><?php echo $row['e_status']; ?></td>
                        <td>
                            <?php if ($status == 'Active') { ?>
                                <button class="btn btn-sm btn-success" disabled>Registered</button>
                            <?php } elseif ($status == 'InActive') { ?>
                                <button class="btn btn-sm btn-warning" disabled>Pending</button>
                            <?php } else { ?>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                    <button type="submit" name="register" class="btn btn-sm btn-primary">Register</button>
                                </form>
                            <?php } ?>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                <textarea name="feedback_content" placeholder="Your feedback" required></textarea>
                                <button type="submit" name="submit_feedback" class="btn btn-sm btn-info">Submit Feedback</button>
                            </form>
                        </td>
                    </tr>
        <?php
                }
            } else {
        ?>
                <tr> 
                    <td colspan="8"><h3>No Active Events</h3></td>
                </tr>
        <?php
            }
        ?>
        </tbody>
    </table>
</div>

<div class="col-12">
<h3 style="background-color: black; color: white;">Upcoming Events</h3>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">S.No</th>
                <th scope="col">Photo</th>
                <th scope="col">Event Topic</th>
                <th scope="col">Event Location</th>
                <th scope="col">Starting Date</th>
                <th scope="col">Ending Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $upcomingEvents = $db->query("SELECT * FROM event WHERE e_status = 'InActive'"); 
            if ($upcomingEvents->num_rows > 0) {
                $sno = 1;
                while ($row = $upcomingEvents->fetch_assoc()) {
                    $event_id = $row['e_id'];
                    $photo_path = '../manager/uploads/' . $row['e_photo'];

                    // Check registration status
                    $user_id = $_SESSION['user_id']; // Assuming you store user ID in session
                    $registerStatusQuery = $db->prepare("SELECT r_status FROM register WHERE e_id = ? AND v_id = ?");
                    $registerStatusQuery->bind_param("ii", $event_id, $user_id);
                    $registerStatusQuery->execute();
                    $registerStatus = $registerStatusQuery->get_result()->fetch_assoc();
                    $status = $registerStatus['r_status'] ?? 'Not Registered';
        ?>
                    <tr>
                        <td><?php echo $sno++; ?></td>
                        <td><img src="<?php echo $photo_path; ?>" alt="Event Photo" width="180"></td>
                        <td><?php echo $row['e_topic']; ?></td>
                        <td><?php echo $row['e_location']; ?></td>
                        <td><?php echo $row['start_date']; ?></td>
                        <td><?php echo $row['end_date']; ?></td>
                        <td><?php echo $row['e_status']; ?></td>
                        <td>
                            <?php if ($status == 'Active') { ?>
                                <button class="btn btn-sm btn-success" disabled>Registered</button>
                            <?php } elseif ($status == 'InActive') { ?>
                                <button class="btn btn-sm btn-warning" disabled>Pending</button>
                            <?php } else { ?>
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                    <button type="submit" name="register" class="btn btn-sm btn-primary">Register</button>
                                </form>
                            <?php } ?>
                            <form action="" method="POST" style="display:inline;">
                                <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                <textarea name="feedback_content" placeholder="Your feedback" required></textarea>
                                <button type="submit" name="submit_feedback" class="btn btn-sm btn-info">Submit Feedback</button>
                            </form>
                        </td>
                    </tr>
        <?php
                }
            } else {
        ?>
                <tr> 
                    <td colspan="8"><h3>No Upcoming Events</h3></td>
                </tr>
        <?php
            }
        ?>
        </tbody>
    </table>
</div>

<section class="contact">
    <div class="cont">
        <h2 id="contact">CONTACT US</h2>
        <div class="contact-wrapper">
            <div class="contact-form">
                <h3>send us message</h3>
                <form action="https://api.web3forms.com/submit" method="POST">
                    <div class="form-group">
                        <input type="hidden" name="access_key" value="943c149f-d0ae-4f10-ad00-a9c03decab3a">
                    </div>
                    <div class="form-group">
                        <input type="text" name="name" placeholder="your name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="your email" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="your message" required></textarea>
                    </div>
                    <button type="submit">send message</button>
                </form>
            </div>
            <div class="contact-info">
                    <h3>contact information</h3>
                    
                    <p><b>PHONE NO:</b> +91 9798669871</p>
                    <p><B>EMAIL:</B> rajnishkk97@gmail.com</p>
                    <p><B>ADDRESS:</B> CUCEK</p>
                </div>
        </div>
    </div>
</section>
<br>

<?php
    require_once("inc/footer.php");
?>
