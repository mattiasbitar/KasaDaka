<!----------------------------------------
  --  Kristian ELawad & Mattias Bitar   --
  --    Indek åk4 - Databas projekt     --
  ---------------------------------------->

<!-- 
Å = &Aring;
å = &aring; 
Ä = &Auml;
ä = &auml; 
Ö = &Ouml;
ö = &ouml; 
-->

<!------------------------------------------------------------------------------------------------------------->
<!-- Head -->

<html>
<head>
<title> Dalarnas Stugby </title>
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
<h1>Dalarnas Stugby</h1>
</td>
</tr>
<tr><td valign="top" align="left" style="min-width:140px; padding-left:20px; padding-right:20px; font-size: 18px;">


<a href="index.php" style="text-decoration: none;">Hem </a> <br>
<hr>
<a href="page2.php" style="text-decoration: none;">Hyr stuga</a> <br>
<hr>
<a href="page3.php" style="text-decoration: none;">Boka utrustning</a> <br>

</td>
<td valign="top" bgcolor="white" width="100% "style="padding: 20px;">


<!------------------------------------------------------------------------------------------------------------->

<h2> Boka utrustning</h2>
<hr>  

<?php
// define variables and set to empty values
$startDatum = $slutDatum = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST") {   
   if (empty($_POST["startDatum"])) {
     $startDatum = "";
   } else {
     $startDatum = test_input($_POST["startDatum"]);
   }
   
   if (empty($_POST["slutDatum"])) {
     $slutDatum = "";
   } else {
     $slutDatum = test_input($_POST["slutDatum"]);
   }
}
?>


<h3>Se utrustning enligt s&auml;song</h3>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

Start datum: <input type="date" min=2015-10-03 step=7 id="datepicker" name='startDatum' size='9' value="<?php echo $startDatum;?>" />
Slut datum: <input type="date" min=2015-10-03 step=7 id="datepicker" name='slutDatum' size='9' value="<?php echo $slutDatum;?>" />
<input type="submit" name="submit" value="Se s&auml;song">
</form>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname );
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
 
$date = new DateTime($startDatum);
$week = $date->format("W");

if((($week>=41) && ($week<=52))||(($week>=0) && ($week<=13))){
	$sql = "SELECT * FROM utrustning WHERE arsSasang = 'vinter'";
}
else {
	$sql = "SELECT * FROM utrustning WHERE arsSasang = 'sommar'";
}
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table cellpadding='5' cellspacing='5' border='0'><tr><th>Utrustnings nummer</th><th>Utrustnings typ</th><th>Storlek</th><th>Pris</th><th>&Aring;rs s&auml;song</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["utrustningsNr"]."</td><td>".$row["utrustningsTyp"]."</td><td>".$row["storlek"]."</td><td>".$row["pris"]."</td><td>".$row["arsSasang"]."</td></tr>";
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
$nameErr = $emailErr = $utrustningErr = $websiteErr = "";
$personNrErr = "";
$startDatum = $slutDatum = $namn = $personNr = $telNr = $adress  = $utrustningsTyp = "";
$langd = $huvudOmkrets = $skoStrl = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	   if (empty($_POST["startDatum"])) {
     $startDatum = "";
   } else {
     $startDatum = test_input($_POST["startDatum"]);
   }
   
   if (empty($_POST["slutDatum"])) {
     $slutDatum = "";
   } else {
     $slutDatum = test_input($_POST["slutDatum"]);
   }
   
   if (empty($_POST["namn"])) {
     $namn = "";
   } else {
     $namn = test_input($_POST["namn"]);
   }
   
   if (empty($_POST["personNr"])) {
     $personNrErr = "Du m&aring;ste ange personnummer!";
   } else {
     $personNr = test_input($_POST["personNr"]);
   }   
   
   if (empty($_POST["adress"])) {
     $adress = "";
   } else {
     $adress = test_input($_POST["adress"]);
   }
   
   if (empty($_POST["telNr"])) {
     $telNr = "";
   } else {
     $telNr = test_input($_POST["telNr"]);
   }
   
   if (empty($_POST["langd"])) {
     $langd = "";
   } else {
     $langd = test_input($_POST["langd"]);
   }
   
   if (empty($_POST["huvudOmkrets"])) {
     $huvudOmkrets = "";
   } else {
     $huvudOmkrets = test_input($_POST["huvudOmkrets"]);
   }
   
   if (empty($_POST["skoStrl"])) {
     $skoStrl = "";
   } else {
     $skoStrl = test_input($_POST["skoStrl"]);
   }
   
   if (empty($_POST["utrustningsTyp"])) {
     $utrustningErr = "Val av utrustning krävs";
   } else {
     $utrustningsTyp = test_input($_POST["utrustningsTyp"]);
   }
}
?>

<!------------------------------------------------------------------------------------------------------------->

