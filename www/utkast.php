<?php
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname );
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM testtext";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>testt</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["testt"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?>


Start datum: <input type="date" min=2015-10-03 step=7 id="datepicker" name='startDatum' size='9' value="" />
Antal veckor: <input type="number" min=1 name="antalVeckor" style="width: 80px"/>
<br>


"SELECT *
FROM stuga
WHERE stugNr NOT IN
(SELECT Hyrning 
 FROM StugNr
 WHERE
   (checkin <= '$_POST[startDatum]' AND checkout >= '$_POST[startDatum]') OR
   (checkin <= '$_POST[slutDatum]' AND checkout >= '$_POST[slutDatum]') OR
   (checkin >= '$_POST[startDatum]' AND checkout <= '$_POST[slutDatum]'))"
   
   
   
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
 
$sql= "INSERT INTO nametable (fname, lname) VALUES('$_POST[startDatum]','$_POST[antalVeckor]')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
 
$conn->close();
?>

<hr>