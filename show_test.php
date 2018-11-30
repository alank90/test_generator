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
     
    <title>Student Test Score</title>
    
     <!---------------------->
   	 <!-- Start PHP --------->
   	 <!---------------------->
   	 
<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);   
include ("dbinfo.inc.php"); //include login info file

// Start PDO Session w/MySQL
try
    {
      $conn = new PDO($dsn, $username, $password);
      
    }
catch (PDOException $e)
    {
    $error_message=$e->getMessage();
    echo "<h1>Resource Unavailable. Please contact the System Administrator</h1>";
    }
//End of Connection Setup

$id = $_REQUEST['id']; // id passed in URL via GET method
//echo $id;
$query="SELECT * FROM exam_data WHERE id='$id'";
$result=$conn->query($query);

while ($row = $result->fetch()) { 
    
    $fname=$row['fname'];
    $lname=$row['lname'];
    $teacher_name = $row['teacher_name'];
    $score = $row['score'];
    $test_name = $row['test_name'];
    $student_answers = $row['student_answers'];
     
  }

$student_answers = explode(",",$student_answers);

  // ---------- //
  // End PHP -- //
  // ---------- //
?> 


  </head>
  
 <body>
 	<!----------------------------------> 
 	<!---------- Button --------------- >
 	<!---------------------------------->
<div class="container-fluid">
  <button type="button" id="button_display_scores" class="btn btn-info btn-lg dd_menu" onclick="location.href='display_scores.php'">Back to Student Scores <br>Page</button>
 <div class="page-header">
  <h2 id="title"></h2>
 </div>
  
</div>
 		
      <!-------------------------> 	
 	  <!-- Exam  Markup --------->
 	  <!------------------------->
   <div class="cke_question"></div>
 	  
  <div id="container" class="choice_answers">
  	<div id="question_div"></div>
      <form id="choices_return"></form>
  </div>
  
  <script>
        //  --------------------------------------- // 
     	// ----------- Begin JS for Page ---------- //
     	// ---------------------------------------- //
     	
   $(document).ready(function() {
   	    // Convert php variables to JS variables -- //
   	    // ---------------------------------------- //
   	    requestedTest = <?php echo json_encode($test_name); ?>;
   	    student_answers = <?php echo json_encode($student_answers); ?>;
   	    fname = <?php echo json_encode($fname); ?>;
   	    lname = <?php echo json_encode($lname); ?>;
   	    teacher_name = <?php echo json_encode($teacher_name); ?>;
   	    test_name = <?php echo json_encode($test_name); ?>;
   	    score = <?php echo json_encode($score); ?>;
   	    // --- End conversions -------------------- //
   	    
   	    $("#title").html(fname + " " + lname +  "&nbsp<span class='glyphicon glyphicon-user'></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspExam:" + test_name  + "&nbsp&nbsp Exam Score:  " + score + "%");
   	    
   	  // AJAX Request
     	 	  $.ajax({
                url: "tests/" + requestedTest + ".json",
                cache: false,
                dataType: "json",
                success: function(data,status) { // Grab JSON file off server and print it out
                  var testReturn = data;
                  var choice_results = "";
                  var row_num = 0;   
                  // ---------------------------------------------- //       
                  //------------ Print Out Student Answers  ------- //
                  // ---------------------------------------------- //
                    for (var i = 0; i < testReturn[0].question.length ; i++) {
                      row_num = i / 3;
                      row_num = Math.floor(row_num); // Round down row number so get three student answers on one row via Bootstrap code  
                  	  if ( i == 0 || (i % 3 == 0)) {
                  	    $(".cke_question").append("<div class='row row_count" + row_num + "'></div>");
                  	  } 
                  	 
                  	  // Print Question
                  	  $(".row.row_count" + row_num).append("<div class='col-md-4'><p class='question_return" + i + "'>" + "<strong class='answer_number'>" + (i + 1) + ")" + "&nbsp" + "</strong></p>");
                  	  // Print Radio Buttons
                  	  for (var j = 0; j < testReturn[0].choices[i].length; j++) {
                      	var chr = String.fromCharCode(97 + j); // where j is 0, 1, 2 ...
                        choice_results += "<br />&nbsp<em>" + chr + ")</em>&nbsp<input id='answers" + i + j +"' type='radio' disabled><span class='choice_text'>&nbsp" + testReturn[0].choices[i][j] + "</span>";
                      }
                      
                      $(".question_return" + i).append(choice_results); // Append Radio button choices to the DOM
                      $("#answers" + i + student_answers[i]).prop("checked",true); // Mark Student radio button answer 
                      if (student_answers[i] != testReturn[0].correctAnswer[i]) {
                      	$( "#answers" + i + student_answers[i] ).next().addClass( "wrong_answer" ); // Mark wrong answer
                      }
                      choice_results = ""; // Clear Choices before rendering next question.
                  } 
                  
                  // ----------------------------------------- //
                  // ------ End Print Out Student Answers ---- //
                  // ----------------------------------------- //
                }, // End Success Function
                error: function(jqxhr,status,error) {
                  $.alert("Error: " + error, "Alert");
                } 
              }); // End AJAX function
   	
   });  // End $(document).ready
    
	
</script>
   	   	 
</body>
   
</html>