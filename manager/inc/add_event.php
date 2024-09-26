<!-- alert -->
<?php 
    if(isset($_GET['added'])) {
?>
        <div class="alert alert-success my-3" role="alert">
            Event added successfully                                          
        </div>  
<?php
    } else if(isset($_GET['notadded'])) {
?>
        <div class="alert alert-danger my-3" role="alert">
            Event topic already exists. Please choose a different topic
        </div>  
<?php
    } else if(isset($_GET['date'])) {
?>
        <div class="alert alert-danger my-3" role="alert">
            Starting date time is greater than ending date time. Please correct it.
        </div>  
<?php
    } else if(isset($_GET['date1'])) {
?>
        <div class="alert alert-danger my-3" role="alert">
            Current date time is greater than ending date time. Please correct it.
        </div>  
<?php
    } else if(isset($_GET['delete_id'])) {
        $d_id = mysqli_real_escape_string($db, $_GET['delete_id']);
        
        mysqli_query($db, "DELETE FROM register WHERE e_id = '". $d_id ."'") or die(mysqli_error($db));
       
        mysqli_query($db, "DELETE FROM event WHERE e_id = '". $d_id ."'") or die(mysqli_error($db));
?>
        <div class="alert alert-danger my-3" role="alert">
            Events and its participants have been deleted successfully!
        </div>
<?php
    }
?>

<div class="row my-3">
    <div class="col-4">
        <h3>Add New Event</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" name="event_topic" placeholder="Event Topic" class="form-control" required />
            </div>
            <div class="form-group">
                <input type="text" name="number_of_candidates" placeholder="Event Location" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="sd">Starting Date</label>
                <input type="datetime-local" id="sd" name="starting_date" placeholder="Starting Date" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="sd">Ending Date</label>
                <input type="datetime-local" name="ending_date" placeholder="Ending Date" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="cp">Candidate Photo</label>
                <input type="file" id="cp" name="candidate_photo" class="form-control" required />
            </div>
            <input type="submit" value="Add Event" name="addeventBtn" class="btn btn-success" />
        </form>
    </div>

    <div class="col-8">
        <h3>Upcoming Event</h3>
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
                $fetchingData = mysqli_query($db, "SELECT * FROM event") or die(mysqli_error($db)); 
                $isAnyeventAdded = mysqli_num_rows($fetchingData);

                if($isAnyeventAdded > 0) {
                    $sno = 1;
                    while($row = mysqli_fetch_assoc($fetchingData)) {
                        $event_id = $row['e_id'];
                        $photo_path = 'uploads/' . $row['e_photo'];
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
                                <button class="btn btn-sm btn-danger" onclick="DeleteData(<?php echo $event_id; ?>)"> Delete </button>
                            </td>
                        </tr>
            <?php
                    }
                } else {
            ?>
                    <tr> 
                        <td colspan="8"><h3>No Events</h3></td>
                    </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
    </div>
</div>


<script>
    const DeleteData = (e_id) => {
        let c = confirm("Are you sure you want to delete it?");
        if(c == true) {
            location.assign("index.php?addEventPage=1&delete_id=" + e_id);
        }
    }
</script>

<?php 
    if(isset($_POST['addeventBtn'])) {
        $event_topic = mysqli_real_escape_string($db, $_POST['event_topic']);
        $number_of_candidates = mysqli_real_escape_string($db, $_POST['number_of_candidates']);
        $starting_date = mysqli_real_escape_string($db, $_POST['starting_date']);
        $ending_date = mysqli_real_escape_string($db, $_POST['ending_date']);
        
        // Handle file upload
        $photo = $_FILES['candidate_photo'];
        $photo_name = $photo['name'];
        $photo_tmp_name = $photo['tmp_name'];
        $photo_error = $photo['error'];
        $photo_size = $photo['size'];
        
        if ($photo_error === 0) {
            $photo_ext = pathinfo($photo_name, PATHINFO_EXTENSION);
            $photo_new_name = uniqid('', true) . "." . $photo_ext;
            $photo_destination = 'uploads/' . $photo_new_name;
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }
            move_uploaded_file($photo_tmp_name, $photo_destination);
        } else {
            echo "There was an error uploading the file.";
            exit;
        }

        // Date and status logic
        $startDateTime = new DateTime($starting_date, new DateTimeZone('Asia/Kolkata'));
        $endDateTime = new DateTime($ending_date, new DateTimeZone('Asia/Kolkata'));
        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Kolkata'));

        if ($startDateTime->getTimestamp() > $endDateTime->getTimestamp()) {
?>
            <script> location.assign("index.php?addEventPage=1&date=1"); </script>
<?php
            exit;
        } else if ($currentDateTime->getTimestamp() > $endDateTime->getTimestamp()) {
?>
            <script> location.assign("index.php?addEventPage=1&date1=1"); </script>
<?php
            exit;
        } else if ($currentDateTime->getTimestamp() >= $startDateTime->getTimestamp() && $currentDateTime->getTimestamp() <= $endDateTime->getTimestamp()) {
            $status = "Active";
        } else {
            $status = "InActive";
        }

        // Check if e_topic is unique
        $checkQuery = $db->prepare("SELECT e_id FROM event WHERE e_topic = ?");
        $checkQuery->bind_param("s", $event_topic);
        $checkQuery->execute();
        $checkQuery->store_result();

        if ($checkQuery->num_rows == 0) {
            // Insert into db
            mysqli_query($db, "INSERT INTO event(e_topic, e_location, start_date, end_date, e_status, e_photo) VALUES
            ('". $event_topic ."', '". $number_of_candidates ."', '". $starting_date ."', '". $ending_date ."', '". $status ."', '". $photo_new_name ."')") or die(mysqli_error($db));
?>
            <script> location.assign("index.php?addEventPage=1&added=1"); </script>
<?php
        } else {
?>
            <script> location.assign("index.php?addEventPage=1&notadded=1"); </script>
<?php
        }
    }
?>
