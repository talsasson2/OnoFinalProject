<?php
    include_once('../session/session.php');
    include_once('../session/connection.php');

    $mode = $_POST['mode'];

    $sql = "UPDATE `db`.`users` SET `dark_mode` = '$mode' WHERE `ID` = $userid_session;";

    if (mysqli_query($con, $sql)) 
    {
        echo json_encode($sql);
        echo json_encode("change background mode");
        exit;
    } 
    else
    {
        exit;
    }

 
?>