<?php
session_start();
require_once("./database.php");

$err=" ";
$main_id = $_SESSION['id'];


if(isset($_SESSION['id']) && $_SESSION['id']==1):{

	if(isset($_POST['register'])){


			$sql="INSERT INTO comp (name, price, pprice) VALUES ('".$_POST['name']."',".$_POST['price'].",".$_POST['price'].")";
			if(mysqli_query($conn, $sql)){
				$sql = ("INSERT INTO buysell (userid, compid, price, quantity, type)
				VALUES (
					".$postuserid.",
					".$postcompid.",
					".$postprice.",
					".$tempquantity.",
					".$posttype."
				);");
                if($conn,$sql){
                    $err="Company registered successfully!<br/>";
                }
				$sql2 = "SELECT id, name, price FROM comp";
				$result = mysqli_query($conn, $sql2);

				while($row = mysqli_fetch_assoc($result)){
					if($_POST['name']==$row["name"] && $_POST['price']==$row["price"]){
						$err=$err."Company ID of the new company is: <b>".$row["id"]."</b>";
					}
				}
			}else{
				$err="Error registering company: " . mysqli_error($conn);
			}	


	}
}?>
<html>
	<head>
		<title>
			Registeration Panel
		</title>
	</head>

	<body>
	<center>
		<b style="font-size:25px;">Registeration Panel</b><br/><br/><br/>
		<?php echo $err ?>
			<form src="./reg.php" method="post">
				<input type="text" name="name" placeholder="Enter company name" /><br/>
				<input type="number" name="price" placeholder="Enter Initial Price" /><br/>
				<input type="submit" name="register" value="Register" />
			</form>
		</center>
	</body>
</html>
<?php else:{
	$err=$err."You don't have the authentication for registering new companies!";
}?>
<html>
	<head>
		<title>
			Registeration Panel
		</title>
	</head>

	<body>
	<center>
		<b style="font-size:25px;">Registeration Panel</b><br/><br/><br/>
		<?php echo $err; ?>
<?php endif;?>

