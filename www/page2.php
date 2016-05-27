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

<tr><td align="left" valign="bottom" height="35" width="100%" nowrap colspan="4" style="padding: 20px;">
<h1>Radio Question and Answer</h1>
</td></tr>

<tr><td valign="top" align="left" style="min-width:140px; padding-left:20px; padding-right:20px; font-size: 18px;">

<!-- Menu -->
<a href="index.php" style="text-decoration: none;">Home </a> <br>
<hr>
<a href="page2.php" style="text-decoration: none;">Ask question / Provide information(answers)</a> <br>
<hr>
<a href="page3.php" style="text-decoration: none;">Listen to informaiton(answers)</a> <br>

</td>

<!------------------------------------------------------------------------------------------------------------->

<td valign="top" bgcolor="white" width="100% "style="padding: 20px;">

<h2>Ask a question / Provide information(answers)</h2>
<hr>  

<?php
// define variables and set to empty values
$startDate = $endDate = "";


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

<h3>Filter questions by selecting the date</h3> 

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

Start date: <input type="date" min=2016-05-25 step=1 id="datepicker" name='startDate' size='9' value="<?php echo $startDate;?>" />
End date: <input type="date" min=2016-05-25 step=1 id="datepicker" name='endDate' size='9' value="<?php echo $endDate;?>" />
<input type="submit" name="submit" value="Filter questions">
</form>


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RadioQueAns";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = 	"SELECT * FROM QuestionFactTable WHERE Date_ID NOT IN
		(SELECT Date_ID FROM DateTable WHERE
		   (startDate <= '" .$startDate. "' AND endDate >= '" .$startDate. "') OR
		   (startDate <= '" .$endDate. "' AND endDate >= '" .$endDate. "'))";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
	echo "<table cellpadding='5' cellspacing='5' border='0'><tr><th>Question ID</th><th>Question Wavefile</th><th>Category</th><th>Date</th></tr>";
	// output data of each row
	while($row = $result->fetch_assoc()) {
		echo "<tr><td>".$row["Question_ID"]."</td><td>".$row["Question_Wavefile"]."</td><td>".$row["Category_Wavefile_ID"]."</td><td>".$row["startDate"]."</td></tr>";
	}
	echo "</table>";
} else {
	echo "0 results";
}

$conn->close();
?>

<hr>

<!------------------------------------------------------------------------------------------------------------->

<?php
// define variables and set to empty values
$telNrErr = $categoryIDErr = "";
$categoryID = 0;
$name =  $telNr = $surname =  "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["categoryID"])) {
     $categoryIDErr = "Du m&aring;ste ange stug nummer!";
   } else {
     $categoryID = test_input($_POST["categoryID"]);
   }

   if (empty($_POST["name"])) {
     $name = "";
   } else {
     $name = test_input($_POST["name"]);
   }
	
   if (empty($_POST["telNr"])) {
     $telNr = "You have to give a telephone number";
   } else {
     $telNr = test_input($_POST["telNr"]);
   }   
   
   if (empty($_POST["surname"])) {
     $surname = "";
   } else {
     $surname = test_input($_POST["surname"]);
   }
   
}
?>

<h3>Fill in the application to ask a question or give an answer</h3>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<h4>Personal Information: </h4>
  <table> 
	<tr> 
	<td>Name:</td> 
	<td><input type="text" name="name" value="<?php echo $name;?>"></td>
	</tr>
	
	<tr>
	<td> Surname: </td>  
	<td> <input type="text" name="surname" value="<?php echo $surname;?>"></td>
	</tr>
	
	<tr>
	<td> Telephone Number: </td> 
	<td> <input type="text" name="telNr" value="<?php echo $telNr;?>">* <!--<span class="error">* <?php //echo $surnameErr;?></span>--></td>
	</tr>
	
	<tr>
	<td colspan="2"><br><h4>Category: </h4></td>
	</tr>
	
	<tr>
	<td>Select: <br><sub> 1 for Rainfall <br> 2 for Weather Forecast (Temperature, Wind, etc)<br> 3 for harvesting <br> 4 for Seed Planting <br> 5 for Animal Health <br> 6 for Others (no Category)</sub> </td>     
	<td><input type="number" min=0 name="categoryID" value="<?php echo $categoryID;?>">* <!-- <span class="error">* <?php //echo $categoryIDErr;?></span>--> </td>
	</tr>
	
    <tr>
	<td colspan="2"><br><h4>Select if it is a question or answer </h4></td>
	</tr>
  	<tr>
	
	<td>Select:</td>
	<td>
	<input type="radio" name="Question/Answer"  if (isset($Question_ID) && $Question_ID=="Question") echo "checked";  value="Question">Question
	<input type="radio" name="Question/Answer"  if (isset($Answer_ID) && $Answer_ID=="Answer") echo "checked";  value="Answer">Answer
	<tr>
	<td> Select Question ID <br>(Only if you choose answer)</br></td> 
	<td> <input type="text" name="adress" value="<?php echo $adress;?>"></td>
	</tr>
	*
	</td>
	
	<tr>
	<td colspan="2"><br><h4>Question/Answer: </h4></td>
	</tr>
	
	<tr>
	<td>Type in your question/answer: <br><sub> Spell correct <br> to avoid errors</sub></td> 
	<td><textarea name="medResenerer" rows="5" cols="40"><?php echo $question/answer;?></textarea></td>
	</tr>
	
			
	
	<tr>
	<td/>
	<td><br><input type="submit" name="submit" value="Send" style="width: 80px;"></td>
	<tr>
	</table>  
</form>

<!------------------------------------------------------------------------------------------------------------->

<br> <br> 

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "RadioQueAns";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// resetar värdet på return
$conn->query("SET @return = ''");

$sql = "CALL sp_QuestionFactTable('" .$Question_ID. "', '" .$Question_Wavefile. "', '" .$Date_ID. "', '" .$Answer_ID. "', '" .$Category_ID. "', " .$Info_req_ID. ", '" .$startDate. "', '" .$endDate. "', @return);";
$sql = "CALL sp_DateTable('" .$Date_ID. "', '" .$startDate. "', '" .$endDate. "', @return);";
//echo $sql;
if ($conn->query($sql) === TRUE) {
	
	$select = 'SELECT @return;';
	$res = $conn->query($select);
	if ($result->num_rows > 0) {
		while($row = $res->fetch_assoc()) {
			echo "<span style='font-size:20px;'>" .$row["@return"]. "</span>";
		}
	} else {
		//echo "Error: " . $select . "<br>" . $conn->error;
	}
	
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>

<!------------------------------------------------------------------------------------------------------------->

<br><br> 

</td>

</tr></table>

</body></html>