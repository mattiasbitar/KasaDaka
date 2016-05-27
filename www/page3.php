<!----------------------------------------
  --   			Mattias Bitar   		--
  ---------------------------------------->

<!------------------------------------------------------------------------------------------------------------->
<!-- Head -->

<html>
<body background="dalarna.jpg">
<head>
<title> Radio Question and Answer </title>
<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>

<?php
//globala variabler
$currentDate=date("W"); //23

//funktioner
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<!------------------------------------------------------------------------------------------------------------->

<!-- Table for Main Body -->
<table border="0" width="100%" cellspacing="0">
<tr>
<td align="left" valign="bottom" height="35" width="100%" nowrap colspan="4" style="padding: 20px;">
<h1>Radio Question and Answer</h1>
</td>
</tr>
<tr><td valign="top" align="left" style="min-width:140px; padding-left:20px; padding-right:20px; font-size: 18px;">


<a href="index.php" style="text-decoration: none;">Home </a> <br>
<hr>
<a href="page2.php" style="text-decoration: none;">Ask question / Provide information(answers)</a> <br>
<hr>
<a href="page3.php" style="text-decoration: none;">Listen to informaiton(answers)</a> <br>

</td>
<td valign="top" bgcolor="white" width="100% "style="padding: 20px;">


<!------------------------------------------------------------------------------------------------------------->

<h2> Listen to informaiton(and answers)</h2>
<hr>  

<?php
// define variables and set to empty values
$startDate = $endDate = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST") {   
   if (empty($_POST["startDate"])) {
     $startDate = "";
   } else {
     $startDate = test_input($_POST["startDate"]);
   }
   
   if (empty($_POST["endDate"])) {
     $endDate = "";
   } else {
     $endDate = test_input($_POST["endDate"]);
   }
}
?>

<h3>See answers and general informaiton by date</h3>

<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]);?>

Start Date: <input type="date" min=2016-05-25 step=1 id="datepicker" name='startDate' size='9' value="<?php $startDate;?>" />
End Date: <input type="date" min=2016-05-25 step=1 id="datepicker" name='endDate' size='9' value="<?php $endDate;?>" />
<input type="submit" name="submit" value="See answers">
</form>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RadioQueAns";

 
$date = new DateTime($startDate);
$week = $date->
<table cellpadding='5' cellspacing='5' border='0'><tr><th>Answer ID</th><th>Answer Wavefile</th><th>Date</th><th>Answer to Question ID</th><th>Question Wavefile</th><th>Category</th></tr>
</table>



<hr>

<!------------------------------------------------------------------------------------------------------------->

<br> <br> 

</td>

</tr></table>

</body></html>