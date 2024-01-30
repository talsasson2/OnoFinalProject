<?php 
   include('../session/session.php');
   include_once('../session/connection.php');
   $useragent=$_SERVER['HTTP_USER_AGENT'];
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;
   require '../PHPMailer-master/src/Exception.php';
   require '../PHPMailer-master/src/PHPMailer.php';
   require '../PHPMailer-master/src/SMTP.php';

   if(isset($_GET['project_id']))
   {
      $project_id=$_GET['project_id'];
      $project_name=$_GET['name'];
      $hviagent=$_GET['hviagent'];
      $installer=$_GET['installer'];
      $installerphone=$_GET['installerphone'];
      $si=$_GET['si'];
      $siphone=$_GET['siphone'];

   }


	if(isset($_POST['submit'])) {
      $name = htmlspecialchars($_POST['name']); 
      $open_by = htmlspecialchars($_POST['open_by']); 
      $type = htmlspecialchars($_POST['type']); 
		$installer=htmlspecialchars($_POST['installer']);
		$installerphone=htmlspecialchars($_POST['installerphone']);
		$si = htmlspecialchars($_POST['si']); 
		$siphone = $_POST['siphone']; 
      $project_time_start = $_POST['project_time_start']; 
      $project_time_end = $_POST['project_time_end']; 
      $date_created = date('Y-m-d H:i:s');

      
      if(isset($project_id))
      {
         $project_id=$_GET['project_id'];
      }
      else{
         $project_id = 0;
      }
 


		$description = htmlspecialchars($_POST['description']); 
      $address = htmlspecialchars($_POST['address']); 
		$hviagent = htmlspecialchars($_POST['hviagent']); 
      $date_old = $_POST['date_asked'];
      $date_asked = date('Y-m-d', strtotime($date_old));

      $today_date = date("Y-m-d");
	
      $sql = "INSERT INTO `db`.`service_calls`
      (`name`,
      `open_by`,
      `type`,
      `installer`,
      `installerphone`,
      `si`,
      `siphone`,
      `startdate`,
      `responsibility`,
      `address`,
      `date_asked`,
      `description`,
      `status`,
      `hviagent`,
      `project_time_start`,
      `project_time_end`,
      `project_id`,
      `date_created`)
      VALUES
      ('$name',
      '$open_by',
      '$type',
     '$installer',
      '$installerphone',
      '$si',
     '$siphone',
      '$today_date',
      'Tech Manager',
      '$address',
      '$date_asked',
      '$description',
      'ממתין לאישור מנהל טכני',
      '$hviagent',
      '$project_time_start',
      '$project_time_end',
      '$project_id',
      '$date_created');";
		if (mysqli_query($con, $sql)) {

         $subject = "נוספה קריאה חדשה במערכת";
         $body = "<body style=\"text-align:right; direction:rtl;\">$display_session הוסיף קריאת שירות חדשה <b> ";
         notify($subject,$body,$con);
         header("location: ../service_calls.php");

       } else {

       }

   } 
   

   function notify($subject1,$body1,$con1) {
         $emails_to_send=array();
         $telegrams_to_send=array();
         $result = mysqli_query($con1,"SELECT * FROM db.users WHERE role = 'COO' or role = 'Tech Manager';");
         while($row = mysqli_fetch_array($result))
         {
         array_push($emails_to_send,$row['email']);
         array_push($telegrams_to_send,$row['telegram']);
         }
         sendmail($subject1,$body1,$emails_to_send);
         sendtelegram($subject1,$telegrams_to_send);
      }

   function sendmail($subject1,$content1,$emails1) {
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
		if(!isset($emails1)){
		} 

		else{
			$arrayLength = count($emails1);
			$i = 0;
			while ($i < $arrayLength)
			{
				$mail->AddAddress($emails1[$i]);
				$i++;
			}
		}

		 $mail->SetFrom("projectshvi@gmail.com", "Projects");
      $mail->Subject = $subject1;
		$mail->MsgHTML($content1); 
		if(!isset($emails1)){
		} 

		else
		{
			if(!$mail->Send()) {
				echo "Error while sending Email.";
			} 
			else 
			{

			}
		}
      
      
   }
   function sendtelegram($subject1,$numbers1) {

      try
      { 
         $apiToken = "5184472528:AAEyx2Y_-T3Hdw9yObExYX7qd1ZjmZXV8bc";

         $arrayLength = count($numbers1);
         $i = 0;
         while ($i < $arrayLength)
         {
            $data = [
               'chat_id' => $numbers1[$i],
               'text' => $subject1
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

  

  
  ?>
<html dir="rtl" lang="en" >
   <head>
      <meta charset="utf-8" />
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
                           <h4 class="card-title">פרטי קריאת שירות</h4>
                           <div class="card-body">
                              <form method="post" id="form1" onsubmit="loading()">
                                 <div class="container">
                                 <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>שם</label>
                                             <input name="name" type="text" class="form-control" required value="<?php if(isset($project_id)) {echo $project_name;}?>">

                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>נוצר ע"י</label>
                                             <input name="open_by" type="text" class="form-control" require readonly placeholder="שם הקריאה" value="<?php echo $display_session; ?>">
                                          </div>
                                       </div>
                                    </div>
                                    <?php
                                    if(isset($_GET['project_id']))
                                    {
                                       $project_id=$_GET['project_id'];
                                       $project_name=$_GET['name'];

                                       
                                       echo "<h4 class=\"card-title\"></h4>
                                       <div class=\"row\">
                                          <div class=\"col\">
                                             <div class=\"form-group\">
                                             <label>מזהה פרויקט</label>
                                             <input name=\"project_id\" type=\"number\" readonly class=\"form-control\" value=\"$project_id\">
   
                                             </div>
                                          </div>
                                          <div class=\"col\">
                                             <div class=\"form-group\">
                                                <label>שם פרויקט</label>
                                                <input name=\"project_name\" type=\"text\" readonly class=\"form-control\" value=\"$project_name\">
   
                                             </div>
                                          </div>
                                       </div>";
                                    }
                                    ?>
                                    

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
                                                   <option class=\"p-3 mb-2 bg-danger text-white\"  value=\"" . $display_session . "\">" . $display_session . "</option>
                                                   ";
                                                }
                                                else
                                                {
                                                   echo 
                                                   "
                                                   <option class=\"p-3 mb-2 bg-danger text-white\"  value=\"" . $hviagent . "\">" . $hviagent . "</option>
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
                                             <option class="p-3 mb-2 bg-danger text-white" value="תמיכה טכנית - בשטח">תמיכה טכנית - בשטח</option>
                                             <option class="p-3 mb-2 bg-danger text-white" value="תמיכה טכנית - מרחוק">תמיכה טכנית - מרחוק</option>
                                             <option class="p-3 mb-2 bg-danger text-white" value="הדרכה">הדרכה</option>
                                             <option class="p-3 mb-2 bg-danger text-white" value="בדיקה">בדיקה</option>

                                             </select>
                                          </div>
                                       </div>
                                    </div>

                                    <h4 class="card-title">אנשי קשר</h4>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>איש קשר / מתקין</label>
                                             <input name="installer" type="text" class="form-control" required placeholder="שם"  value="<?php if(isset($installer)) {echo $installer;}?>">
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="form-group">
                                             <label>טלפון</label>
                                             <input name="installerphone" type="text" class="form-control" required placeholder="טלפון"  value="<?php if(isset($installerphone)) {echo $installerphone;}?>">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>מפיץ / אינטגרטור</label>
                                             <input name="si" type="text" class="form-control" placeholder="שם" value="<?php if(isset($si)) {echo $si;}?>">
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="form-group">
                                             <label>טלפון</label>
                                             <input name="siphone" type="text" class="form-control" placeholder="טלפון"  value="<?php if(isset($siphone)) {echo $siphone;}?>">
                                          </div>
                                       </div>
                                    </div>

                                    <br>
                                    <h4 class="card-title">תיאור</h4>
                                    <div class="row">
                                       <div class="col">
                                          <textarea name="description" rows="5" class="form-control" required placeholder="תיאור"></textarea>
                                       </div>
                                    </div>
                                    <br>
                                    <br>
                                    <button type="button" class="btn btn-primary btn-round" data-toggle="modal" data-target="#submit">הוסף</button>
                              </form>
                              </div>
                           </div>
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
      <div class="<?php echo $modal_background ?>" id="submit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel">תאריך ושעה רצויים?</h4>
         </div>
         <div class="modal-body" style="text-align: right;">
         <div class="row">
         <div class="col">
         <label>תאריך</label>
                  <input  name="date_asked" form="form1" type="date" require value="2022-01-01">
                  <br>

                  <label>שעת התחלה</label>
                  <input  name="project_time_start" form="form1" type="time" require value="08:30">
                  <br>

                  <label>שעת סיום</label>
                  <input  name="project_time_end" form="form1" type="time" require value="17:30">
                  <br>
            <li class="header-title">כתובת?</li>
            <input name="address" form="form1" type="text" class="form-control" placeholder="כתובת" value="ללא כתובת">

            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
            <button type="submit" form="form1" name="submit" class="btn btn-primary btn-round">שמור פרויקט</button>

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
                            url: './session/dark_mode.php',
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
                            url: './session/dark_mode.php',
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