<?php
 	include('../session/session.php');
    include '../session/connection.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require '../PHPMailer-master/src/Exception.php';
	require '../PHPMailer-master/src/PHPMailer.php';
	require '../PHPMailer-master/src/SMTP.php';





	$id=$_GET['project_file_id'];

	if (is_dir("Project_Files/$id/"))
	{
	  $uploadFileDir = "Project_Files/$id/";
	}
	else{
	mkdir("Project_Files/$id/", 0770, true);
	  $uploadFileDir = "Project_Files/$id/";
	}

	$dir    = "Project_Files/$id";
	$files = array_slice(scandir($dir), 2);

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'העלאה')
			{
			  if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK)
			  {
				// get details of the uploaded file
				$fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
				$fileName = $_FILES['uploadedFile']['name'];
				$fileSize = $_FILES['uploadedFile']['size'];
				$fileType = $_FILES['uploadedFile']['type'];
				$fileNameCmps = explode(".", $fileName);
				$fileExtension = strtolower(end($fileNameCmps));

				// sanitize file-name
				$newFileName = $fileName;

				// check if file has one of the following extensions
				$allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc','pdf','jpeg','xls','xlsx');

				if (in_array($fileExtension, $allowedfileExtensions))
				{
				  // directory in which the uploaded file will be moved
					if (is_dir("Project_Files/$id/"))
					{
					  $uploadFileDir = "Project_Files/$id/";
					}
					else{
					mkdir("Project_Files/$id/", 0770, true);
					  $uploadFileDir = "Project_Files/$id/";
					}
				  $dest_path = $uploadFileDir . $newFileName;

				  if(move_uploaded_file($fileTmpPath, $dest_path)) 
				  {
					  $sql = "INSERT INTO projects_updates (`projects_id`,`project_update`,`project_date`,`project_update_by`)VALUES('$id','העלאה קובץ','" . date("Y-m-d") . "','$display_session');";
						if (mysqli_query($con, $sql)) {
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
						$mail->AddAddress("arye@hviil.co.il", "Arye");
						$mail->SetFrom("hvitmiha@gmail.com", "Projects");
						$mail->Subject = "$display_session הוסיף קובץ בשם $newFileName לפרויקט $name";
						$content = "<b></b>";
						$mail->MsgHTML($content); 
						if(!$mail->Send()) {
						  var_dump($mail);
						} else {
						}	
						}
				  }
				  else
				  {
					echo 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
				  }
				}
				else
				{
				  echo 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
				}
			  }
			  else
			  {
				echo 'There is some error in the file upload. Please check the following error.<br>';
				echo 'Error:' . $_FILES['uploadedFile']['error'];
			  }
			}
			header("Refresh:0");

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
                     <form method="POST" enctype="multipart/form-data">
								<div class="upload-wrapper">
									<br> </br>
									<span class="file-name">בחר קובץ</span>
									<label for="file-upload"><input type="file" id="file-upload" name="uploadedFile"></label>
									<input type="submit" name="uploadBtn" value="העלאה" />
								</div>

							</form>       
                     </div>
                  </div>
                  <div class="col">
                     <div class="card ">
                     <table class="table table-hover">
                           <thead>
                           <tr>
                              <th>קבצים</th>
                           </tr>
                           </thead>
                           <tbody>
                           <?php $i =0 ;foreach($files as $file) {?>
                           <tr>
                              <td><a href="Project_Files\<?php echo $id;?>/<?php echo $file;?>"><?php echo $file;?></a>
                              </td>
                           </tr>
                        <?php }?>

                        </tbody>
                        </tbody>
                           </tbody>
                        </table>

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
<div class="modal modal-black fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-body" style="text-align: right;">
         <div class="row">
            <div class="col">
            <div class="modal-header">
            <h4 class="modal-title">למי לשלוח?</h4>
            </div>
            <br>

            <?php
               $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
            <div class="modal-header">
            <h4 class="modal-title">יש לך משהו להוסיף?</h4>
            </div>
            <br>

            <textarea form="form1" name="notes" rows="5" class="form-control" placeholder="הערות"></textarea>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
            <button type="submit" form="form1" name="delete" onclick="loading()" class="btn btn-primary btn-round">מחק פרויקט</button>
         </div>
      </div>
   </div>
</div>
</div>

<div class="modal modal-black fade" id="approve_ceo_to_presale" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
         </div>
         <div class="modal-body" style="text-align: right;">
         <div class="row">
            <div class="col">
            <?php
               $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
            <textarea form="form1" name="approve_ceo_to_presale_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
            </div>

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
            <button type="submit" form="form1" name="approve_ceo_to_presale" onclick="loading()" class="btn btn-primary btn-round">אשר פרויקט</button>

         </div>
      </div>
   </div>
</div>
</div>

