
<?php
$servername = "ecs-pd-proj-db.ecs.csus.edu";
$username = "CSC174144";
$password = "Csc134_765446362";
$dbname = "CSC174144";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die('Connect Error (' . 
    $conn->connect_errno . ') '. 
    $conn->connect_error);
}

//query for all rooms
$sql="SELECT * FROM ROOM";
$result = $conn->query($sql);

//query for premium rooms
$sql1="SELECT * FROM PREMIUM";
$premium = $conn->query($sql1);

//query for deluxe rooms
$somequery = "SELECT * FROM DELUXE";
$deluxe = $conn->query($somequery);

if(isset($_POST['submit'])){
  $dlnumber = $_REQUEST['dlnumber'];
  $name = $_REQUEST['name'];
  $email = $_REQUEST['email'];
  $phone = $_REQUEST['phone'];
  $zip = $_REQUEST['zip'];
  $customerType = $_REQUEST['customerType'];
  $couponCode = $_REQUEST['couponCode'];
  $vipId = $_REQUEST['vipId'];
  $roomNum = $_REQUEST['roomNum'];


  //prepare statement
  //$sql2 = $conn->prepare("INSERT INTO CUSTOMER VALUES (?,?,?,?,?,?,?,?,?)");
  //$sql2->bind_param("sssssssss",'$dlnumber','$name','$email','$phone','$zip','$customerType','$couponCode','$vipId',$roomNum);

  $sql2 = "INSERT INTO CUSTOMER VALUES('$dlnumber','$name','$email','$phone','$zip','$customerType','$couponCode','$vipId',$roomNum)";
  $conn->query($sql2);

}

if(isset($_POST['checkout'])){
	$roomNum = $_REQUEST['roomNum'];

	//delete the customer on that room
	// $sqll = $conn->prepare("DELETE FROM CUSTOMER WHERE roomNum=? ");
	// $sqll->bind_param("s", $roomNum);
	
	$sql = "DELETE FROM CUSTOMER WHERE roomNum = $roomNum";
	$conn->query($sql);
}


//stored procedure call to get the number of deluxe rooms
$rs = $conn->query("CALL COUNTROOMTYPE('DELUXE',@output)");
$rs2 = $conn->query("SELECT @output as output");

//stored procedure call to get the number of premium rooms
$rs3 = $conn->query("CALL COUNTROOMTYPE('PREMIUM',@output)");
$rs4 = $conn->query("SELECT @output as output2");

