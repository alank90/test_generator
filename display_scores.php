<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
   <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    
   <!-- Latest compiled and minified BootStrap JavaScript -->
	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
	 
   <!-- Bootstrap CSS CDN ------>
     <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">	 
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1">
     
   <!-- SortTable JS -->   
     <script src="js/sorttable.js"></script>
   
   <!-- Main CSS file -->
     <link rel="stylesheet" type="text/css" href="main.css">
     
    <title>SHS App Test Scores</title>
  </head>
  
   <body>
 
<div class="container-fluid">
  <ul class="nav nav-tabs nav-justified">
    <li><a href="testgen.html">Goto Exam Generator</a></li>
    <li class="active"><a href="#">Student Scores</a></li>
    <li><a href="retrieve_test.html">Retrieve an Exam</a></li>
  </ul>
</div>
   	
    <h2>SHS App Student Test Scores&nbsp; <span class="glyphicon glyphicon-education" aria-hidden="true"></span></h2>	
    
<!-------------==================-->
<!-- Start PHP ==================-->
<!-------------==================-->    
 
<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);   
require "PDO_Pagination.php"; //Does the heavy lifting for pagination
include ("dbinfo.inc.php"); //include login info file

// Start PDO Session w/MySQL
try
    {
      $conn = new PDO($dsn, $username, $password);
      $pagination = new PDO_Pagination($conn);

    }
catch (PDOException $e)
    {
    $error_message=$e->getMessage();
    echo "<h1>Resource Unavailable. Please contact the System Administrator</h1>";
    }
//End of Connection Setup

// Search function if Search field has a value in it.
$search = null;
if(isset($_REQUEST["search"]) && $_REQUEST["search"] != "") {
  $search = htmlspecialchars($_REQUEST["search"]);
  $pagination->param = "&search=$search";
  $pagination->rowCount("SELECT * FROM exam_data WHERE lname LIKE '%$search%' OR test_name LIKE '%$search%' OR teacher_name LIKE '%$search%'");
  $pagination->config(3, 15);
  $sql = "SELECT * FROM exam_data WHERE lname LIKE '%$search%' OR  test_name LIKE '%$search%' OR teacher_name LIKE '%$search%' ORDER BY id ASC LIMIT $pagination->start_row, $pagination->max_rows";
  $query = $conn->prepare($sql);
  $query->execute();
  $student_scores = array();
  while($rows = $query->fetch()) {
    $student_scores[] = $rows;
  }
} // end If
// Setup initial MySQL query for page here. The 40 is how may items per page. //
// Search field is blank. --------------------------------------------------- //
else {
  $pagination->rowCount("SELECT * FROM exam_data ");
  $pagination->config(3, 40);
  $sql = "SELECT * FROM exam_data ORDER BY test_name ASC LIMIT $pagination->start_row, $pagination->max_rows";
  $query = $conn->prepare($sql);
  $query->execute();
  $student_scores = array();
  while($rows = $query->fetch())   {
    $student_scores[] = $rows;
  }
}  // end else 
// End Search Function
?>

<form id="frm_style" method="post" action="<?php echo $_SERVER["PHP_SELF"] ?>">
<strong style="font-size:22px">Find:</strong> 
<input type="text" name="search" placeholder="LName/Test Name/Teacher" value="<?php echo $search ?>" class="input_style"/>
<input type="submit" class="def_search" value="Search"/>
</form>
<br/><br/>
    <center>
<!----------------------------------------------------------------- >               
<!-- Styling for table in the CSS file ----------------------------->
<!-- Student Scores Table Markup. Table Body (td) elements --------->
<!-- generated via php. -------------------------------------------->
<!------------------------------------------------------------------>
<table class="table sortable" border="3">
    <tr> 
        <th class="sorttable_nosort">First Name</th>
        <th>Last Name</th>
        <th>Test Name</th>
        <th>Teacher Name</th>
        <th class="sorttable_nosort">Student Score</th>
    </tr>
    
 <?php

    foreach($student_scores as $row)
    {
        echo "<tr class=first>";
        echo '<td>' . $row['fname'] .'</td>';
        echo '<td>' . $row['lname'] .'</td>';
        echo '<td>' . $row['test_name'] .'</td>';
        echo '<td>' . $row['teacher_name'] .'</td>';
        echo "<td>" . $row['score'] .  "&nbsp&nbsp&nbsp&nbsp<a  class= 'glyphicon glyphicon-pencil' title='Click Here to View Student Exam' href='show_test.php?id=" . $row['id'] . "'></a></td>";
    }
?>

</table>
<!--------------------------------------->
<!-- End Student Scores Table Markup. --->
<!--------------------------------------->

 <div id="pagination_pos">
  <?php // Initialize Pagination Plugin
    $pagination->pages("btn_pgn");
  ?>
</div>
  </body>
</html>
