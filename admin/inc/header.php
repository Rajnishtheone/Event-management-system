<?php 
    session_start();
    require_once("../manager/inc/config.php");
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminPanel</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    />

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

 
<div class="container-fluid">
        <div style="background-color: black" class="row bg-black text-white">
            <div class="col-1">
                <img src="../assets/images/WhatsApp Image 2024-07-14 at 14.11.52_b2b50ab8.jpg" width="70px"/>
            </div>
            <div  class="col-11 my-auto">
                <h3> EVENT MANAGEMENT SYSTEM  - <small> Welcome  Admin <?php echo $_SESSION['username']; ?></small> </h3>
            </div>
        </div>

 