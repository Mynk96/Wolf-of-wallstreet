<?php

$err=" ";
$pprice=0;

if(isset($_POST['buysellsubmit'])){
	if(isset($_SESSION['id'])){
		if($_POST['buysell']!=null && $_POST['company']!=null && $_POST['price']!=null && $_POST['quantity']!=null){	//All parameters not filled in.
			if($_POST['quantity']>0 && $_POST['quantity']<51){
				$sql="SELECT id, price FROM comp";
				$result = mysqli_query($conn, $sql);
				$found=0;
				while($row = mysqli_fetch_assoc($result)){
					if($_POST['company']==$row['id']){
						$found=1;
						break;
					}
				}
				if($found && ( ($_POST['buysell']==0 && (($_POST['quantity'] * $_POST['price']) < $_SESSION['amount']))) || $_POST['buysell']==1 ){
					if($_POST['price'] > (1.015 * $row['price']) || $_POST['price'] < (0.985 * $row['price'])){
						$err=$err."You cannot buy/sell a stock for more than 1.5% change";
					}else{
						$sql="SELECT * FROM buysell";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_assoc($result)){
							if($row['compid']==$_POST['company'] && $row['type']!=$_POST['buysell'] && $row['price']==$_POST['price'] && $row['userid']!=$_SESSION['id']){
								$typenew=($_POST['buysell'] + 1) % 2;
								if($row['quantity']>$_POST['quantity']){
									$diff=$row['quantity'] - $_POST['quantity'];
									$sql2="UPDATE buysell SET quantity=".$diff." WHERE id=".$row['id']."";
									$sql3="INSERT INTO balsheet (userid, type, quantity, price, compid) VALUES (".$row['userid'].",".$typenew.",".$_POST['quantity'].",".$_POST['price'].",".$_POST['company'].")";
									$sql4="INSERT INTO balsheet (userid, type, quantity, price, compid) VALUES (".$_SESSION['id'].",".$_POST['buysell'].",".$_POST['quantity'].",".$_POST['price'].",".$_POST['company'].")";
									if($_POST['buysell']){
										updAmt($row['userid'],-($_POST['quantity'] * $_POST['price']));
										updAmt($_SESSION['id'],($_POST['quantity'] * $_POST['price']));
									}else{
										updAmt($row['userid'],($_POST['quantity'] * $_POST['price']));
										updAmt($_SESSION['id'],-($_POST['quantity'] * $_POST['price']));
									}
									$_POST['quantity']=0;
								}else{
									$_POST['quantity']=$_POST['quantity'] - $row['quantity'];
									$sql2="DELETE FROM buysell WHERE id=".$row['id'];
									$sql3="INSERT INTO balsheet (userid, type, quantity, price, compid) VALUES (".$row['userid'].",".$typenew.",".$row['quantity'].",".$_POST['price'].",".$_POST['company'].")";
									$sql4="INSERT INTO balsheet (userid, type, quantity, price, compid) VALUES (".$_SESSION['id'].",".$_POST['buysell'].",".$row['quantity'].",".$_POST['price'].",".$_POST['company'].")";
									if($_POST['buysell']){
										updAmt($row['userid'],-($row['quantity'] * $_POST['price']));
										updAmt($_SESSION['id'],($row['quantity'] * $_POST['price']));
									}else{
										updAmt($row['userid'],($row['quantity'] * $_POST['price']));
										updAmt($_SESSION['id'],-($row['quantity'] * $_POST['price']));
									}
								}
								$sqlc="SELECT price FROM comp WHERE id=".$_POST['company'];
								$resultc = mysqli_query($conn, $sqlc);
								while($row = mysqli_fetch_assoc($resultc)){
									$pprice=$row['price'];
								}
								$sql5="UPDATE comp SET pprice=".$pprice.", price=".$_POST['price']." WHERE id=".$_POST['company'];
								if(mysqli_query($conn, $sql2) && mysqli_query($conn, $sql3) && mysqli_query($conn, $sql4) && mysqli_query($conn, $sql5)){
									$err="Your order was successful!";
								}
								else{
									$err="Error processing the query." . mysqli_error($conn);
								}
							}
							if($_POST['quantity']==0)
								break;
						}
						if($_POST['quantity']){
							$sql="INSERT INTO buysell (userid, price, quantity, type, compid) VALUES (".$_SESSION['id'].",".$_POST['price'].",".$_POST['quantity'].",".$_POST['buysell'].",".$_POST['company'].") ";
							if(mysqli_query($conn, $sql)){
								$err="Your order was successful!";
							}else{
								$err="Error processing the query." . mysqli_error($conn);
							}
						}
					}
				}
				else{
					$err="Not enough money in your account!";
				}
			}else{
				$err=$err."Please choose the number of stocks between 1-50.";
			}
		}else{
			$err=$err."Please fill in all the details.";
		}
	}else{
		$err=$err."You're not logged in!";
	}
}

function updAmt($id, $amt){

	$pamt=0;
	$fsql="SELECT amount FROM users";
	$result = mysqli_query($conn, $fsql);
	while($row=mysqli_fetch_assoc($result)){
		$pamt=$row['amount'];
	}
	$pamt=$pamt + $amt;
	$fsql="UPDATE users SET amount=".$pamt;
	if(mysqli_query($GLOBALS['conn'], $fsql)){
		$err="Users database updated";
	}
	else
	{
		$err="BIG ERROR!!";
	}
	if($id==$_SESSION['id']){
		$_SESSION['amount']=$pamt;
	}
}

?>
