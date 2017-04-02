<?php
session_start();

require_once("./login.php");
require_once("./logout.php");
require_once("./database.php");
require_once("./action.php");


if(isset($_SESSION['id'])){

$company = "SELECT * FROM comp";
$compresult = mysqli_query($conn, $company);
$compresult2 = mysqli_query($conn, $company);
$buysell = "SELECT * FROM buysell";
$bsres = mysqli_query($conn, $buysell);

//--------------------USER LOGGED IN!!

?>
<html>
	<head>
		<title>
			Welcome <?php echo $_SESSION['name']; ?>!
		</title>
	</head>

	<body>
	<form action="./index.php" method="post" >
		<input style="float:right;margin-right:0px;" type="submit" name="logout" value="Logout" />
	</form>
	User logged in!<br/>
	User id is <?php echo $_SESSION['id']; ?><br/>
	User name is <?php echo $_SESSION['name'] ?><br/>
	Amount in user's account is Rs. <?php echo $_SESSION['amount'] ?>/-<br/>
	<?php if($_SESSION['id']==1){  ?>
		<a href="./tables.php">View and Edit Tables</a><br/>
		<a href="./reg.php">Register New Users</a><br/>
	<?php }; ?>
	<br/><br/><br/>


	<div style="float:right;margin-right:50px;">
		<center>
			<b style="font-size:25px;">Stocks Main Window</b><br/><br/>
			<table>
				<tr>
					<td><b>Company name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
					<td><b>Price</b></td>
				</tr>

				<?php while($comp = mysqli_fetch_assoc($compresult2)){ $company[$comp['id']]=$comp['name'];?>
				<!-- THIS UNIT REPEATS  -->
				<tr>
					<td><?php echo $comp['name'] ?></td>
					<td><?php echo $comp['price'] ?></td>
					<td><?php if($comp['pprice']<$comp['price']){ ?>
						INCREASING<?php }else if($comp['pprice']>$comp['price']){?>
						DECREASING<?php }else{ ?>
						STABLE<?php } ?>
					</td>
				</tr>
				<!-- UNIT ENDS -->
				<?php } ?>
			</table>
		</center>
	</div>

	<div style="float:left;margin-left:50px;">
		<b><?php echo $err; ?></b><br/><br/>
		<form action="./index.php" method="post">
			<table>
				<tr>
					<td colspan="2">What do you want to do?</td>
				</tr>
				<tr>
					<td><input type="radio" id="buy" name="buysell" value="0"/><label for="buy">Buy</label></td>
					<td><input type="radio" id="sell" name="buysell" value="1"/><label for="sell">Sell</label></td>
				</tr>
				<tr>
					<td colspan="2">How many stocks?</td>
				</tr>
				<tr>
					<td colspan="2"><input type="number" name="quantity" placeholder="1-50" /></td>
				</tr>

				<tr>
					<td colspan="2">Which company?<?php echo $typenew; ?></td>
				</tr>
				<tr>
					<td colspan="2">
						<?php while($comp = mysqli_fetch_assoc($compresult)){ ?>
						<input type="radio" id="<?php echo $comp['id']; ?>" name="company" value="<?php echo $comp['id']; ?>"/>
						<label for="<?php echo $comp['id']; ?>"><?php echo $comp['name']; ?></label><br/>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">What is your proposed price?</td>
				</tr>
				<tr>
					<td colspan="2"><input type="number" name="price" /></td>
				</tr>
				<tr>
					<td><input type="submit" name="buysellsubmit" value="Submit" />
			</table>
		</form>
	</div>

	<div style="float:right;margin-right:100px;">
		<table>
			<tr>
				<td><b>What to do</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><b>Price Offd</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><b>How Many</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td><b>Company</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<?php while($row = mysqli_fetch_assoc($bsres)){ ?>
			<tr>
				<td><?php if($row['type']){echo "Sell";}else{echo "Buy";} ?></td>
				<td><?php echo $row['price'] ?></td>
				<td><?php echo $row['quantity'] ?></td>
				<td><?php echo $company[$row['compid']]; ?></td>
			</tr>
			<?php } ?>
		</table>
	</body>
</html>

<?php }else{ 












































//---------------------USER NOT LOGGED IN!!
?>

<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Wolf of Wall Street : Login / Signup</title>
    <link rel='stylesheet' href="css/font.css"  type='text/css'>
    
    <link rel="stylesheet" href="css/normalize.css">

    <link rel="stylesheet" href="css/style.css">

    
  </head>

  <body>

	<div class="heading">
	<h1>Wolf of Wallstreet</h1>
	</div>

    <div class="form">
      
      <!--<ul class="tab-group">
        <li class="tab"><a href="#signup">Sign Up</a></li>
        <li class="tab active"><a href="#login">Log In</a></li>
      </ul>-->
        
        <div id="login">   
          <h1>Some tagline to set the mood</h1>
          <h4 class="formerr"><?php echo $loginerr; ?></h4>
          
          <form action="/" method="post">
          
            <div class="field-wrap">
            <label>
              UserID<span class="req">*</span>
            </label>
            <input type="text" name="user" required autocomplete="off"/>
          </div>
          
          <div class="field-wrap">
            <label>
              Password<span class="req">*</span>
            </label>
            <input type="password" name="password" required autocomplete="off"/>
          </div>
          <input type="hidden" name="login" value="Login" />
          <button class="button button-block"/>Log In</button>
          
          </form>

        </div>
        
      </div><!-- tab-content -->
      
</div> <!-- /form -->
  <script src="js/first.js"></script>   

  <script src="js/index.js"></script>

    
    
    
  </body>
</html>

<?php
};
?>