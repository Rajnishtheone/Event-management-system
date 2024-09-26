<?php
// Handle add manager form submission
if (isset($_POST['addManagerBtn'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // Check if username is unique
    $checkQuery = $db->prepare("SELECT a_id FROM manager WHERE a_id = ?");
    $checkQuery->bind_param("s", $username);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows == 0) {
        // Insert into db
        $insertQuery = $db->prepare("INSERT INTO manager (a_id, a_pass) VALUES (?, ?)");
        $insertQuery->bind_param("ss", $username, $password);
        $insertQuery->execute();
        if ($insertQuery->affected_rows > 0) {
            echo "<div class='alert alert-success my-3'>Manager added successfully</div>";
        } else {
            echo "<div class='alert alert-danger my-3'>Error adding manager</div>";
        }
    } else {
        echo "<div class='alert alert-danger my-3'>Username already exists. Please choose a different username</div>";
    }
}

// Handle manager deletion
if (isset($_GET['delete_admin'])) {
    $d_id = mysqli_real_escape_string($db, $_GET['delete_admin']);
    $query = "DELETE FROM manager WHERE a_id = '$d_id'";
    if (mysqli_query($db, $query)) {
        echo "<div class='alert alert-success my-3' role='alert'>Manager has been deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger my-3' role='alert'>Error deleting manager: " . mysqli_error($db) . "</div>";
    }
    ?>
    <script> location.assign("index.php?addmanager=1"); </script>
<?php
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Manager</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <!-- Add Manager Form -->
    <div class="row my-3">
        <div class="col-10">
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Username" class="form-control" required />
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" class="form-control" required />
                </div>
                <input type="submit" value="Add Manager" name="addManagerBtn" class="btn btn-success" />
            </form>
        </div>
    </div>

    <!-- Display Managers -->
    <div class="col-8">
        <h3>Event Managers</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">S.No</th>
                    <th scope="col">Username</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $fetchingAdmins = mysqli_query($db, "SELECT * FROM manager") or die(mysqli_error($db)); 
                $isAnyAdminAdded = mysqli_num_rows($fetchingAdmins);

                if ($isAnyAdminAdded > 0) {
                    $sno = 1;
                    while ($row = mysqli_fetch_assoc($fetchingAdmins)) {
                        $username = $row['a_id'];
            ?>
                        <tr>
                            <td><?php echo $sno++; ?></td>
                            <td><?php echo $username; ?></td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="DeleteAdmin('<?php echo $username; ?>')"> Delete </button>
                            </td>
                        </tr>
            <?php
                    }
                } else {
            ?>
                    <tr> 
                        <td colspan="3"><h3>No Event Manager is Found</h3></td>
                    </tr>
            <?php
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const DeleteAdmin = (a_id) => {
        let c = confirm("Are you sure you want to delete this manager?");
        if (c) {
            console.log(`Deleting manager with ID: ${a_id}`);
            location.assign("index.php?addmanager=1&delete_admin=" + a_id);
        }
    }
</script>
</body>
</html>
