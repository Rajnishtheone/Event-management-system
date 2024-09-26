<?php 
    require_once("inc/header.php");
    require_once("inc/navigation.php");

    
      

      if(isset($_GET['homepage']))
    {
        require_once("inc/homepage.php");
    }else if(isset($_GET['addEventPage']))
    {
        require_once("inc/add_event.php");
    }
   else if(isset($_GET['adduserPage']))
    {
        require_once("inc/add_user.php");
    }
    
    ?>
    
<?php 
    require_once("inc/footer.php");
?>