<div class="modal modal-black fade" id="approve_ceo_to_postsale" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="approve_ceo_to_postsale_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="approve_ceo_to_postsale" onclick="loading()" class="btn btn-primary btn-round">אשר פרויקט</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="decline_ceo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="decline_ceo_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="decline_ceo" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="approve_projectmanager" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="approve_projectmanager_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="approve_projectmanager" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="decline_projectmanager" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="decline_projectmanager_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="decline_projectmanager" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="approve_meet_done" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="approve_meet_done_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="approve_meet_done" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="decline_meet_done" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="decline_meet_done_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="decline_meet_done" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="license_done" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="license_done_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="license_done" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="equipment_on_site" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="equipment_on_site_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="equipment_on_site" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="ready_for_install" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="ready_for_install_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="ready_for_install" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="install_set" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="install_set_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="install_set" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="install_done" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="install_done_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="install_done" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="tech" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="tech_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="tech" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="pilot_done_approve" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="pilot_done_approve_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="pilot_done_approve" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="pilot_done_reject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="pilot_done_reject_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="pilot_done_reject" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="order_done" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
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
                  <textarea form="form1" name="order_done_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="order_done" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="support" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
                     while($row = mysqli_fetch_array($result))
                     {
                                      echo 
                                      "<div class=\"form-check\" style=\"dir: rtl;\">
                                            <label class=\"form-check-label\">
                                            
                                               <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\" value=" . $row['ID'] . " id=" . $row['displayname'] . " >
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
                  <textarea form="form1" name="support_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="support" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="ask_servicecall" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Postsale Manager';");
                     while($row = mysqli_fetch_array($result))
                     {
                                      echo 
                                      "<div class=\"form-check\" style=\"dir: rtl;\">
                                            <label class=\"form-check-label\">
                                            
                                               <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\" value=" . $row['ID'] . " id=" . $row['displayname'] . " >
                                               " . $row['displayname'] . "
                                               <span class=\"form-check-sign\">
                                                  <span class=\"check\" ></span>
                                               </span>
                                            </label>
                                      </div>";
                                      }
                     ?>
                  </div>
                  <div class="col">
                  <textarea form="form1" name="ask_servicecall_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="ask_servicecall" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="ask_buildsite" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Postsale Manager';");
                     while($row = mysqli_fetch_array($result))
                     {
                                      echo 
                                      "<div class=\"form-check\" style=\"dir: rtl;\">
                                            <label class=\"form-check-label\">
                                            
                                               <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\" value=" . $row['ID'] . " id=" . $row['displayname'] . " >
                                               " . $row['displayname'] . "
                                               <span class=\"form-check-sign\">
                                                  <span class=\"check\" ></span>
                                               </span>
                                            </label>
                                      </div>";
                                      }
                     ?>
                  </div>
                  <div class="col">
                  <textarea form="form1" name="ask_buildsite_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="ask_buildsite" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="ask_tech_call" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Postsale Manager';");
                     while($row = mysqli_fetch_array($result))
                     {
                                      echo 
                                      "<div class=\"form-check\" style=\"dir: rtl;\">
                                            <label class=\"form-check-label\">
                                            
                                               <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\" value=" . $row['ID'] . " id=" . $row['displayname'] . " >
                                               " . $row['displayname'] . "
                                               <span class=\"form-check-sign\">
                                                  <span class=\"check\" ></span>
                                               </span>
                                            </label>
                                      </div>";
                                      }
                     ?>
                  </div>
                  <div class="col">
                  <textarea form="form1" name="ask_tech_call_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="support" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="new_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">האם אתה בטוח?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
                     while($row = mysqli_fetch_array($result))
                     {
                                      echo 
                                      "<div class=\"form-check\" style=\"dir: rtl;\">
                                            <label class=\"form-check-label\">
                                            
                                               <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\" value=" . $row['ID'] . " id=" . $row['displayname'] . " >
                                               " . $row['displayname'] . "
                                               <span class=\"form-check-sign\">
                                                  <span class=\"check\" ></span>
                                               </span>
                                            </label>
                                      </div>";
                                      }
                     ?>
                  </div>
                  <div class="col">
                  <textarea form="form1" name="new_update_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="new_update" onclick="loading()" class="btn btn-primary btn-round">אשר</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="ask_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h3 class="modal-title" id="exampleModalLabel">ממי נדרש עדכון?</h3>
               </div>
               <div class="modal-body" style="text-align: right;">
               <div class="row">
                  <div class="col">
                  <?php
                     $result = mysqli_query($con,"SELECT * FROM db.users where role = 'Admin' or role = 'CEO' or role = 'Manager' or role = 'Presale Manager'  or role = 'Postsale Manager' or role = 'PreSale' or displayname = '$hikagent' or displayname = '$hviagent';");
                     while($row = mysqli_fetch_array($result))
                     {
                                      echo 
                                      "<div class=\"form-check\" style=\"dir: rtl;\">
                                            <label class=\"form-check-label\">
                                            
                                               <input class=\"form-check-input\" form=\"form1\" name=\"emails[]\" type=\"checkbox\" value=" . $row['ID'] . " id=" . $row['displayname'] . ">
                                               " . $row['displayname'] . "
                                               <span class=\"form-check-sign\">
                                                  <span class=\"check\" ></span>
                                               </span>
                                            </label>
                                      </div>";
                                      }
                     ?>
                  </div>
                  <div class="col">
                  <textarea form="form1" name="ask_update_notes" rows="5" class="form-control" placeholder="הערות"></textarea>
                  </div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">סגור</button>
                  <button type="submit" form="form1" name="ask_update" onclick="loading()" class="btn btn-primary btn-round">שלח</button>

               </div>
            </div>
         </div>
      </div>
</div>

<div class="modal modal-black fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
             });
         
             $('.dark-badge').click(function() {
               $('body').removeClass('white-content');
             });
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