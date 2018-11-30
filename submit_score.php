<!DOCTYPE html>
<html lang="en">

<?php 
//ini_set('display_errors',1); 
//error_reporting(E_ALL);

// -------------------------------------------------------------  //
// Grab the $_POST array values, put them in php variables ------ //
// and start a PDO session. Then INSERT the php variables into -- //
// the MySQL table via a prepare & execute() -------------------- // 
// -------------------------------------------------------------- //
include 'dbinfo.inc.php';

    if(isset($_POST)) {
  	  $fname = $_POST['fname'];
	  $lname = $_POST['lname'];
	  $test_name = $_POST['test_name'];
	  $teacher_name = $_POST['teacher_name'];
	  $score = $_POST['score'];
	  $student_answers = $_POST['student_answers'];
    } else {
     // $_POST array not set, throw an error
      echo "Houston, We Have a Problem!";
  }
 
//Clean input to make sure it is formatted with a leading Capital letter.
$fname = ucfirst($fname);
$lname = ucfirst($lname);
	// Start a PDO Session with MySQL for all the great methods that then provides us with
try
    {
    $conn = new PDO($dsn, $username, $password);
    }
catch (PDOException $e)
    {
    $error_message=$e->getMessage();
    echo "<h1>Resource Unavailable. Please Contact the System Administrator</h1>";
    }
  // Prepare a MySQL statement for PDO execute() method
if ($lname != '' && $test_name != '') {
	$stmt = $conn->prepare('INSERT INTO  exam_data SET fname = :fname, lname = :lname, test_name = :test_name, teacher_name = :teacher_name, score = :score, student_answers = :student_answers');
	
 // PDOStatement::execute — Executes a prepared statement     
	if ( $stmt->execute(array(
	    ':fname' => $fname,
	    ':lname' => $lname,
	    ':test_name' => $test_name,
	    ':teacher_name' => $teacher_name,
	    ':score' => $score,
	    ':student_answers' => $student_answers
 	 ))) {
	     echo "Added Test Score Succesfully";
	    }
}	
else {
  echo 'Unable to add your test score. Check with System Administrator.';
}
?>
</html>