<?php
 include('../session/session.php');
 ?>

<li>
<a href="../index.php">
	<i class="tim-icons icon-chart-bar-32"></i>
	<p>מסך הבית</p>
</a>
</li>
<li>
<a href="../projects.php">
	<i class="tim-icons icon-molecule-40"></i>
	<p>פרויקטים</p>
</a>
</li>
<?php
 if($role_session == "Admin")
 {
 echo "<li>
 <a href=\"../users.php\">
	 <i class=\"tim-icons icon-single-02\"></i>
	 <p>משתמשים</p>
 </a>
 </li>";
 }
 else
 {
 }

 if($role_session == "Admin" || $role_session == "Tech Manager" || $role_session == "Tech"|| $role_session == "Projects Manager" || $role_session == "CEO" || $role_session == "COO" || $role_session == "Agent")
 {
 echo "<li>
 <a href=\"../service_calls.php\">
	 <i class=\"tim-icons icon-headphones\"></i>
	 <p>קריאות שירות</p>
 </a>
 </li>";
 }
 else
 {
 }
 ?>