//query for customers added to the db
$customerquery = "SELECT * FROM CUSTOMER";
$customer = $conn->query($customerquery);

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Hotel Anmol</title>
	<style>
        table {
            margin: 0 auto;
            font-size: large;
            border: 1px solid black;
        }
  
        h1 {
            text-align: center;
            color: #006600;
            font-size: xx-large;
            font-family: 'Gill Sans', 'Gill Sans MT', 
            ' Calibri', 'Trebuchet MS', 'sans-serif';
        }
  
        td {
            background-color: #E4F5D4;
            border: 1px solid black;
        }
  
        th,
        td {
            font-weight: bold;
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
  
        td {
            font-weight: lighter;
        }
    </style>
    <script type="text/javascript">
    	function enableTextField(){
    		var check = document.getElementById("regular");
    		var couponTextField = document.getElementById("coupon");

    		var check2 = document.getElementById("vip");
    		var vipTextField = document.getElementById("vipid");


    		if(check.checked){
    			couponTextField.disabled = false;
    			vipTextField.disabled = true;
    		}

    		if(check2.checked){
    			couponTextField.disabled = true;
    			vipTextField.disabled = false;
    		}
    	}
    </script>
</head>
<body>

	<h1>Welcome to Hotel Anmol</h1>
	<h1>Your choice for a safe stay!</h1>
	<br>
	<h3>All Rooms:</h3>
	<table>
		<tr>
			<th>Room No.</th>
			<th>Price($)</th>
			<th>Size(Sq.ft)</th>
			<th>No. of Beds</th>
			<th>View</th>
			<th>Room Type</th>
			<th>Champagne Bottles</th>
			<th>No. of Bedrooms</th>
		</tr>
		<?php 
			while($rows=$result->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $rows['roomNum'];?></td>
			<td><?php echo $rows['roomPrice'];?></td>
			<td><?php echo $rows['roomSize'];?></td>
			<td><?php echo $rows['numOfBeds'];?></td>
			<td><?php echo $rows['roomView'];?></td>
			<td><?php echo $rows['roomType'];?></td>
			<td><?php echo $rows['numOfFreeChampagne'];?></td>
			<td><?php echo $rows['numOfBedrooms'];?></td>
		</tr>
		<?php 
			}
		?>
		
	</table>
	<h3>
		Number of Deluxe Rooms:
		<?php 
			$row = $rs2->fetch_assoc();
			echo $row['output'];
		?>
	</h3>
	<h3>
		Number of Premium Rooms:
		<?php 
			$row = $rs4->fetch_assoc();
			echo $row['output2'];
		?>
	</h3>
	<br>
	<div>
		<h3>Premium Rooms:</h3>
	<table style="display: inline-block;">
		<tr>
			<th>Room No.</th>
			<th>Price($)</th>
			<th>Size(Sq.ft)</th>
			<th>No. of Beds</th>
			<th>View</th>
			<th>No. of Bedrooms</th>
		</tr>
		<?php 
			while($rows=$premium->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $rows['roomNum'];?></td>
			<td><?php echo $rows['roomPrice'];?></td>
			<td><?php echo $rows['roomSize'];?></td>
			<td><?php echo $rows['numOfBeds'];?></td>
			<td><?php echo $rows['roomView'];?></td>
			<td><?php echo $rows['numOfBedrooms'];?></td>
		</tr>
		<?php 
			}
		?>
		
	</table>

	<br>
	<h3>Deluxe Rooms:</h3>
	<table style="display: inline-block;">
		<tr>
			<th>Room No.</th>
			<th>Price($)</th>
			<th>Size(Sq.ft)</th>
			<th>No. of Beds</th>
			<th>View</th>
			<th>Champagne Bottles</th>
		</tr>
		<?php 
			while($rows=$deluxe->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $rows['roomNum'];?></td>
			<td><?php echo $rows['roomPrice'];?></td>
			<td><?php echo $rows['roomSize'];?></td>
			<td><?php echo $rows['numOfBeds'];?></td>
			<td><?php echo $rows['roomView'];?></td>
			<td><?php echo $rows['numOfFreeChampagne'];?></td>
		</tr>
		<?php 
			}
		?>
		
	</table>
	</div>
	
	<h2>Fill out your info to reserve a room:</h2><br>
	<form action="index.php" method="POST">
		Guest Name: <input type="text" name="name"><br><br>
		E-mail: <input type="text" name="email"><br><br>
		Phone number: <input type="text" name="phone"><br><br>
		ZipCode: <input type="text" name="zip"><br><br>
		DL Number: <input type="text" name="dlnumber"><br><br>

		Type of Guest:
		<input type="radio" name="customerType" value="REGULAR" id="regular" onclick="enableTextField()">REGULAR
		<input type="radio" name="customerType" value="VIP" id="vip">VIP <br><br>

		Coupon Code: <input type="text" name="couponCode" id="coupon" onclick="enableTextField()"><br><br>
		VIPID: <input type="text" name="vipId" id="vipid"><br><br>
		Room Number: <input type="text" name="roomNum" placeholder="Enter the room number from above table"><br><br>

		<input type="submit" name='submit'>
	</form>

	<br>
	<h3>Reserved Room Info:</h3>
	<table>
		<tr>
			<th>DLNum</th>
			<th>Name</th>
			<th>Email</th>
			<th>Phone No.</th>
			<th>Zip</th>
			<th>CustomerType</th>
			<th>Coupon Code</th>
			<th>VIPID</th>
			<th>Room No.</th>
		</tr>
		<?php 
			while($rows=$customer->fetch_assoc())
			{
		?>
		<tr>
			<td><?php echo $rows['DLNum'];?></td>
			<td><?php echo $rows['name'];?></td>
			<td><?php echo $rows['email'];?></td>
			<td><?php echo $rows['phone'];?></td>
			<td><?php echo $rows['zipCode'];?></td>
			<td><?php echo $rows['customerType'];?></td>
			<td><?php echo $rows['couponCode'];?></td>
			<td><?php echo $rows['VIPID'];?></td>
			<td><?php echo $rows['roomNum'];?></td>
		</tr>
		<?php 
			}
		?>
		
	</table>

	<h3>CHECK OUT:</h3>
	<form action="index.php" method="POST">
		Room Number: <input type="text" name="roomNum" placeholder="Room no. to checkout"><br>
		<input type="submit" name='checkout'>
	</form>
	
</body>
</html>