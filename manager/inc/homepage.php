
<div class="row my-3">  
    <div class="col-12">
        <h3>Events</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Event Name</th>
                    <th scope="col">Event Location</th>
                    <th scope="col">Starting Date</th>
                    <th scope="col">Ending Date</th>
                    <th scope="col">Status </th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                    $fetchingData = mysqli_query($db, "SELECT * FROM event") or die(mysqli_error($db)); 
                    $isAnyeventAdded = mysqli_num_rows($fetchingData);

                    if($isAnyeventAdded > 0)
                    {
                        $sno = 1;
                        while($row = mysqli_fetch_assoc($fetchingData))
                        {
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
                            </tr>
                <?php
                        }
                    }else {
            ?>
                        <tr> 
                            <td colspan="7"><h3> No Upcoming Event </h3></td>
                        </tr>
            <?php
                    }
                ?>
            </tbody>    
        </table>
    </div>
</div>



