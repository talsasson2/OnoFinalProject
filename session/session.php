<?php
   include('connection.php');
   ini_set('session.gc_maxlifetime', 604800); 
   ini_set('session.cookie_lifetime', 604800);
   session_start();

 

   $user_check = $_SESSION['login_user'];

   
   $ses_sql = mysqli_query($con,"select * from users where username = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];
   $display_session = $row['displayname'];
   $role_session = $row['role'];
   $userid_session = $row['ID'];
   $moked_number = $row['moked_number'];
   $change_password = $row['change_password'];
   $dark_mode = $row['dark_mode'];

   if ($dark_mode == "true")
   {
      $modal_background = "modal modal-black fade";
   }
   else
   {
      $modal_background = "modal fade";

   }


   if ($change_password == "yes")
   {
      header("location: ../session/change_password.php?updateid=$userid_session");

   }



   
   if(!isset($_SESSION['login_user'])){
      $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      header("location: ../session/login.php?forward=$actual_link");
      die();
   }



   
?>