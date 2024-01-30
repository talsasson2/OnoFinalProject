<?php 
   include('../session/session.php');
   include_once('../session/connection.php');
   $useragent=$_SERVER['HTTP_USER_AGENT'];
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;
   require '../PHPMailer-master/src/Exception.php';
   require '../PHPMailer-master/src/PHPMailer.php';
   require '../PHPMailer-master/src/SMTP.php';

   


	if(isset($_POST['submit'])) {
		$name = htmlspecialchars($_POST['name']); 
		$hviagent = htmlspecialchars($_POST['hviagent']); 
		$si = htmlspecialchars($_POST['si']); 
		$siphone = $_POST['siphone']; 
		$endcustomer = htmlspecialchars($_POST['endcustomer']); 
		$endcustomerphone = $_POST['endcustomerphone']; 
		$hikagent = htmlspecialchars($_POST['hikagent']); 
		$installer=htmlspecialchars($_POST['installer']);
		$installerphone=htmlspecialchars($_POST['installerphone']);
		$value = $_POST['value']; 
		$description = htmlspecialchars($_POST['description']); 
		$requirements = htmlspecialchars($_POST['requirements']); 
      $today_date = date("Y-m-d");

      if(isset($_POST['emails'])){
         $user_id = $_POST['emails'];

      }
		
      $sql = "INSERT INTO db.projects
      (`name`,
      `si`,
      `siphone`,
      `endcustomer`,
      `endcustomerphone`,
      `hviagent`,
      `hikagent`,
      `startdate`,
      `description`,
      `status`,
      `value`,
      `requirements`,
      `installer`,
      `installerphone`)
      VALUES
      ('$name',
      '$si',
      '$siphone',
      '$endcustomer',
      '$endcustomerphone',
      '$display_session',
      '$hikagent',
      '$today_date',
      '$description',
      'ממתין לאישור מנהל מכירות',
      '$value',
      '$requirements',
      '$installer',
      '$installerphone')";
		if (mysqli_query($con, $sql)) {

         $subject1 = "$display_session הוסיף פרויקט בשם $name";
         $body1 = "<body style=\"text-align:right; direction:rtl;\">$display_session הוסיף פרויקט בשם $name<b> ";
         notify($display_session,$subject1,$body1,$user_id,$con);

       } else {}
      header("Refresh:0");
   } 
   

   function notify($display_session1,$subject1,$body1,$user_id,$con1) {
         $emails_to_send=array();
         $telegrams_to_send=array();
         $ids = implode(', ', $user_id);
         $result = mysqli_query($con1,"SELECT * FROM db.users WHERE ID IN ($ids)");
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
      $mail->Username   = "********* ";
      $mail->Password   = "*********";
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
                           <h4 class="card-title">פרטי פרויקט</h4>
                           <div class="card-body">
                              <form method="post" id="form1"  onsubmit="loading()">
                                 <div class="container">
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>שם פרויקט</label>
                                             <input name="name" type="text" class="form-control" placeholder="שם פרויקט">
                                          </div>
                                       </div>
                                    </div>

                                    <h4 class="card-title">סוכנים</h4>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>HVI</label>
                                             <select name="hviagent" class="form-control" data-style="select-with-transition">
                                             <?php
                                                if ($role_session == "Agent")
                                                {

                                                   echo 
                                                   "
                                                   <option class=\"p-3 mb-2 bg-danger text-white\">" . $display_session . "</option>
                                                   ";
                                                }
                                                else
                                                {
                                                   $result = mysqli_query($con,"SELECT * FROM db.users where role = 'agent';");
                                                   while($row = mysqli_fetch_array($result))
                                                   {
                                                   echo 
                                                   "
                                                   <option class=\"p-3 mb-2 bg-danger text-white\">" . $row['displayname'] . "</option>
                                                   ";

                                                   }
                                                }




                                                   ?>
                                             </select>
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="form-group">
                                             <label>Hikvision</label>
                                             <select name="hikagent" class="form-control" data-style="select-with-transition" >
                                             <?php
                                                if ($role_session == "Hik")
                                                {

                                                   echo 
                                                   "
                                                   <option class=\"p-3 mb-2 bg-danger text-white\">" . $display_session . "</option>
                                                   ";
                                                }
                                                else
                                                {
                                                   
                                                   echo 
                                                   "
                                                   <option class=\"p-3 mb-2 bg-danger text-white\">אין</option>
                                                   ";
                                                   $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Hik';");
                                                   while($row = mysqli_fetch_array($result))
                                                   {

                                                   echo 
                                                   "
                                                   <option class=\"p-3 mb-2 bg-danger text-white\">" . $row['displayname'] . "</option>
                                                   ";
                                                      
                                                   }
                                                }




                                                   ?>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <h4 class="card-title">אנשי קשר</h4>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>איש קשר / מתקין</label>
                                             <input name="installer" type="text" class="form-control" required placeholder="שם" >
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="form-group">
                                             <label>טלפון</label>
                                             <input name="installerphone" type="text" class="form-control" required placeholder="טלפון">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>מפיץ / אינטגרטור</label>
                                             <input name="si" type="text" class="form-control" placeholder="שם"  <?php if($role_session == "Hik") {}else{echo "required";}  ?>>
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="form-group">
                                             <label>טלפון</label>
                                             <input name="siphone" type="text" class="form-control"  placeholder="טלפון" >
                                          </div>
                                       </div>
                                    </div>
                                    <div class="row">
                                       <div class="col">
                                          <div class="form-group">
                                             <label>לקוח סופי / משתמש המערכת</label>
                                             <input name="endcustomer" type="text" class="form-control" placeholder="שם" >
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="form-group">
                                             <label>טלפון</label>
                                             <input name="endcustomerphone" type="text" class="form-control" placeholder="טלפון" >
                                          </div>
                                       </div>
                                    </div>
                                    <h4 class="card-title">תקציב</h4>
                                    <div class="row">
                                       <div class="col">
                                          <input name="value" type="number" class="form-control" required placeholder="תקציב" >
                                       </div>
                                    </div>
                                    <br>
                                    <h4 class="card-title">תיאור פרויקט</h4>
                                    <div class="row">
                                       <div class="col">
                                          <textarea name="description" rows="5" class="form-control" required placeholder="תיאור"></textarea>
                                       </div>
                                    </div>
                                    <br>
                                    <h4 class="card-title">דרישות פרויקט</h4>
                                    <div class="row">
                                       <div class="col">
                                          <textarea name="requirements" rows="5" class="form-control" required placeholder="דרישות"></textarea>
                                       </div>
                                    </div>
                                    <br>
                                    <button type="button" class="btn btn-primary btn-round" data-toggle="modal" data-target="#submit">שמור פרויקט חדש</button>
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
            <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
         </div>
         <div class="modal-body" style="text-align: right;">
         <div class="row">
            <div class="col">
            <?php
               $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Projects Manager' or role = 'CEO' or role = 'COO' or role = 'Postsale Manager';");
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
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
            <button type="submit" form="form1" name="submit" class="btn btn-primary btn-round">שמור פרויקט</button>

         </div>
      </div>
   </div>
</div>
</div>
<div class="<?php echo $modal_background ?>" id="loading" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
               </div>
               <div class="modal-body" style="text-align: right;">
               <h3 class="modal-title" id="exampleModalLabel"> </h3>
               <h3 class="modal-title" id="exampleModalLabel"> </h3>
               <h3 class="modal-title" id="exampleModalLabel"> </h3>
               <h3  id="exampleModalLabel">אנא המתן.....</h3>
               <h3 id="exampleModalLabel">שולח עדכונים</h3>
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