<h3>Fyll i formul&auml;ret f&ouml;r att boka utrustning</h3>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

<h4>Person uppgifter: </h4>
  <table> 
	<tr> 
	<td>Namn:</td>
	<td><input type="text" name="namn" value="<?php echo $namn;?>">*</td>
	</tr>
	
	<tr>
	<td> Personnummer: <br><sub>(YYMMDDXXXX) </sub></td>  
	<td> <input type="text" name="personNr" value="<?php echo $personNr;?>">* <!--<span class="error">* <?php //echo $personNrErr;?></span>--></td>
	</tr>
	
	<tr>
	<td> Adress:  </td> 
	<td> <input type="text" name="adress" value="<?php echo $adress;?>">*</td>
	</tr>
	
	<tr>
	<td> Telefon Nummer: </td> 
	<td> <input type="text" name="telNr" value="<?php echo $telNr;?>">*</td>
	</tr>
	
	
    <tr>
	<td colspan="2"><br><h4>Datum f&ouml;r bokning: </h4></td>
	</tr>
  
	<tr> 
	<td>Start datum: </td>
	<td><input type="date" min=2015-10-03 step=7 id="datepicker" name='startDatum' size='9' value="<?php echo $startDatum;?>" />*</td>
	</tr>
	
	<tr> 
	<td>Slut datum: </td>
	<td><input type="date" min=2015-10-03 step=7 id="datepicker" name='slutDatum' size='9' value="<?php echo $slutDatum;?>" />*</td>
	</tr>
	
	
	<tr>
	<td colspan="2"><br><h4>Uppgifter f&ouml;r utrustning: </h4></td>
	</tr>
   
	<tr>
	<td>L&auml;ngd:</td>     
	<td><input type="number" min=0 name="langd" value="<?php echo $langd;?>">* (cm)</td>
	</tr>
   
	<tr>
	<td>Huvud omkrets:</td>     
	<td><input type="number" min=0 name="huvudOmkrets" value="<?php echo $huvudOmkrets;?>">* (cm)</td>
	</tr>
   
<?php 

	$date = new DateTime($startDatum);
	$week = $date->format("W");
 
	if(($week>=41 && $week<=52)||($week>=0 && $week<=13)){
		echo' 
		   <tr>
		   <td>Sko storlek:</td>     
		   <td><input type="number" min=0 name="skoStrl" value="<?php echo $skoStrl;?>">*</td>
		   </tr>
		   
		   <tr>
		   <td>Skidor:</td>
		   <td>
		   <input type="radio" name="utrustningsTyp"  if (isset($utrustningsTyp) && $utrustningsTyp=="langd") echo "checked";  value="langd">L&auml;ngd&aring;kning
		   <input type="radio" name="utrustningsTyp"  if (isset($utrustningsTyp) && $utrustningsTyp=="utfor") echo "checked";  value="utfor">Utf&ouml;rs&aring;kning
		   *
		   <td>
		   </tr>
	   ';
	   }
	   
	 else{
		echo'
			<tr>
			<td>Cykel:</td>
			<td>
			<input type="radio" name="utrustningsTyp"  if (isset($utrustningsTyp) && $utrustningsTyp=="landsVag") echo "checked";  value="landsVag">Landsv&auml;gscyckling
			<input type="radio" name="utrustningsTyp"  if (isset($utrustningsTyp) && $utrustningsTyp=="downHill") echo "checked";  value="downHill">Down Hill
			<input type="radio" name="utrustningsTyp"  if (isset($utrustningsTyp) && $utrustningsTyp=="mountainBike") echo "checked";  value="mountainBike">Mountain Bike
			*
			</td>
	   ';
	 }  
?>

   <tr>
   <td/>
   <td><br><input type="submit" name="submit" value="Boka utrustning"></td> 
   </tr>
   
</form>
</table>

<br> <br> 

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// resetar värdet på return
$conn->query("SET @return = ''");

$date = new DateTime($startDatum);
$week = $date->format("W");
 
if(($week>=41 && $week<=52)||($week>=0 && $week<=13)){
	
}
else 
{
	$skoStrl = 0;
}

$sql = "CALL sp_bokning('" .$namn. "', '" .$personNr. "', '" .$adress. "', '" .$telNr. "', '" .$startDatum. "', '" .$slutDatum. "', " .$langd. ", " .$huvudOmkrets. ", " .$skoStrl. ", '" .$utrustningsTyp. "', @result);";
//echo $sql. "<br>";
if ($conn->query($sql) === TRUE) {
	
	$select = 'SELECT @result;';
	$res = $conn->query($select);
	if ($result->num_rows > 0) {
		while($row = $res->fetch_assoc()) {
			echo "<span style='font-size:20px;'>" .$row["@result"]. "</span>";
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

<br> <br> 

</td>

</tr></table>

</body></html>