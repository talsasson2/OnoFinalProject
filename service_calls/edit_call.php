<?php 
   include('../session/session.php');
   include_once('../session/connection.php');
   $useragent=$_SERVER['HTTP_USER_AGENT'];
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;
   require '../PHPMailer-master/src/Exception.php';
   require '../PHPMailer-master/src/PHPMailer.php';
   require '../PHPMailer-master/src/SMTP.php';
   
   
   
   $id=$_GET['updateid'];
   $result=mysqli_query($con,"select * from service_calls where ID=$id;");
   $row=mysqli_fetch_assoc($result);
   $name=$row['name'];
   $open_by=$row['open_by'];
   $type=$row['type'];
   $installer=$row['installer'];
   $installerphone=$row['installerphone'];
   $si=$row['si'];
   $siphone=$row['siphone'];
   $description=$row['description'];
   $date_old = $row['date_asked'];
   $date_asked = date('Y-m-d', strtotime($date_old));
   $address=$row['address'];
   $hviagent=$row['hviagent'];
   $status=$row['status'];
   $project_time_start = $row['project_time_start']; 
   $project_time_end = $row['project_time_end']; 
   $project_id = $row['project_id']; 
   $date_created_b4_format = $row['date_created']; 
   $date_created = date('d-m-Y H:i:s', strtotime($date_created_b4_format));
   
   
   
   if(isset($_POST['delete'])) {
      $sql = "DELETE FROM service_calls WHERE `ID` = $id;";
   
      if (mysqli_query($con, $sql)) {
      header("location: ../service_calls.php");
      } 
   
   }
   
   
   if(isset($_POST['submit'])) {
      if(isset($_POST['emails']))
      {
         $emails1 = $_POST['emails'];
         $name = htmlspecialchars($_POST['name']);
         $open_by = htmlspecialchars($_POST['open_by']); 
         $type = htmlspecialchars($_POST['type']); 
         $installer=htmlspecialchars($_POST['installer']);
         $installerphone=htmlspecialchars($_POST['installerphone']);
         $si = htmlspecialchars($_POST['si']); 
         $siphone = $_POST['siphone']; 
         $description = htmlspecialchars($_POST['description']); 
         $date_asked = htmlspecialchars($_POST['date_asked']); 
         if(isset($_POST['address']))
         {
            $address = htmlspecialchars($_POST['address']); 
         }
         $hviagent = htmlspecialchars($_POST['hviagent']); 
         $project_time_start = $_POST['project_time_start']; 
         $project_time_end = $_POST['project_time_end']; 
         $today_date = date("Y-m-d");
      
         $sql = "UPDATE `db`.`service_calls`
         SET
         `name` = '$name',
         `open_by` = '$open_by',
         `type` = '$type',
         `installer` = '$installer',
         `installerphone` = '$installerphone',
         `si` = '$si',
         `siphone` = '$siphone',
         `address` = '$address',
         `date_asked` = '$date_asked',
         `description` = '$description',
         `hviagent` = '$hviagent',
         `project_time_start` = '$project_time_start',
         `project_time_end` = '$project_time_end'
         WHERE `ID` = '$id';";
         if (mysqli_query($con, $sql)) 
         {
            $subject1 = "שונו פרטי הקריאה " . $name;
            $body1 = "שונו פרטי הקריאה " . $name;
            $emails_to_send=array();
            $telegrams_to_send=array();
            $ids = implode(', ', $emails1);
            $result = mysqli_query($con,"SELECT * FROM db.users WHERE ID IN ($ids)");
            while($row = mysqli_fetch_array($result))
            {
               if ($row['email'] == '')
               {

               }
               else
               {
                  array_push($emails_to_send,$row['email']);
               }
               if ($row['telegram'] == '')
               {

               }
               else
               {
                  array_push($telegrams_to_send,$row['telegram']);
               }
               sendmail($subject1,$body1,$emails_to_send);
               sendtelegram($subject1,$telegrams_to_send,$notes);
            }
            header("Refresh:0");
         }
      } 
      else 
      {
         header("Refresh:0");
      }
   } 
   
   
   function notify1($id1,$display_session1,$subject1,$body1,$user_id,$con1,$notes,$address,$date,$time_start,$time_end,$name) {
      $emails_to_send=array();
      $telegrams_to_send=array();
      $ids = implode(', ', $user_id);
      $result = mysqli_query($con1,"SELECT * FROM db.users WHERE ID IN ($ids)");
      while($row = mysqli_fetch_array($result))
      {
         array_push($emails_to_send,$row['email']);
         array_push($telegrams_to_send,$row['telegram']);
      
      }
      $subject2 = $subject1 . " - " . $name;
      sendmail($subject2,$body1,$emails_to_send);
      sendtelegram($subject2,$telegrams_to_send,$notes,$display_session1);
      $insert_id = add_update($id1,$display_session1,$subject1,$con1,$notes,$address,$date,$time_start,$time_end);
      return $insert_id;
   }
   
   function notify2($id1,$display_session1,$subject,$body1,$user_id,$con1,$notes,$date,$name) {
      $emails_to_send=array();
      $telegrams_to_send=array();
      $ids = implode(', ', $user_id);
      $result = mysqli_query($con1,"SELECT * FROM db.users WHERE ID IN ($ids)");
      while($row = mysqli_fetch_array($result))
      {
         if ($row['email'] == ''){
         }
         else{
            array_push($emails_to_send,$row['email']);
         }
         if ($row['telegram'] == ''){
         }
         else{
            array_push($telegrams_to_send,$row['telegram']);
         }
      }
      $subject1 = $subject . " בקריאת השירות - " . $name;
      sendmail($subject1,$body1,$emails_to_send);
      sendtelegram($subject1,$telegrams_to_send,$notes,$display_session1);
      add_update_clear($id1,$subject,$date,$display_session1,$notes,$con1);
   }
   
   function sendmail($subject1,$content1,$emails) {
   $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->Mailer = "smtp";
      $mail->SMTPDebug  = 0;  
      $mail->SMTPAuth   = TRUE;
      $mail->SMTPSecure = "tls";
      $mail->Port       = 587;
      $mail->Host       = "smtp.gmail.com";
      $mail->Username   = "projectshvi@gmail.com ";
      $mail->Password   = "ekxrtjyhqewttvmq";
      $mail->IsHTML(true);
      $mail->CharSet = 'UTF-8';
      $mail->Encoding = 'base64';
      if(!isset($emails))
      {
         
   } 
   
   else
      {
   $arrayLength = count($emails);
   $i = 0;
   while ($i < $arrayLength)
   {
   	$mail->AddAddress($emails[$i]);
   	$i++;
   }
      }   
       $mail->SetFrom("projectshvi@gmail.com", "Projects");
      $mail->Subject = $subject1;
      $content = $content1;
      $mail->MsgHTML($content); 
      if(!$mail->Send()) {
        var_dump($mail);
      } else {
      }
   }
   function sendtelegram($subject1,$numbers1,$notes1,$display_session1) 
   {
      try
      { 
         $apiToken = "5184472528:AAEyx2Y_-T3Hdw9yObExYX7qd1ZjmZXV8bc";
         if ($notes1 == '')
         {
            $text = $subject1 .PHP_EOL . 'על ידי: ' . $display_session1;
         }
         else{
            $text = $subject1 .PHP_EOL. 'פרטים נוספים:' . $notes1 .PHP_EOL . 'על ידי: ' . $display_session1;
         }
      
      
         $arrayLength = count($numbers1);
         $i = 0;
         while ($i < $arrayLength)
         {
            $data = [
               'chat_id' => $numbers1[$i],
               'text' => $text
         ];
         $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data) );
            $i++;
         }
      }
      catch (\Exception $e) 
      {
         
      }
      catch (Throwable $e)
      {
      
      }
   
   }
   function change_status($status,$id1,$con1) {
   $sql = "UPDATE service_calls SET `status` = '$status' WHERE `ID` = $id1;";
   if (mysqli_query($con1, $sql)) { 
   } else { 
   
   }
   }
   function add_update($id,$display_session,$subject,$con2,$notes,$address,$date,$time_start,$time_end) {
   
      $sql2 = "INSERT INTO service_calls_updates (`projects_id`,`project_update`,`project_date`,`project_created_by`,`notes`,`address`,`project_time_start`,`project_time_end`)VALUES('$id','$subject','$date','$display_session','$notes','$address','$time_start','$time_end');";
   
   
   
   if (mysqli_query($con2, $sql2)) {
      return $insert_id = mysqli_insert_id($con2);
   
    } else {
   
     }
   }
   function add_update_clear($id,$subject,$date,$display_session,$notes,$con2) {
   
   $sql = "INSERT INTO service_calls_updates (`projects_id`,`project_update`,`project_date`,`project_created_by`,`notes`)VALUES('$id','$subject','$date','$display_session','$notes');";
   if (mysqli_query($con2, $sql)) 
   {
      header("Refresh:0");
   } 
   else 
   {
      header("Refresh:0");
   
   }
   }
   
   
   if(isset($_POST['servicecall'])) {
   if(isset($_POST['emails'])){
      $name = htmlspecialchars($_POST['name']);;
       $emails1 = $_POST['emails'];
       $notes = htmlspecialchars($_POST['servicecall_notes']);
       $address = htmlspecialchars($_POST['address_to_set_support']);
       $date_old = $_POST['date_to_set_support'];
       $start_time_old = $_POST['time_start_support'];
       $end_time_old = $_POST['time_end_support'];
   
   
       $date = date('Ymd', strtotime($date_old));
       $time_start = date('His', strtotime($start_time_old));
       $time_end = date('His', strtotime($end_time_old));
   
   
   
       $subject1 = "נקבעה תמיכה טכנית - בשטח";
       $content1 = "<body style=\"text-align:right; direction:rtl;\"> <b> $address </b>";
   
   
       change_status($subject1,$id,$con);
       $insert_id = notify1($id,$display_session,$subject1,$content1,$emails1,$con,$notes,$address,$date,$time_start,$time_end,$name);
       $body_final = "http://projects.hviil.co.il/service_calls/add_project_update.php?id=$insert_id";
   
       if(isset($_POST['tech_agent1'])){
         $tech_agent_name='';
         $tech_agent_mail='';
         $tech_agent_id=$_POST['tech_agent1'];
         $ids = implode(', ', $emails1);
         $result = mysqli_query($con,"SELECT * FROM db.users WHERE ID IN ($ids)");
         while($row = mysqli_fetch_array($result))
         {
            $tech_agent_name=$row['displayname'];
            $tech_agent_mail=$row['email'];
         }
      }
      echo "<script> location.href='../service_calls/download_ics.php?date=$date&startTime=$time_start&endTime=$time_end&subject_ics=$name&body_ics=$body_final&call_id=$id&location_ics=$address&notes=$notes&emails=$emails&tech_agent_name=$tech_agent_name&tech_agent_mail=$tech_agent_mail'; </script>";
      exit;
      }
   }
   
   if(isset($_POST['remote_support'])) {
   if(isset($_POST['emails'])){
      $name = htmlspecialchars($_POST['name']);;
       $emails1 = $_POST['emails'];
       $notes = htmlspecialchars($_POST['remote_support_notes']);
       $address = htmlspecialchars($_POST['address_to_set_remote']);
       $date_old = $_POST['date_to_set_remote'];
       $start_time_old = $_POST['time_start_remote'];
       $end_time_old = $_POST['time_end_remote'];
   
   
       $date = date('Ymd', strtotime($date_old));
       $time_start = date('His', strtotime($start_time_old));
       $time_end = date('His', strtotime($end_time_old));
   
   
   
       $subject1 = "נקבעה תמיכה טכנית - מרחוק";
       $content1 = "<body style=\"text-align:right; direction:rtl;\"> <b> $address </b>";
   
   
       change_status($subject1,$id,$con);
       $insert_id = notify1($id,$display_session,$subject1,$content1,$emails1,$con,$notes,$address,$date,$time_start,$time_end,$name);
       $body_final = "http://projects.hviil.co.il/service_calls/add_project_update.php?id=$insert_id";
   
       if(isset($_POST['tech_agent2'])){
         $tech_agent_name='';
         $tech_agent_mail='';
         $tech_agent_id=$_POST['tech_agent2'];
         $ids = implode(', ', $emails1);
         $result = mysqli_query($con,"SELECT * FROM db.users WHERE ID IN ($ids)");
         while($row = mysqli_fetch_array($result))
         {
            $tech_agent_name=$row['displayname'];
            $tech_agent_mail=$row['email'];
         }
      }
      echo "<script> location.href='../service_calls/download_ics.php?date=$date&startTime=$time_start&endTime=$time_end&subject_ics=$name&body_ics=$body_final&call_id=$id&location_ics=$address&notes=$notes&emails=$emails&tech_agent_name=$tech_agent_name&tech_agent_mail=$tech_agent_mail'; </script>";
      exit;
      }
   }
   
   if(isset($_POST['training'])) {
   if(isset($_POST['emails'])){
      $name = htmlspecialchars($_POST['name']);;
       $emails1 = $_POST['emails'];
       $notes = htmlspecialchars($_POST['training_notes']);
       $address = htmlspecialchars($_POST['address_to_set_training']);
       $date_old = $_POST['date_to_set_training'];
       $start_time_old = $_POST['time_start_training'];
       $end_time_old = $_POST['time_end_training'];
   
   
       $date = date('Ymd', strtotime($date_old));
       $time_start = date('His', strtotime($start_time_old));
       $time_end = date('His', strtotime($end_time_old));
   
   
   
       $subject1 = "נקבעה הדרכה";
       $content1 = "<body style=\"text-align:right; direction:rtl;\"> <b> $address </b>";
   
   
       change_status($subject1,$id,$con);
       $insert_id = notify1($id,$display_session,$subject1,$content1,$emails1,$con,$notes,$address,$date,$time_start,$time_end,$name);
       $body_final = "http://projects.hviil.co.il/service_calls/add_project_update.php?id=$insert_id";
   
       if(isset($_POST['tech_agent3'])){
         $tech_agent_name='';
         $tech_agent_mail='';
         $tech_agent_id=$_POST['tech_agent3'];
         $ids = implode(', ', $emails1);
         $result = mysqli_query($con,"SELECT * FROM db.users WHERE ID IN ($ids)");
         while($row = mysqli_fetch_array($result))
         {
            $tech_agent_name=$row['displayname'];
            $tech_agent_mail=$row['email'];
         }
      }
      echo "<script> location.href='../service_calls/download_ics.php?date=$date&startTime=$time_start&endTime=$time_end&subject_ics=$name&body_ics=$body_final&call_id=$id&location_ics=$address&notes=$notes&emails=$emails&tech_agent_name=$tech_agent_name&tech_agent_mail=$tech_agent_mail'; </script>";
      exit;
      }
   }
   
   if(isset($_POST['new_update'])) {
   if(isset($_POST['emails'])){
      $name = $_POST['name'];
       $emails = $_POST['emails'];
       $notes = htmlspecialchars($_POST['new_update_notes']);
       $subject = "נוסף עדכון חדש";
       $subject1 = "בטיפול";
       $content = "<body style=\"text-align:right; direction:rtl;\"> <b> $notes </b>";
       $address = "";
       $today_date = date("Y-m-d");
   
       change_status($subject1,$id,$con);
       notify2($id,$display_session,$subject,$content,$emails,$con,$notes,$today_date,$name);
   
      }
   }
   
   
   
   
   ?>
