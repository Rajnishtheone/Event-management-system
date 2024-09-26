<?php

 session_start();
    require_once("./admin/inc/config.php");

if(isset($_GET['viewResult']))
{
    require_once("viewResult.php");
}

?>