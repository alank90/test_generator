<!DOCTYPE html>
<html lang="en">
<head></head>

<?php 
//ini_set('display_errors',1); 
//error_reporting(E_ALL);
   // ---------------------------------------------------------- //
  // Grabs the $_POST Global array and put into $allquestions &  //
  // $file (Note; testquestions is in JSON format when sent) --  //
  // These will be written to file on server ------------------- //
  // ----------------------------------------------------------- //
 
    if(isset($_POST)) {
  	  	$fileName = "tests/" . $_POST["fileName"] . ".json";
	    $allQuestions = $_POST["testquestions"];
		//$allQuestions = file_get_contents('php://input'); php://input is a read-only stream that allows 
		// you to read raw data from the request body. Didn't use here but good to know.
  	  
  } else {
     // $_POST array not set, throw an error
      echo "Houston, We Have a Problem!";
  }
  
    // serialize — Generates a storable representation of a value. Before this allQuestions is an object, and can not 
    // be stored on the server.
    serialize($allQuestions);
	    
    // Saves the json string in $file into .json file
    // LOCK_EX flag to prevent anyone else writing to the file at the same time
    // outputs error message if data cannot be saved
    
    if (file_exists($fileName)) {
      echo  'Exam file already exists. Resubmit the Exam with another name.';	
    
	 } elseif (file_put_contents($fileName, $allQuestions, LOCK_EX)) {
		 echo 'Exam successfully saved.';
     } else  { echo 'Warning. Unable to save data in. See the System Administrator.' . $file;
	 }
 ?>