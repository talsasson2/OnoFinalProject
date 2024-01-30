<?php 
 include('session/session.php');
 include_once('session/connection.php');
 $useragent=$_SERVER['HTTP_USER_AGENT'];
 


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
            <div class="card ">
              <div class="card-header text-right">
                
                <h4 class="card-title">קריאות שירות</h4>
              </div>
              <div class="row">
                <div class="col">
                    <div class="card-header text-right">
                      <div class="input-group mb-4">
                        <form action="" method="GET">
                          <div class="input-group mb-3">
                             קריאת שירות חדשה
                          </div>
                          <a class="btn btn-primary btn-simple" href="..\service_calls/new_call.php"> קריאה חדשה </a>
                        </form>
                      </div>
                </div>
                </div>

                <div class="col">
                    <div class="card-header text-right">
                      <div class="input-group mb-4">
                        <form action="" method="GET">
                          <div class="input-group mb-3">
                             סינון והצגת קריאות שנסגרו
                          </div>

                          <div class="row">
                            <div class="col">
                              <form action="" method="GET">
                              <input type="text" name="filter"  hidden required value="ממתינים" class="form-control" placeholder="Search data">
                              <button type="submit" class="btn btn-primary btn-simple">ממתינים לי</button>
                              </form>

                              <form action="" method="GET">
                              <input type="text" name="filter"  hidden required value="לא פעילים" class="form-control" placeholder="Search data">
                              <button type="submit" class="btn btn-primary btn-simple">לא פעילים</button>
                              </form>
                            </div>
                          </div>

                      </div>
                      </div>
                   
                </div>

                
              </div>
              <div class="card-body">
                <div class="table-responsive">
                <table id="myTable" class="table">
                <?php
                    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
                    {
                      echo "<thead>";
                      echo "<tr><th>שם הלקוח</th><th>פעולות</th></tr>";
                      echo "	</thead>";
                    }
                    else
                    {
                      echo "<thead>";
                      echo "<tr><th>שם הלקוח</th><th>פותח הקריאה</th><th>סטאטוס</th><th>סוג</th><th>פעולות</th></tr>";
                      echo "	</thead>";
                    }

                    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
                    {
                      if(isset($_GET['filter']))
                      {
                        $filtervalues = $_GET['filter'];
                        if ($filtervalues == "לא פעילים")
                        {
                          $result = mysqli_query($con,"SELECT * FROM db.service_calls where status = 'טופל' or status = 'לא ניתן לפתור' ORDER BY status");
                          while($row = mysqli_fetch_array($result))
                          {
                          $id = $row['ID'];
                          echo "<tbody>";											
                          echo "<tr>";
                          echo "<td>" . $row['name'] . "</td>";
                          echo "<td><a href=\"..\service_calls/edit_call.php?updateid=" .$id. "\"><p class=\"text-danger\"><i class=\"tim-icons icon-settings\"></p></i></i></a></td>";
                          echo "</tr>";
                          echo "</tbody>";											

                          }
                          echo "</table>";

                        }
                        if ($filtervalues == "ממתינים")
                        {

                          $result = mysqli_query($con,"SELECT * FROM db.service_calls where responsibility = '$role_session' ORDER BY status");
                          while($row = mysqli_fetch_array($result))
                          {
                          $id = $row['ID'];
                          echo "<tbody>";											
                          echo "<tr>";
                          echo "<td>" . $row['name'] . "</td>";
                      
                          echo "<td><a href=\"..\service_calls/edit_call.php?updateid=" .$id. "\"><p class=\"text-danger\"><i class=\"tim-icons icon-settings\"></p></i></i></a></td>";
                          echo "</tr>";
                          echo "</tbody>";											

                          }
                          echo "</table>";

                        }
                          
                        mysqli_close($con);
                      }

                      else
                      {
                          $result = mysqli_query($con,"SELECT * FROM db.service_calls where status <>'טופל' and status <> 'לא ניתן לפתור'  ORDER BY status");
                      
                        while($row = mysqli_fetch_array($result))
                        {
                        $id = $row['ID'];
                        echo "<tbody>";											
                        echo "<tr>";
                        echo "<td>" . $row['installer'] . "</td>";

                        echo "<td><a href=\"..\service_calls/edit_call.php?updateid=" .$id. "\"><p class=\"text-danger\"><i class=\"tim-icons icon-settings\"></p></i></i></a></td>";
                        echo "</tr>";
                        echo "</tbody>";											

                        }
                        echo "</table>";

                        mysqli_close($con);
                      }
                    }
                    else
                    {
                      if(isset($_GET['filter']))
                      {
                        $filtervalues = $_GET['filter'];
                        if ($filtervalues == "לא פעילים"){

                            $result = mysqli_query($con,"SELECT * FROM db.service_calls where status = 'טופל' or status = 'לא ניתן לפתור' ORDER BY status");

                          while($row = mysqli_fetch_array($result))
                          {
                          $id = $row['ID'];
                          echo "<tbody>";											
                          echo "<tr>";
                          echo "<td>" . $row['name'] . "</td>";
                          echo "<td>" . $row['open_by'] . "</td>";
                          echo "<td>" . $row['status'] . "</td>";		
                          echo "<td>" . $row['type'] . "</td>";		


                      
                          echo "<td><a href=\"..\service_calls/edit_call.php?updateid=" .$id. "\"><p class=\"text-danger\"><i class=\"tim-icons icon-settings\"></p></i></i></a></td>";
                          echo "</tr>";
                          echo "</tbody>";											

                          }
                          echo "</table>";

                        }
                        if ($filtervalues == "ממתינים"){

                            $result = mysqli_query($con,"SELECT * FROM db.service_calls where responsibility = '$role_session' ORDER BY status");
                          

                          while($row = mysqli_fetch_array($result))
                          {
                          $id = $row['ID'];
                          echo "<tbody>";											
                          echo "<tr>";
                          echo "<td>" . $row['name'] . "</td>";
                          echo "<td>" . $row['open_by'] . "</td>";
                          echo "<td>" . $row['status'] . "</td>";	
                          echo "<td>" . $row['type'] . "</td>";		


                      
                          echo "<td><a href=\"..\service_calls/edit_call.php?updateid=" .$id. "\"><p class=\"text-danger\"><i class=\"tim-icons icon-settings\"></p></i></i></a></td>";
                          echo "</tr>";
                          echo "</tbody>";											

                          }
                          echo "</table>";

                        }
                          
                        mysqli_close($con);
                      }
                      else
                      {
                        


                          $result = mysqli_query($con,"SELECT * FROM db.service_calls where status <> 'טופל' and status <> 'לא ניתן לפתור' ORDER BY status");


                        while($row = mysqli_fetch_array($result))
                        {
                        $id = $row['ID'];
                        echo "<tbody>";											
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['open_by'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";		
                        echo "<td>" . $row['type'] . "</td>";		



                        echo "<td><a href=\"..\service_calls/edit_call.php?updateid=" .$id. "\"><p class=\"text-danger\"><i class=\"tim-icons icon-settings\"></p></i></i></a></td>";
                        echo "</tr>";
                        echo "</tbody>";											

                        }
                        echo "</table>";

                        mysqli_close($con);
                      }
                    }



                ?>
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
  <!--   Core JS Files   -->
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

      demo.initDashboardPageCharts();

    });
  </script>

</body>

</html>