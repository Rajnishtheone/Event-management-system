<?php 
    require_once("inc/header.php");
    require_once("inc/navigation.php");







    if(isset($_GET['homepage']))
    {
        require_once("inc/homepage.php");
    }else if(isset($_GET['addmanager']))
    {
        require_once("inc/add_manager.php");
    }
    else if(isset($_GET['feedback']))
    {
        require_once("inc/feedback.php");
    }
    else if(isset($_GET['p']))
    {
        require_once("inc/part.php");
    }
?>



















<?php
    require_once("inc/footer.php");
?>