<html dir="rtl" lang="en" >
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
      <link rel="icon" type="image/png" href="../assets/img/favicon.png">
      <title>
         HVI Dashboard by Tal Sasson
      </title>
      <!--     Fonts and icons     -->
      <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
      <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
      <!-- Nucleo Icons -->
      <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
      <!-- CSS Files -->
      <link href="../assets/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css" rel="stylesheet" />
      <!-- CSS Just for demo purpose, don't include it in your project -->
      <link href="../assets/demo/demo.css" rel="stylesheet" />
   </head>
   <body class=" rtl menu-on-right " >
      <div class="wrapper">
         <div class="sidebar">
            <div class="sidebar-wrapper">
               <div class="logo">
                  <a href="javascript:void(0)" class="simple-text logo-mini">
                  HVI
                  </a>
                  <a href="javascript:void(0)" class="simple-text logo-normal">
                  מערכת ניהול
                  </a>
               </div>
               <ul class="nav">
                  <div id="sidebar"></div>
                  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                  <script type="text/javascript">
                     $(document).ready(function(){
                         $("#sidebar").load("../sidebar/sidebar.php");});
                  </script>  
               </ul>
            </div>
         </div>
         <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
               <div class="container-fluid">
                  <div class="navbar-wrapper">
                     <div class="navbar-toggle d-inline">
                        <button type="button" class="navbar-toggler">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                        </button>
                     </div>
                     <a class="navbar-brand" href="javascript:void(0)">HVI פתרונות אבטחה</a>
                  </div>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-bar navbar-kebab"></span>
                  <span class="navbar-toggler-bar navbar-kebab"></span>
                  <span class="navbar-toggler-bar navbar-kebab"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navigation">
                     <ul class="navbar-nav  mr-auto">
                        <li class="search-bar input-group">
                        </li>
                        <li class="dropdown nav-item">
                        </li>
                        <li class="dropdown nav-item">
                           <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                           <?php echo "שם: " . $display_session; echo "<br> רמת הרשאה: " . $role_session; ?>
                           </a>
                           <ul class="dropdown-menu dropdown-navbar">
                              <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">הגדרות</a></li>
                              <li class="dropdown-divider"></li>
                              <li class="nav-link"><a href="../session/logout.php" class="nav-item dropdown-item">התנתק</a></li>
                           </ul>
                        </li>
                        <li class="separator d-lg-none"></li>
                     </ul>
                  </div>
               </div>
            </nav>
            <div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="SEARCH">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="tim-icons icon-simple-remove"></i>
                        </button>
                     </div>
                  </div>
               </div>
            </div>
            <!-- End Navbar -->
            <div class="content">
               <div class="row">
                  <div class="col">
                     <div class="card ">
                        <div class="card-header text-right" >
                           <h4 class="card-title">פרטים</h4>
                           <div class="card-body">
                              <form method="post" id="form1" enctype="multipart/form-data" accept-charset="UTF-8" onsubmit="loading()">
                                 <div class="container">
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>שם</label>
                                             <input name="name" id= "name" type="text" class="form-control" require placeholder="שם" value = "<?php echo $name; ?>">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>נפתח ע"י</label>
                                             <input name="open_by" type="text" class="form-control" require readonly placeholder="שם הקריאה" value = "<?php echo $open_by; ?>">
                                          </div>
                                       </div>
                                    </div>
                                    <?php
                                       if(isset($project_id))
                                       {
                                       
                                          $result=mysqli_query($con,"select name from projects where ID=$project_id;");
                                          while($row = mysqli_fetch_array($result))
                                             {
                                                if ($row['name'] == '')
                                                {
                                       
                                                }
                                                else
                                                {
                                                   $project_name=$row['name'];
                                                   echo "<h4 class=\"card-title\"></h4>
                                                   <div class=\"row\">
                                                      <div class=\"col\">
                                                         <div class=\"form-group\">
                                                            <label>שם פרויקט</label>
                                                            <input name=\"project_name\" type=\"text\" readonly class=\"form-control\" value=\"$project_name\">
                                       
                                                         </div>
                                                      </div>
                                                   </div>";                                             
                                                }
                                             }
                                          }
                                       
                                       ?>
                                    <script>
                                       document.getElementById('name').onkeypress = function () {
                                          if (event.keyCode === 222) { // apostrophe
                                             // prevent the keypress
                                             return false;
                                          }
                                       };​
                                    </script>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>סטאטוס</label>
                                             <select name="status" class="form-control" data-style="select-with-transition" value="<?php echo $status; ?>">
                                             <?php
                                                if ($role_session == "Admin" || $role_session == "CEO" || $role_session == "COO" || $role_session == "Projects Manager" || $role_session == "Tech Manager")
                                                {
                                                echo "<option class=\"p-3 mb-2 bg-danger text-white\">" . $status . "</option>";
                                                echo "<option class=\"p-3 mb-2 bg-danger text-white\">ממתין לאישור מנהל טכני</option>";
                                                echo "<option class=\"p-3 mb-2 bg-danger text-white\">נקבעה קריאת שירות</option>";
                                                echo "<option class=\"p-3 mb-2 bg-danger text-white\">נקבעה תמיכה מרחוק</option>";
                                                echo "<option class=\"p-3 mb-2 bg-danger text-white\">נקבעה הדרכה</option>";
                                                echo "<option class=\"p-3 mb-2 bg-danger text-white\">בטיפול</option>";
                                                echo "<option class=\"p-3 mb-2 bg-danger text-white\">טופל</option>";
                                                echo "<option class=\"p-3 mb-2 bg-danger text-white\">לא ניתן לפתור</option>";
                                                
                                                
                                                }
                                                elseif ($role_session == "Agent")
                                                {
                                                
                                                   echo 
                                                   "
                                                   <option class=\"p-3 mb-2 bg-danger text-white\">" . $status . "</option>
                                                   ";
                                                }
                                                
                                                else
                                                {
                                                   echo 
                                                   "
                                                   <option class=\"p-3 mb-2 bg-danger text-white\">" . $status . "</option>
                                                   ";
                                                      
                                                }
                                                
                                                
                                                   ?>
                                             </select>
                                          </div>
                                          <div class="row">
                                             <div class="col">
                                                <div class="form-group">
                                                   <label>תאריך הקמה</label>
                                                   <input readonly value="<?php echo $date_created; ?>">
                                                </div>
                                                <h4 class="card-title">פעולות</h4>
                                                <?php
                                                   if ($type == "תמיכה טכנית - בשטח" && ($role_session == "Projects Manager" || $role_session == "Admin" || $role_session == "Tech Manager" || $role_session == "COO"))
                                                   {
                                                     echo "<button type=\"button\" class=\"btn btn-success btn-round\" data-toggle=\"modal\" data-target=\"#servicecall\">תאם קריאת שירות באתר</button>";
                                                     
                                                   }
                                                   if ($type == "תמיכה טכנית - מרחוק" && ($role_session == "Projects Manager" || $role_session == "Admin" || $role_session == "Tech Manager" || $role_session == "COO"))
                                                   {
                                                     echo "<button type=\"button\" class=\"btn btn-success btn-round\" data-toggle=\"modal\" data-target=\"#remote_support\">תאם קריאת שירות מרחוק</button>";
                                                     
                                                   }
                                                   if ($type == "הדרכה" && ($role_session == "Projects Manager" || $role_session == "Admin" || $role_session == "Tech Manager" || $role_session == "COO" ))
                                                   {
                                                     echo "<button type=\"button\" class=\"btn btn-success btn-round\" data-toggle=\"modal\" data-target=\"#training\">תאם הדרכה</button>";
                                                     
                                                   }
                                                   
                                                     ?>
                                                <?php
                                                   if(isset($project_id))
                                                   {
                                                   
                                                      $result=mysqli_query($con,"select name from projects where ID=$project_id;");
                                                      while($row = mysqli_fetch_array($result))
                                                         {
                                                            if ($row['name'] == '')
                                                            {
                                                   
                                                            }
                                                            else
                                                            {
                                                               $project_name=$row['name'];
                                                               echo "<a class=\"btn btn-success btn-round\" href=\"../Projects/edit_project.php?updateid=$project_id\">עבור לעמוד הפרויקט</a>";                                             
                                                            }
                                                         }
                                                      }
                                                   ?>
                                                <a class="btn btn-success btn-round" href="..\service_calls/files.php?project_file_id=<?php echo $id; ?>" >
                                                <span class="icon text-white-50"></span><span class="text">קבצים</span>
                                                </a>  
                                             </div>
                                          </div>
                                          <h4 class="card-title"></h4>
                                          <div class="row">
                                             <div class="col">
                                                <div class="form-group">
                                                   <label>סוכן HVI</label>
                                                   <select name="hviagent" class="form-control" require data-style="select-with-transition">
                                                   <?php
                                                      if ($role_session == "Agent")
                                                      {
                                                      
                                                         echo 
                                                         "
                                                         <option class=\"p-3 mb-2 bg-danger text-white\" value=\"" . $hviagent . "\">" . $hviagent . "</option>
                                                         ";
                                                      }
                                                      else
                                                      {
                                                         echo 
                                                         "
                                                         <option class=\"p-3 mb-2 bg-danger text-white\" value=\"" . $hviagent . "\">" . $hviagent . "</option>
                                                         ";
                                                         $result = mysqli_query($con,"SELECT * FROM db.users where role = 'agent';");
                                                         while($row = mysqli_fetch_array($result))
                                                         {
                                                         echo 
                                                         "
                                                         <option class=\"p-3 mb-2 bg-danger text-white\" value=\"" . $row['displayname'] . "\">" . $row['displayname'] . "</option>
                                                         ";
                                                      
                                                         }
                                                      }
                                                      ?>
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="col">
                                                <div class="form-group">
                                                   <label>סוג הבקשה</label>
                                                   <select name="type" class="form-control" require data-style="select-with-transition">
                                                   <?php 
                                                      echo "<option class=\"p-3 mb-2 bg-danger text-white\">" . $type . "</option>";
                                                      if ($type == "תמיכה טכנית - בשטח"){echo "";}else{echo "<option class=\"p-3 mb-2 bg-danger text-white\" value=\"תמיכה טכנית - בשטח\">תמיכה טכנית - בשטח</option>";}
                                                      if ($type == "תמיכה טכנית - מרחוק"){echo "";}else{echo "<option class=\"p-3 mb-2 bg-danger text-white\" value=\"תמיכה טכנית - מרחוק\">תמיכה טכנית - מרחוק</option>";}
                                                      if ($type == "הדרכה"){echo "";}else{echo "<option class=\"p-3 mb-2 bg-danger text-white\" value=\"הדרכה\">הדרכה</option>";}
                                                      if ($type == "בדיקה"){echo "";}else{echo "<option class=\"p-3 mb-2 bg-danger text-white\" value=\"בדיקה\">בדיקה</option>";}
                                                      ?>
                                                   </select>
                                                </div>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col">
                                                <label>תאריך מבוקש לביצוע</label>
                                                <div class="form-group">
                                                   <label>תאריך</label>
                                                   <input  name="date_asked" form="form1" type="date" require value="<?php echo $date_asked; ?>">
                                                   <br>
                                                   <label>שעת התחלה</label>
                                                   <input  name="project_time_start" form="form1" type="time" require value="<?php echo $project_time_start; ?>">
                                                   <br>
                                                   <label>שעת סיום</label>
                                                   <input  name="project_time_end" form="form1" type="time" require value="<?php echo $project_time_end; ?>">
                                                   <br>
                                                </div>
                                             </div>
                                             <?php 
                                                if ($type == "תמיכה מרחוק")
                                                {
                                                
                                                } 
                                                if ($type == "תמיכה טכנית - בשטח" || $type == "הדרכה")
                                                {
                                                   echo "<div class=\"col\">
                                                   <div class=\"form-group\">
                                                      <label>כתובת</label>
                                                      <input name=\"address\" type=\"text\" class=\"form-control\" required placeholder=\"כתובת\"  value = \"$address\">
                                                   </div></div>";
                                                
                                                } 
                                                ?>
                                          </div>
                                          <h4 class="card-title">אנשי קשר</h4>
                                          <div class="row">
                                             <div class="col">
                                                <div class="form-group">
                                                   <label>איש קשר / מתקין</label>
                                                   <input name="installer" type="text" class="form-control" required placeholder="שם"  value = "<?php echo $installer; ?>">
                                                </div>
                                             </div>
                                             <div class="col">
                                                <div class="form-group">
                                                   <label>טלפון</label>
                                                   <input name="installerphone" type="text" class="form-control" required placeholder="טלפון" value = "<?php echo $installerphone; ?>">
                                                </div>
                                             </div>
                                          </div>
                                          <div class="row">
                                             <div class="col">
                                                <div class="form-group">
                                                   <label>מפיץ / אינטגרטור</label>
                                                   <input name="si" type="text" class="form-control" placeholder="שם"  value = "<?php echo $si; ?>">
                                                </div>
                                             </div>
                                             <div class="col">
                                                <div class="form-group">
                                                   <label>טלפון</label>
                                                   <input name="siphone" type="text" class="form-control" placeholder="טלפון" value = "<?php echo $siphone; ?>">
                                                </div>
                                             </div>
                                          </div>
                                          <br>
                                          <h4 class="card-title">תיאור</h4>
                                          <div class="row">
                                             <div class="col">
                                                <textarea name="description" rows="5" class="form-control" required placeholder="תיאור"><?php echo $description; ?></textarea>
                                             </div>
                                          </div>
                                          <br>
                                          <br>
                                          <button type="button" class="btn btn-primary btn-round" data-toggle="modal" data-target="#submit">עדכן</button>
                                          <button type="button" class="btn btn-primary btn-round" data-toggle="modal" data-target="#delete">מחק</button>
                              </form>
                              </div>
                              </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col">
                     <div class="card ">
                        <div class="card-header text-right">
                           <h4 class="card-title">עדכונים </h4>
                           <button type="button" class="btn btn-success btn-round" data-toggle="modal" data-target="#new_update">הוסף עדכון</button>
                           <div class="table-responsive">
                              <table id="myTable" class="table">
                                 <thead>
                                    <tr>
                                       <th>תאריך</th>
                                       <th>שעת התחלת</th>
                                       <?php if ($type == "הגעה לאתר"){echo "<th>כתובת</th>";} ?>
                                       <th>הערות</th>
                                       <th>נקבע ע"י</th>
                                       <th>בוצע ע"י</th>
                                       <?php if ($role_session == "הגעה לאתר"){echo "<th>כתובת</th>";} ?>
                                       <th>הערות</th>
                                       <th>מחיקה</th>
                                       <th>בוצע</th>
                                    </tr>
                                 </thead>
                                 <?php
                                    $result = mysqli_query($con,"SELECT * FROM service_calls_updates where projects_id = '$id' ORDER BY ID DESC;");
                                    while($row = mysqli_fetch_array($result))
                                    {
                                       $id_update = $row['ID'];
                                       $datefromserver = $row['project_date'];
                                    
                                       $date = date('d-m-Y', strtotime($datefromserver));
                                    
                                    echo "<tbody>";											
                                    echo "<tr>";
                                    if ($date == "12:00:00 30-11--0001"){echo "<td></td>";}else{echo "<td><strong style=\"font-size: 13px;\">". $date ."</strong></td>";}
                                    if ($date == "12:00:00 30-11--0001"){echo "<td></td>";}else{echo "<td><strong style=\"font-size: 13px;\">". $row['project_time_start'] ."</strong></td>";}
                                    
                                    if ($type == "הגעה לאתר"){echo "<td><strong style=\"font-size: 13px;\">". $row['address'] ."</strong></td>";}
                                    
                                    if ($row['notes'] == "")
                                    {
                                       echo "<td></td>";	
                                    }else{
                                       echo "<td><button type=\"button\" class=\"btn btn-danger btn-sm btn-round btn-icon\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . $row['notes'] . "\">
                                       <i class=\"fas fa-eye\"></i>
                                       </button></td>";		
                                    }	
                                    echo "<td><strong style=\"font-size: 13px;\">". $row['project_created_by'] ."</strong></td>";
                                    echo "<td><strong style=\"font-size: 13px;\">". $row['project_done_by'] ."</strong></td>";
                                    
                                    
                                    if ($row['call_notes'] == "")
                                    {
                                       echo "<td></td>";	
                                    }else{
                                       echo "<td><button type=\"button\" class=\"btn btn-danger btn-sm btn-round btn-icon\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"" . $row['call_notes'] . "\">
                                       <i class=\"fas fa-eye\"></i>
                                       </button></td>";	
                                    }	
                                    
                                    
                                    if ($row['project_created_by'] == $display_session)
                                    {
                                       echo "<td><a href=\"delete_project_update.php?deleteupdateid=" . $id_update . "\"class=\"btn btn-danger btn-sm btn-round btn-icon\"><i class=\"fas fa-eraser\"></i></a></td>";
                                    
                                    }else{
                                       echo "<td></td>";	
                                    }
                                    
                                    
                                    if ($date == "12:00:00 30-11--0001"){echo "<td></td>";}else{ echo "<td><a href=\"add_project_update.php?id=" . $id_update . "&call_id=" . $id . "\"class=\"btn btn-danger btn-sm btn-round btn-icon\"><i class=\"fas fa-check\"></i></a></td>";}
                                    
                                    
                                    
                                    echo "</tr>";
                                    echo "</tbody>";											
                                       
                                    }
                                    echo "</table>";
                                    
                                    ?>
                              </table>
                           </div>
                        </div>
                        <div class="card-body">
                           <div class="table-responsive">
                              <table id="myTable" class="table">
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <footer class="footer">
                  <div class="container-fluid">
                     <div class="copyright">
                        ©
                        <a href="javascript:void(0)" target="_blank">Tal Sasson</a>
                     </div>
                  </div>
               </footer>
            </div>
         </div>
         <div class="fixed-plugin">
            <div class="dropdown show-dropdown">
               <a href="#" data-toggle="dropdown">
               <i class="fa fa-cog fa-2x"> </i>
               </a>
               <ul class="dropdown-menu">
                  <li class="header-title"> צבע מערכת</li>
                  <li class="adjustments-line">
                     <a href="javascript:void(0)" class="switch-trigger background-color">
                        <div class="badge-colors text-center">
                           <span class="badge filter badge-primary active" data-color="primary"></span>
                           <span class="badge filter badge-info" data-color="blue"></span>
                           <span class="badge filter badge-success" data-color="green"></span>
                        </div>
                        <div class="clearfix"></div>
                     </a>
                  </li>
                  <li class="adjustments-line text-center color-change">
                     <span class="color-label">מצב בהיר</span>
                     <span class="badge light-badge mr-2"></span>
                     <span class="badge dark-badge ml-2"></span>
                     <span class="color-label">מצב כהה</span>
                  </li>
               </ul>
            </div>
         </div>
         <!--                          MODALS                                -->
         <div class="<?php echo $modal_background ?>" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-body" style="text-align: right;">
                     <div class="row">
                        <div class="col">
                           <div class="modal-header">
                              <h4 class="modal-title">האם אתה בטוח?</h4>
                           </div>
                           <br>
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                        <button type="submit" form="form1" name="delete"  class="btn btn-primary btn-round">מחק</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         
      <div class="<?php echo $modal_background ?>" id="servicecall" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">תיאום קריאה</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
                  <div class="row">
                     <div class="col">
                        <h5 class="modal-title" id="exampleModalLabel">את מי לעדכן?</h5>
                        <?php
                           $result = mysqli_query($con,"SELECT * FROM db.users  WHERE role = 'Tech Manager' or role = 'COO' or role = 'Tech' or role = 'Projects Manager' or displayname = '$hviagent'");
                           while($row = mysqli_fetch_array($result))
                           {
                                            echo 
                                            "<div class=\"form-check\" style=\"dir: rtl;\">
                                                  <label class=\"form-check-label\">
                                                  
                                                     <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\"  value=" . $row['ID'] . " id=" . $row['displayname'] . ">
                                                     " . $row['displayname'] . "
                                                     <span class=\"form-check-sign\">
                                                        <span class=\"check\"></span>
                                                     </span>
                                                  </label>
                                            </div>";
                                            }
                           ?>
                     </div>
                     <div class="col">
                        <h5 class="modal-title">מבצע המשימה?</h5>
                        <?php
                           $result = mysqli_query($con,"SELECT * FROM db.users  WHERE role = 'Tech'  or role = 'Tech Manager' or role = 'Projects Manager'; ");
                           while($row = mysqli_fetch_array($result))
                           {
                              echo "<div class=\"form-check form-check-radio\" style=\"dir: rtl;\">
                              <label class=\"form-check-label\" style=\"dir: rtl;\">
                                    <input class=\"form-check-input\"  form=\"form1\" name=\"tech_agent1[]\" type=\"radio\" name=\"exampleRadios\" id=\"exampleRadios1\" value=\"" . $row['ID'] . "\" >
                                    " . $row['displayname'] . "
                                    <span class=\"form-check-sign\"></span>
                              </label>
                              </div>";
                           }
                           
                           
                           ?>
                     </div>
                     <div class="col">
                        <label>תאריך</label>
                        <br>
                        <input  name="date_to_set_support" form="form1" type="date" require value="<?php echo $date_asked; ?>">
                        <br>
                        <label>שעת התחלה</label>
                        <br>
                        <input  name="time_start_support" form="form1" type="time" require value="<?php echo $project_time_start; ?>">
                        <br>
                        <label>שעת סיום</label>
                        <br>
                        <input  name="time_end_support" form="form1" type="time" require value="<?php echo $project_time_end; ?>">
                        <br>
                        <label>כתובת</label>
                        <br>
                        <input name="address_to_set_support" form="form1" type="text" class="form-control" placeholder="כתובת" require value="<?php echo $address; ?>">
                     </div>
                     <br>
                     <label>הערות</label>
                     <textarea form="form1" name="servicecall_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="servicecall"  class="btn btn-primary btn-round">תאם</button>
               </div>
            </div>
         </div>
      </div>
      </div>

      <div class="<?php echo $modal_background ?>" id="remote_support" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">תיאום קריאה</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
                  <div class="row">
                     <div class="col">
                        <h5 class="modal-title" id="exampleModalLabel">את מי לעדכן?</h5>
                        <?php
                           $result = mysqli_query($con,"SELECT * FROM db.users  WHERE role = 'Tech Manager' or role = 'COO' or role = 'Tech' or role = 'Projects Manager' or displayname = '$hviagent'");
                           while($row = mysqli_fetch_array($result))
                           {
                                            echo 
                                            "<div class=\"form-check\" style=\"dir: rtl;\">
                                                  <label class=\"form-check-label\">
                                                  
                                                     <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\"  value=" . $row['ID'] . " id=" . $row['displayname'] . ">
                                                     " . $row['displayname'] . "
                                                     <span class=\"form-check-sign\">
                                                        <span class=\"check\"></span>
                                                     </span>
                                                  </label>
                                            </div>";
                                            }
                           ?>
                     </div>
                     <div class="col">
                        <h5 class="modal-title">מבצע המשימה?</h5>
                        <?php
                           $result = mysqli_query($con,"SELECT * FROM db.users  WHERE role = 'Tech'  or role = 'Tech Manager' or role = 'Projects Manager'; ");
                           while($row = mysqli_fetch_array($result))
                           {
                              echo "<div class=\"form-check form-check-radio\" style=\"dir: rtl;\">
                              <label class=\"form-check-label\" style=\"dir: rtl;\">
                                    <input class=\"form-check-input\"  form=\"form1\" name=\"tech_agent2[]\" type=\"radio\" name=\"exampleRadios\" id=\"exampleRadios1\" value=\"" . $row['ID'] . "\" >
                                    " . $row['displayname'] . "
                                    <span class=\"form-check-sign\"></span>
                              </label>
                              </div>";
                           }
                           
                           
                           ?>
                     </div>
                     <div class="col">
                        <label>תאריך</label>
                        <br>
                        <input  name="date_to_set_remote" form="form1" type="date" require value="<?php echo $date_asked; ?>">
                        <br>
                        <label>שעת התחלה</label>
                        <br>
                        <input  name="time_start_remote" form="form1" type="time" require value="<?php echo $project_time_start; ?>">
                        <br>
                        <label>שעת סיום</label>
                        <br>
                        <input  name="time_end_remote" form="form1" type="time" require value="<?php echo $project_time_end; ?>">
                        <br>
                        <label>כתובת</label>
                        <br>
                        <input name="address_to_set_remote" form="form1" type="text" class="form-control" placeholder="כתובת" require value="<?php echo $address; ?>">
                     </div>
                     <br>
                     <label>הערות</label>
                     <textarea form="form1" name="remote_support_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="remote_support"  class="btn btn-primary btn-round">תאם</button>
               </div>
            </div>
         </div>
      </div>
      </div>
      <div class="<?php echo $modal_background ?>" id="training" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">תיאום קריאה</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
                  <div class="row">
                     <div class="col">
                        <h5 class="modal-title" id="exampleModalLabel">את מי לעדכן?</h5>
                        <?php
                           $result = mysqli_query($con,"SELECT * FROM db.users  WHERE role = 'Tech Manager' or role = 'COO' or role = 'Tech' or role = 'Projects Manager' or displayname = '$hviagent'");
                           while($row = mysqli_fetch_array($result))
                           {
                                            echo 
                                            "<div class=\"form-check\" style=\"dir: rtl;\">
                                                  <label class=\"form-check-label\">
                                                  
                                                     <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\"  value=" . $row['ID'] . " id=" . $row['displayname'] . ">
                                                     " . $row['displayname'] . "
                                                     <span class=\"form-check-sign\">
                                                        <span class=\"check\"></span>
                                                     </span>
                                                  </label>
                                            </div>";
                                            }
                           ?>
                     </div>
                     <div class="col">
                        <h5 class="modal-title">מבצע המשימה?</h5>
                        <?php
                           $result = mysqli_query($con,"SELECT * FROM db.users  WHERE role = 'Tech'  or role = 'Tech Manager' or role = 'Projects Manager'; ");
                           while($row = mysqli_fetch_array($result))
                           {
                              echo "<div class=\"form-check form-check-radio\" style=\"dir: rtl;\">
                              <label class=\"form-check-label\" style=\"dir: rtl;\">
                                    <input class=\"form-check-input\"  form=\"form1\" name=\"tech_agent3[]\" type=\"radio\" name=\"exampleRadios\" id=\"exampleRadios1\" value=\"" . $row['ID'] . "\" >
                                    " . $row['displayname'] . "
                                    <span class=\"form-check-sign\"></span>
                              </label>
                              </div>";
                           }
                           
                           
                           ?>
                     </div>
                     <div class="col">
                        <label>תאריך</label>
                        <br>
                        <input  name="date_to_set_training" form="form1" type="date" require value="<?php echo $date_asked; ?>">
                        <br>
                        <label>שעת התחלה</label>
                        <br>
                        <input  name="time_start_training" form="form1" type="time" require value="<?php echo $project_time_start; ?>">
                        <br>
                        <label>שעת סיום</label>
                        <br>
                        <input  name="time_end_training" form="form1" type="time" require value="<?php echo $project_time_end; ?>">
                        <br>
                        <label>כתובת</label>
                        <br>
                        <input name="address_to_set_training" form="form1" type="text" class="form-control" placeholder="כתובת" require value="<?php echo $address; ?>">
                     </div>
                     <br>
                     <label>הערות</label>
                     <textarea form="form1" name="training_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="training"  class="btn btn-primary btn-round">תאם</button>
               </div>
            </div>
         </div>
      </div>
      </div>
      <div class="<?php echo $modal_background ?>" id="submit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-body" style="text-align: right;">
                  <div class="row">
                     <h5 class="modal-title" id="exampleModalLabel">את מי לעדכן?</h5>
                     <div class="col">
                        <?php
                           $result = mysqli_query($con,"SELECT * FROM db.users  WHERE role = 'Tech Manager' or role = 'COO' or role = 'Tech' or role = 'Projects Manager' or displayname = '$hviagent'");
                           while($row = mysqli_fetch_array($result))
                           {
                                            echo 
                                            "<div class=\"form-check\" style=\"dir: rtl;\">
                                                  <label class=\"form-check-label\">
                                                  
                                                     <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\"  value=" . $row['ID'] . " id=" . $row['displayname'] . ">
                                                     " . $row['displayname'] . "
                                                     <span class=\"form-check-sign\">
                                                        <span class=\"check\"></span>
                                                     </span>
                                                  </label>
                                            </div>";
                                            }
                           ?>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="submit"  class="btn btn-primary btn-round">עדכן</button>
               </div>
            </div>
         </div>
      </div>
      </div>
      <div class="<?php echo $modal_background ?>" id="new_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-body" style="text-align: right;">
                  <div class="row">
                     <h5 class="modal-title" id="exampleModalLabel">את מי לעדכן?</h5>
                     <div class="col">
                        <?php
                           $result = mysqli_query($con,"SELECT * FROM db.users  WHERE role = 'Tech Manager' or role = 'COO' or role = 'Tech' or role = 'Projects Manager' or displayname = '$hviagent'");
                           while($row = mysqli_fetch_array($result))
                           {
                                            echo 
                                            "<div class=\"form-check\" style=\"dir: rtl;\">
                                                  <label class=\"form-check-label\">
                                                  
                                                     <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\"  value=" . $row['ID'] . " id=" . $row['displayname'] . ">
                                                     " . $row['displayname'] . "
                                                     <span class=\"form-check-sign\">
                                                        <span class=\"check\"></span>
                                                     </span>
                                                  </label>
                                            </div>";
                                            }
                           ?>
                     </div>
                  </div>
                  <label>הערות</label>
                  <textarea form="form1" name="new_update_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="new_update"  class="btn btn-primary btn-round">עדכן</button>
               </div>
            </div>
         </div>
      </div>
      </div>
      <div class="<?php echo $modal_background ?>" id="loading" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
               </div>
               <div class="modal-body" style="text-align: right;">
                  <img src="..\assets\img\loading.png" alt="Paris" class="center">
                  <h3 style="text-align:center;">אנא המתן.....</h3>
                  <h3 class="modal-title" id="exampleModalLabel"> </h3>
                  <h3 class="modal-title" id="exampleModalLabel"> </h3>
                  <h3 class="modal-title" id="exampleModalLabel"> </h3>
                  <h3 class="modal-title" id="exampleModalLabel"> </h3>
                  <h3 class="modal-title" id="exampleModalLabel"> </h3>
                  <h3 class="modal-title" id="exampleModalLabel"> </h3>
               </div>
            </div>
         </div>
      </div>
      </div>
      <?php
         echo 
         "<div class=\"form-check\" style=\"dir: rtl;\">
               <label class=\"form-check-label\">
               
                  <input  class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"hidden\"  value=\"0\" id=\"Nobody\" checked>
                  Nobody
                  <span class=\"form-check-sign\">
                     <span class=\"check\"></span>
                  </span>
               </label>
         </div>";
         ?>
      <!--   Core JS Files   -->
      <script>
         function loading() {
            $('#loading').modal('toggle');
         }
      </script>
      <script src="../assets/js/main.js"></script>
      <script src="../assets/js/core/jquery.min.js"></script>
      <script src="../assets/js/core/popper.min.js"></script>
      <script src="../assets/js/core/bootstrap.min.js"></script>
      <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
      <!--  Google Maps Plugin    -->
      <!-- Place this tag in your head or just before your close body tag. -->
      <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
      <!-- Chart JS -->
      <script src="../assets/js/plugins/chartjs.min.js"></script>
      <!--  Notifications Plugin    -->
      <script src="../assets/js/plugins/bootstrap-notify.js"></script>
      <!-- Control Center for Black Dashboard: parallax effects, scripts for the example pages etc -->
      <script src="../assets/js/black-dashboard.min.js?v=1.0.0"></script>
      <script src="../assets/js/plugins/bootstrap-notify.js"></script>
      <!-- Black Dashboard DEMO methods, don't include it in your project! -->
      <script src="../assets/demo/demo.js"></script>
      <script>
         $(document).ready(function() {
           $().ready(function() {
             $sidebar = $('.sidebar');
             $navbar = $('.navbar');
             $main_panel = $('.main-panel');
         
             $full_page = $('.full-page');
         
             $sidebar_responsive = $('body > .navbar-collapse');
             sidebar_mini_active = true;
             white_color = false;
         
             window_width = $(window).width();
         
             fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();
         
         
         
             $('.fixed-plugin a').click(function(event) {
               if ($(this).hasClass('switch-trigger')) {
                 if (event.stopPropagation) {
                   event.stopPropagation();
                 } else if (window.event) {
                   window.event.cancelBubble = true;
                 }
               }
             });
         
             $('.fixed-plugin .background-color span').click(function() {
               $(this).siblings().removeClass('active');
               $(this).addClass('active');
         
               var new_color = $(this).data('color');
         
               if ($sidebar.length != 0) {
                 $sidebar.attr('data', new_color);
               }
         
               if ($main_panel.length != 0) {
                 $main_panel.attr('data', new_color);
               }
         
               if ($full_page.length != 0) {
                 $full_page.attr('filter-color', new_color);
               }
         
               if ($sidebar_responsive.length != 0) {
                 $sidebar_responsive.attr('data', new_color);
               }
             });
         
             $('.switch-sidebar-mini input').on("switchChange.bootstrapSwitch", function() {
               var $btn = $(this);
         
               if (sidebar_mini_active == true) {
                 $('body').removeClass('sidebar-mini');
                 sidebar_mini_active = false;
                 blackDashboard.showSidebarMessage('Sidebar mini deactivated...');
               } else {
                 $('body').addClass('sidebar-mini');
                 sidebar_mini_active = true;
                 blackDashboard.showSidebarMessage('Sidebar mini activated...');
               }
         
               // we simulate the window Resize so the charts will get updated in realtime.
               var simulateWindowResize = setInterval(function() {
                 window.dispatchEvent(new Event('resize'));
               }, 180);
         
               // we stop the simulation of Window Resize after the animations are completed
               setTimeout(function() {
                 clearInterval(simulateWindowResize);
               }, 1000);
             });
         
             $('.switch-change-color input').on("switchChange.bootstrapSwitch", function() {
               var $btn = $(this);
         
               if (white_color == true) {
         
                 $('body').addClass('change-background');
                 setTimeout(function() {
                   $('body').removeClass('change-background');
                   $('body').removeClass('white-content');
                 }, 900);
                 white_color = false;
               } else {
         
                 $('body').addClass('change-background');
                 setTimeout(function() {
                   $('body').removeClass('change-background');
                   $('body').addClass('white-content');
                 }, 900);
         
                 white_color = true;
               }
         
         
             });
         
                    $('.light-badge').click(function() {
          $('body').addClass('white-content');
          $.ajax({
                            type: "POST",
                            url: '../session/dark_mode.php',
                            data: {mode: 'false'},
                            success: function(data){
                            console.log(data);
                            },
                            error: function(xhr, status, error){
                            console.error(xhr);
                            }
                            });
         });
         
         $('.dark-badge').click(function() {
          $('body').removeClass('white-content');
          $.ajax({
                            type: "POST",
                            url: '../session/dark_mode.php',
                            data: {mode: 'true'},
                            success: function(data){
                            console.log(data);
                            },
                            error: function(xhr, status, error){
                            console.error(xhr);
                            }
                            });
         });
         <?php 
            if ($dark_mode == "false")
            {
                  echo"$(document).ready(function() {\$('body').addClass('white-content');});";
            }
            if ($dark_mode == "true")
            {
                  echo"$(document).ready(function() {\$('body').removeClass('white-content');});";
            }
            ?>
           });
         });
      </script>
      <script>
         $(document).ready(function() {
         
           demo.initDashboardPageCharts();
         
         });
      </script>
   </body>
</html>