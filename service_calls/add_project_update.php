<?php
   include('../session/session.php');
   include_once('../session/connection.php');
   $useragent=$_SERVER['HTTP_USER_AGENT'];
   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;
   require '../PHPMailer-master/src/Exception.php';
   require '../PHPMailer-master/src/PHPMailer.php';
   require '../PHPMailer-master/src/SMTP.php';

   $id=$_GET['id'];
   $call_id=$_GET['call_id'];
   

  
  function notify($id1,$display_session1,$subject1,$body1,$con1,$notes) {
    $emails_to_send=array();
    $telegrams_to_send=array();
    $result = mysqli_query($con1,"SELECT * FROM db.users  WHERE role = 'Tech Manager' or role = 'COO'");
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
    sendmail($subject1,$body1,$emails_to_send);
    sendtelegram($subject1,$telegrams_to_send,$notes);
    add_update($id1,$display_session1,$con1,$notes);
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
       $mail->Username   = "hvitmiha@gmail.com";
       $mail->Password   = "Hvi12345!";
       $mail->IsHTML(true);
       $mail->CharSet = 'UTF-8';
       $mail->Encoding = 'base64';
       $mail->AddAddress("tal.s@hviil.co.il", "Tal");
    
       $mail->SetFrom("hvitmiha@gmail.com", "Projects");
       $mail->Subject = $subject1;
       $content = $content1;
       $mail->MsgHTML($content); 
       if(!$mail->Send()) {
         var_dump($mail);
       } else {
       }
 }
 function sendtelegram($subject1,$numbers1,$notes1) {
 
    $apiToken = "5184472528:AAEyx2Y_-T3Hdw9yObExYX7qd1ZjmZXV8bc";
    if ($notes1 == '')
    {
       $text = $subject1;
    }
    else{
       $text = $subject1 .PHP_EOL. 'פרטים נוספים:' . $notes1;
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
 function change_status($status,$id1,$con1, $call_id) {
    $sql = "UPDATE service_calls SET `status` = '$status' WHERE `ID` = $call_id;";
    if (mysqli_query($con1, $sql)) { 
    } else { 
 
    }
 }
 
 function add_update($id,$display_session,$con2,$notes) {
    $sql2 = "UPDATE `db`.`service_calls_updates`
    SET
    `project_done_by` = '$display_session',
    `call_notes` = '$notes'
    WHERE ID = $id";
    if (mysqli_query($con2, $sql2)) {
       return $insert_id = mysqli_insert_id($con2);
 
     } else {
 
      }
 }


	if(isset($_POST['done'])) {
		$notes = htmlspecialchars($_POST['notes']); 
    $status = 'טופל';
    $subject = 'קריאת שירות הסתיימה - טופלה בהצלחה';
    $body = 'קריאת שירות הסתיימה - טופלה בהצלחה';


    change_status($status,$id,$con, $call_id);
    notify($id,$display_session,$subject,$body,$con,$notes);
    header("location: ../service_Calls.php");
		}

    if(isset($_POST['failed'])) {
      $id=$_GET['id'];
      $notes = htmlspecialchars($_POST['notes']); 
      $status = 'לא ניתן לפתור';
      $subject = 'קריאת שירות הסתיימה - לא ניתן לפתור';
      $body = 'קריאת שירות הסתיימה - לא ניתן לפתור';
  
  
      change_status($status,$id,$con, $call_id);
      notify($id,$display_session,$subject,$body,$con,$notes);
      header("location: ../service_Calls.php");
      }

      if(isset($_POST['ongoing'])) {
        $id=$_GET['id'];
        $notes = htmlspecialchars($_POST['notes']); 
        $status = 'בטיפול';
        $subject = 'קריאת שירות הסתיימה - בטיפול';
        $body = 'קריאת שירות הסתיימה - בטיפול';
    
    
        change_status($status,$id,$con, $call_id);
        notify($id,$display_session,$subject,$body,$con,$notes);
        header("location: ../service_Calls.php");
        }

?>
 <html lang="en">

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

<body class=" rtl menu-on-right ">

    <div class="main-panel">

      <div class="content">
        <div class="row">
          <div class="col-12">
            <div class="card card-chart">
              <div class="card-header ">
                <div class="row">
                  <div class="col-sm-6 text-right">
                    <h2 class="card-title">סיכום קריאה</h2>

                    <div class="card">
  <div class="card-body">
  <form method="post" id="form1">
     

  <textarea form="form1" name="notes" rows="5" class="form-control" placeholder="הערות"></textarea>

        <div class="form-row">

        <button type="submit" form="form1" class="btn btn-success btn-icon-split" name="done">
								<span class="icon text-white-50"></span><span class="text">טופל</span>
							</button>

              <button type="submit" form="form1" class="btn btn-success btn-icon-split" name="failed">
								<span class="icon text-white-50"></span><span class="text">אין אפשרות לטפל</span>
							</button>

              <button type="submit" form="form1" class="btn btn-success btn-icon-split" name="ongoing">
								<span class="icon text-white-50"></span><span class="text">יש להמשיך בטיפול</span>
							</button>

            </form>
  </div>
</div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">

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
              <span class="badge filter badge-danget" data-color="red"></span>

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
  
  <!--   Core JS Files   -->
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
  <script src="../assets/js/black-dashboard.min.js?v=1.0.0"></script><!-- Black Dashboard DEMO methods, don't include it in your project! -->
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
      // Javascript method's body can be found in assets/js/demos.js

      

    gradientChartOptionsConfigurationWithTooltipBlue = {
      maintainAspectRatio: false,
      legend: {
        display: false
      },

      tooltips: {
        backgroundColor: '#f5f5f5',
        titleFontColor: '#333',
        bodyFontColor: '#666',
        bodySpacing: 4,
        xPadding: 12,
        mode: "nearest",
        intersect: 0,
        position: "nearest"
      },
      responsive: true,
      scales: {
        yAxes: [{
          barPercentage: 1.6,
          gridLines: {
            drawBorder: false,
            color: 'rgba(29,140,248,0.0)',
            zeroLineColor: "transparent",
          },
          ticks: {
            suggestedMin: 60,
            suggestedMax: 125,
            padding: 20,
            fontColor: "#2380f7"
          }
        }],

        xAxes: [{
          barPercentage: 1.6,
          gridLines: {
            drawBorder: false,
            color: 'rgba(29,140,248,0.1)',
            zeroLineColor: "transparent",
          },
          ticks: {
            padding: 20,
            fontColor: "#2380f7"
          }
        }]
      }
    };

    gradientChartOptionsConfigurationWithTooltipPurple = {
      maintainAspectRatio: false,
      legend: {
        display: false
      },

      tooltips: {
        backgroundColor: '#f5f5f5',
        titleFontColor: '#333',
        bodyFontColor: '#666',
        bodySpacing: 4,
        xPadding: 12,
        mode: "nearest",
        intersect: 0,
        position: "nearest"
      },
      responsive: true,
      scales: {
        yAxes: [{
          barPercentage: 1.6,
          gridLines: {
            drawBorder: false,
            color: 'rgba(29,140,248,0.0)',
            zeroLineColor: "transparent",
          },
          ticks: {
            suggestedMin: 0,
            suggestedMax: 15,
            padding: 20,
            fontColor: "#9a9a9a"
          }
        }],

        xAxes: [{
          barPercentage: 1.6,
          gridLines: {
            drawBorder: false,
            color: 'rgba(225,78,202,0.1)',
            zeroLineColor: "transparent",
          },
          ticks: {
            padding: 20,
            fontColor: "#9a9a9a"
          }
        }]
      }
    };

    gradientChartOptionsConfigurationWithTooltipOrange = {
      maintainAspectRatio: false,
      legend: {
        display: false
      },

      tooltips: {
        backgroundColor: '#f5f5f5',
        titleFontColor: '#333',
        bodyFontColor: '#666',
        bodySpacing: 4,
        xPadding: 12,
        mode: "nearest",
        intersect: 0,
        position: "nearest"
      },
      responsive: true,
      scales: {
        yAxes: [{
          barPercentage: 1.6,
          gridLines: {
            drawBorder: false,
            color: 'rgba(29,140,248,0.0)',
            zeroLineColor: "transparent",
          },
          ticks: {
            suggestedMin: 5,
            suggestedMax: 50,
            padding: 20,
            fontColor: "#ff8a76"
          }
        }],

        xAxes: [{
          barPercentage: 1.6,
          gridLines: {
            drawBorder: false,
            color: 'rgba(220,53,69,0.1)',
            zeroLineColor: "transparent",
          },
          ticks: {
            padding: 20,
            fontColor: "#ff8a76"
          }
        }]
      }
    };

    gradientChartOptionsConfigurationWithTooltipGreen = {
      maintainAspectRatio: false,
      legend: {
        display: false
      },

      tooltips: {
        backgroundColor: '#f5f5f5',
        titleFontColor: '#333',
        bodyFontColor: '#666',
        bodySpacing: 4,
        xPadding: 12,
        mode: "nearest",
        intersect: 0,
        position: "nearest"
      },
      responsive: true,
      scales: {
        yAxes: [{
          barPercentage: 1.6,
          gridLines: {
            drawBorder: false,
            color: 'rgba(29,140,248,0.0)',
            zeroLineColor: "transparent",
          },
          ticks: {
            suggestedMin: 5,
            suggestedMax: 50,
            padding: 20,
            fontColor: "#9e9e9e"
          }
        }],

        xAxes: [{
          barPercentage: 1.6,
          gridLines: {
            drawBorder: false,
            color: 'rgba(0,242,195,0.1)',
            zeroLineColor: "transparent",
          },
          ticks: {
            padding: 20,
            fontColor: "#9e9e9e"
          }
        }]
      }
    };


    gradientBarChartConfiguration = {
      maintainAspectRatio: false,
      legend: {
        display: false
      },

      tooltips: {
        backgroundColor: '#f5f5f5',
        titleFontColor: '#333',
        bodyFontColor: '#666',
        bodySpacing: 4,
        xPadding: 12,
        mode: "nearest",
        intersect: 0,
        position: "nearest"
      },
      responsive: true,
      scales: {
        yAxes: [{

          gridLines: {
            drawBorder: false,
            color: 'rgba(29,140,248,0.1)',
            zeroLineColor: "transparent",
          },
          ticks: {
            suggestedMin: 50000,
            suggestedMax: 1000000,
            padding: 20,
            fontColor: "#9e9e9e"
          }
        }],

        xAxes: [{

          gridLines: {
            drawBorder: false,
            color: 'rgba(29,140,248,0.1)',
            zeroLineColor: "transparent",
          },
          ticks: {
            padding: 20,
            fontColor: "#9e9e9e"
          }
        }]
      }
    };

    var ctx = document.getElementById("chartLinePurple").getContext("2d");

    var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

    gradientStroke.addColorStop(1, 'rgba(72,72,176,0.2)');
    gradientStroke.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke.addColorStop(0, 'rgba(119,52,169,0)'); //purple colors

    var data = {
      labels: <?php echo "['" . implode("','",$labels3) . "']";?>,
      datasets: [{
        label: "ממתינים",
        fill: true,
        backgroundColor: gradientStroke,
        borderColor: '#d048b6',
        borderWidth: 2,
        borderDash: [],
        borderDashOffset: 0.0,
        pointBackgroundColor: '#d048b6',
        pointBorderColor: 'rgba(255,255,255,0)',
        pointHoverBackgroundColor: '#d048b6',
        pointBorderWidth: 20,
        pointHoverRadius: 4,
        pointHoverBorderWidth: 15,
        pointRadius: 4,
        data: <?php echo "[" . implode(",",$count3) . "]";?>,
      }]
    };

    var myChart = new Chart(ctx, {
      type: 'line',
      data: data,
      options: gradientChartOptionsConfigurationWithTooltipPurple
    });


    var ctxGreen = document.getElementById("chartLineGreen").getContext("2d");

    var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

    gradientStroke.addColorStop(1, 'rgba(66,134,121,0.15)');
    gradientStroke.addColorStop(0.4, 'rgba(66,134,121,0.0)'); //green colors
    gradientStroke.addColorStop(0, 'rgba(66,134,121,0)'); //green colors

    var data = {
      labels: <?php echo "['" . implode("','",$labels1) . "']";?>,
      datasets: [{
        label: "עדכוני מערכת",
        fill: true,
        backgroundColor: gradientStroke,
        borderColor: '#00d6b4',
        borderWidth: 2,
        borderDash: [],
        borderDashOffset: 0.0,
        pointBackgroundColor: '#00d6b4',
        pointBorderColor: 'rgba(255,255,255,0)',
        pointHoverBackgroundColor: '#00d6b4',
        pointBorderWidth: 20,
        pointHoverRadius: 4,
        pointHoverBorderWidth: 15,
        pointRadius: 4,
        data:<?php echo "[" . implode(",",$count1) . "]";?>,
      }]
    };

    var myChart = new Chart(ctxGreen, {
      type: 'line',
      data: data,
      options: gradientChartOptionsConfigurationWithTooltipGreen

    });



    var chart_labels = ['דצמבר', 'נובמבר', 'אוקטובר', 'ספטמבר', 'אוגוסט', 'יולי', 'יוני', 'מאי', 'אפריל', 'מרץ', 'פברואר', 'ינואר'];
    var chart_data = [2, 4, 3, 1, 2, 3, 5, 3, 1, 2, 3, 5];


    var ctx = document.getElementById("chartBig1").getContext('2d');

    var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

    gradientStroke.addColorStop(1, 'rgba(72,72,176,0.1)');
    gradientStroke.addColorStop(0.4, 'rgba(72,72,176,0.0)');
    gradientStroke.addColorStop(0, 'rgba(119,52,169,0)'); //purple colors
    var config = {
      type: 'line',
      data: {
        labels: chart_labels,
        datasets: [{
          label: "נסגרו בחודש זה",
          fill: true,
          backgroundColor: gradientStroke,
          borderColor: '#d346b1',
          borderWidth: 2,
          borderDash: [],
          borderDashOffset: 0.0,
          pointBackgroundColor: '#d346b1',
          pointBorderColor: 'rgba(255,255,255,0)',
          pointHoverBackgroundColor: '#d346b1',
          pointBorderWidth: 20,
          pointHoverRadius: 4,
          pointHoverBorderWidth: 15,
          pointRadius: 4,
          data: chart_data,
        }]
      },
      options: gradientChartOptionsConfigurationWithTooltipPurple
    };
    var myChartData = new Chart(ctx, config);
    $("#0").click(function() {
      var data = myChartData.config.data;
      data.datasets[0].data = chart_data;
      data.labels = chart_labels;
      myChartData.update();
    });
    $("#1").click(function() {
      var chart_data = [80, 120, 105, 110, 95, 105, 90, 100, 80, 95, 70, 120];
      var data = myChartData.config.data;
      data.datasets[0].data = chart_data;
      data.labels = chart_labels;
      myChartData.update();
    });

    $("#2").click(function() {
      var chart_data = [60, 80, 65, 130, 80, 105, 90, 130, 70, 115, 60, 130];
      var data = myChartData.config.data;
      data.datasets[0].data = chart_data;
      data.labels = chart_labels;
      myChartData.update();
    });


    var ctx = document.getElementById("CountryChart").getContext("2d");

    var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

    gradientStroke.addColorStop(1, 'rgba(29,140,248,0.2)');
    gradientStroke.addColorStop(0.4, 'rgba(29,140,248,0.0)');
    gradientStroke.addColorStop(0, 'rgba(29,140,248,0)'); //blue colors


    var myChart = new Chart(ctx, {
      type: 'bar',
      responsive: true,
      legend: {
        display: false
      },
      data: {
        labels: <?php echo "['" . implode("','",$labels2) . "']";?>,
        datasets: [{
          label: "Countries",
          fill: true,
          backgroundColor: gradientStroke,
          hoverBackgroundColor: gradientStroke,
          borderColor: '#1f8ef1',
          borderWidth: 2,
          borderDash: [],
          borderDashOffset: 0.0,
          data: <?php echo "[" . implode(",",$count2) . "]";?>,
        }]
      },
      options: gradientBarChartConfiguration
    });


    });
  </script>

</body>

</html>