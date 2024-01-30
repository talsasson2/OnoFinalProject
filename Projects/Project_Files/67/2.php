<?php
 	include('../session/session.php');
    include '../session/connection.php';
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	require '../PHPMailer-master/src/Exception.php';
	require '../PHPMailer-master/src/PHPMailer.php';
	require '../PHPMailer-master/src/SMTP.php';





	$id=$_GET['project_file_id'];
	$result=mysqli_query($con,"select * from projects where ID=$id;");
	$row=mysqli_fetch_assoc($result);
	$name=$row['name'];


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




<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>HVI - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar" >

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center">
    <div class="sidebar-brand-text mx-3">HVI</div>
</a>

<div id="sidebar"></div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#sidebar").load("../SideBar/SideBar.php");});
</script>

</ul>
<!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>



                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $display_session; ?></span>
                                <div class="topbar-divider d-none d-sm-block"></div>
                                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">

                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    התנתק
                                </a>
                            </div>
                        </li>
                        
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                    <div class="card shadow mb-4" dir = "rtl" text align="right">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">העלאת קבצים אל <?php echo $name; ?></h6>
                        </div>
                        <div class="card-body"> </div>

							<!-- Form Name -->
							<legend><center><h2><b><?php echo $name; ?></b></h2></center></legend><br>
			    

						

					</div>
	                    <div class="card shadow mb-4" dir = "rtl" text align="right">
							<form method="POST" enctype="multipart/form-data">
								<div class="upload-wrapper">
									<br> </br>
									<span class="file-name">בחר קובץ</span>
									<label for="file-upload"><input type="file" id="file-upload" name="uploadedFile"></label>
									<input type="submit" name="uploadBtn" value="העלאה" />
								</div>

							</form>       
						</div>

			<div class="card shadow mb-4" dir = "rtl" text align="right">
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
					  
					  
					  
					<a href="..\Projects/edit_project.php?updateid=<?php echo $id; ?>" class="btn btn-light btn-icon-split">
						<span class="icon text-gray-600">
						<i class="fas fa-arrow-right"></i>
						</span>
						<span class="text">חזור אל דף הפרויקט </span>
						</a>
			</div>

				</div>

				</div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Tal Sasson 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">יציאה מהמערכת</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" dir='rtl'>האם אתה בטוח?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">ביטול</button>
                    <a class="btn btn-primary" href="../session/logout.php">התנתק</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../js/hvi-projects.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

</body>

</html>