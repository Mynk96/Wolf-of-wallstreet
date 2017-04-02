<?php
session_start();
require_once("database.php");


$err=" ";

if(isset($_SESSION['id']) && $_SESSION['id']==1):{

	if(isset($_POST['register']) && $_POST['name']!=null && $_POST['password']!=null && $_POST['amount']!=null){

		if($_POST['password']==$_POST['re-password']){

			$sql="INSERT INTO users (name, password, amount) VALUES ('".$_POST['name']."','".$_POST['password']."',".$_POST['amount'].")";
			if(mysqli_query($conn, $sql)){
				$err="User registered successfully!<br/>";

				$sql2 = "SELECT id, name, password FROM users";
				$result = mysqli_query($conn, $sql2);

				while($row = mysqli_fetch_assoc($result)){
					if($_POST['name']==$row["name"] && $_POST['password']==$row["password"]){
						$err=$err."User ID of the new user is: <b>".$row["id"]."</b>";
					}
				}
			}else{
				$err="Error registering user: " . mysqli_error($conn);
			}	

		}else{
			$err=$err."Passwords don't match!";
		}
	}
}?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>
			Registeration Panel
		</title>
        <link rel="stylesheet" href="css/font.css"  type='text/css'>
    
        <link rel="stylesheet" href="css/normalize.css">

        <link rel="stylesheet" href="css/style.css">
	</head>

	<body>
	<a href="./index.php" style="border:solid 1px black;">Home</a>
	<center>
		<b style="font-size:25px;">Registeration Panel</b><br/><br/><br/>
		<?php echo $err ?>
			<form src="./reg.php" method="post">
				<input type="text" name="name" placeholder="Enter your name" /><br/>
				<input type="password" name="password" placeholder="Enter Password" /><br/>
				<input type="password" name="re-password" placeholder="Re-enter Password" /><br/>
				<input type="number" name="amount" placeholder="Enter initial amount" /><br/>
				<input type="submit" name="register" value="Register" />
			</form>
		</center>
	</body>
</html>
<?php
else:{
	$err=$err."Contact Registration desk for Registration!";
}
?>
<html>
	<head>
		<title>
			Registeration Panel
		</title>
        <link rel='stylesheet' href="css/font.css"  type='text/css'>
    
    <link rel="stylesheet" href="css/normalize.css">

    <link rel="stylesheet" href="css/style.css">
	</head>

	<body>
	<center>
		<b style="font-size:25px; color:red;">Registeration Panel</b><br/><br/><br/>
		<h2 style="color:cyan"><?php echo $err; ?></h2>
        <a href="./index.php"><h2>Home</h2></a>

			
		</center>
	</body>
</html>
<?php endif;?>