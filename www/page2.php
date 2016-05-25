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
<h1>Dalarnas Stugby</h1>
</td></tr>

<tr><td valign="top" align="left" style="min-width:140px; padding-left:20px; padding-right:20px; font-size: 18px;">

<!-- Menu -->
<a href="index.php" style="text-decoration: none;">Hem </a> <br>
<hr>
<a href="page2.php" style="text-decoration: none;">Hyr stuga</a> <br>
<hr>
<a href="page3.php" style="text-decoration: none;">Boka utrustning</a> <br>

</td>

<!------------------------------------------------------------------------------------------------------------->

<td valign="top" bgcolor="white" width="100% "style="padding: 20px;">

<h2>Hyr stuga</h2>
<hr>  

<?php
// define variables and set to empty values
$startDatum = $slutDatum = "";


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

<h3>Filtrera stugorna f&ouml;r att kolla ledighet</h3> 

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 

Start datum: <input type="date" min=2015-10-03 step=7 id="datepicker" name='startDatum' size='9' value="<?php echo $startDatum;?>" />
Slut datum: <input type="date" min=2015-10-03 step=7 id="datepicker" name='slutDatum' size='9' value="<?php echo $slutDatum;?>" />
<input type="submit" name="submit" value="Filtrera stugor">
</form>


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

$sql = 	"SELECT * FROM Stuga WHERE stugNr NOT IN
		(SELECT stugNr FROM Hyrning WHERE
		   (startDatum <= '" .$startDatum. "' AND slutDatum >= '" .$startDatum. "') OR
		   (startDatum <= '" .$slutDatum. "' AND slutDatum >= '" .$slutDatum. "'))";

$result = $conn->query($sql);
   
if ($result->num_rows > 0) {
	echo "<table cellpadding='5' cellspacing='5' border='0'><tr><th>Stug nummer</th><th>Stug adress</th><th>Antal b&auml;ddar</th><th>Antal rum</th><th>Standard i k&ouml;ket</th><th>&Ouml;vrig utrustning</th><th>Veckohyra</th></tr>";
	// output data of each row
	while($row = $result->fetch_assoc()) {
		echo "<tr><td>".$row["stugNr"]."</td><td>".$row["stugAdress"]."</td><td>".$row["antalBaddar"]."</td><td>".$row["antalRum"]."</td><td>".$row["standardIKoket"]."</td><td>".$row["ovrigUtrustning"]."</td><td>".$row["veckoHyra"]."</td></tr>";
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
$personNrErr = $stugNrErr = "";
$stugNr = 0;
$namn =  $telNr = $adress = $personNr = $medResenerer = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["stugNr"])) {
     $stugNrErr = "Du m&aring;ste ange stug nummer!";
   } else {
     $stugNr = test_input($_POST["stugNr"]);
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
   
   if (empty($_POST["medResenerer"])) {
     $medResenerer = "";
   } else {
     $medResenerer = test_input($_POST["medResenerer"]);
   }
}
?>

<h3>Fyll i formul&auml;ret f&ouml;r att hyra en stuga</h3>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<h4>Person uppgifter: </h4>
  <table> 
	<tr> 
	<td>Namn:</td> 
	<td><input type="text" name="namn" value="<?php echo $namn;?>">*</td>
	</tr>
	
	<tr>
	<td> Personnummer: <br><sub>(YYMMDDXXXX) </sub></td>  
	<td> <input type="text" name="personNr" value="<?php echo $personNr;?>">* <!--<span class="error">* <?php //echo $personNrErr;?></span>--> </td>
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
	<td colspan="2"><br><h4>Medresen&auml;rer: </h4></td>
	</tr>
	
	<tr>
	<td>Personnummer: <br><sub>(YYMMDDXXXX sparera <br> med semikolon " ; ")</sub></td> 
	<td><textarea name="medResenerer" rows="5" cols="40"><?php echo $medResenerer;?></textarea></td>
	</tr>
	
	
	<tr>
	<td colspan="2"><br><h4>Stuga: </h4></td>
	</tr>
	
	<tr>
	<td>Stug nummer :</td>     
	<td><input type="number" min=0 name="stugNr" value="<?php echo $stugNr;?>">* <!-- <span class="error">* <?php //echo $stugNrErr;?></span>--> </td>
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
	<td/>
	<td><br><input type="submit" name="submit" value="Hyr" style="width: 80px;"></td>
	<tr>
	</table>  
</form>

<!------------------------------------------------------------------------------------------------------------->

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

$sql = "CALL sp_hyr('" .$namn. "', '" .$personNr. "', '" .$adress. "', '" .$telNr. "', '" .$medResenerer. "', " .$stugNr. ", '" .$startDatum. "', '" .$slutDatum. "', @return);";
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