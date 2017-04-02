<?php
session_start();

require_once("./login.php");
require_once("./logout.php");
require_once("./database.php");
require_once("./action.php");


if(isset($_SESSION['id'])){

$stock = "SELECT * FROM balsheet WHERE userid=".$_SESSION['id'];
$stockall = mysqli_query($conn, $stock);
$stockb = mysqli_query($conn, $stock." AND type=0");
$stocks = mysqli_query($conn, $stock." AND type=1");
$baltotal=0;

//--------------------USER LOGGED IN!!

?>














































<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Welcome, <?php echo $_SESSION['name']; ?>!</title>
    <link rel='stylesheet' href="css/font.css"  type='text/css'>
    
    <link rel="stylesheet" href="css/normalize.css">

    <link rel="stylesheet" href="css/style.css">
    <script>

		function Ref() {
		  var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (xhttp.readyState == 4 && xhttp.status == 200) {
		      document.getElementById("stockcontent").innerHTML = xhttp.responseText;
		      Ref();
		    }
		  };
		  xhttp.open("GET", "prices.php", true);
		  xhttp.send();
		}
		</script>

		<!-- ------------------------INCLUDE IN STYLE.CC -------------------------- -->
		<style> 
#user{
  position:fixed;
  left:10px;
  top:10px;
  background-color:rgba(19, 35, 47, 0.6);
  color:#fff;
  padding:10px;
  border-radius:4px;
  -moz-border-radius:4px;
  -webkit-border-radius:4px;
  box-shadow: 0 4px 10px 4px rgba(19, 35, 47, 0.1);
}
.mytab2{
  width:50%;
  background:rgba(160, 179, 176, 0.25);
  text-align: center;
  cursor: pointer;
  padding:15px;
  color: #a0b3b0;
  font-size: 20px;
  float:left;
}
.mytab2:hover, .mytab2-active:hover{
  background:#179b77;
  color:#fff;
}
.mytab2-active{
  background:#1ab188;
  color:#fff;
  width:50%;
  text-align: center;
  cursor: pointer;
  padding:15px;
  font-size: 20px;
  float:left;
}
.balsheet td{
  padding:0px 20px; 
}
		</style>
    
  </head>

  <body>
  <form action="index.php" method="post" >
  	<div id="logout">
  		<input id="logoutbutton" type="submit" name="logout" value="Logout" /><br/>
  	</div>
	</form>

	<div id="user">
		<b><?php echo $_SESSION['name'] ?><br/></b>
		Balance: <b>Rs. <?php echo $_SESSION['amount'] ?>/-</b><br/>
		<a href="index.php">Home</a><br/>
    <?php if($_SESSION['id']==1){ ?><a href="/admin.php">Admin Panel</a><?php } ?>
	</div>

	<div class="heading">
	<h1>Wolf of Wallstreet</h1>
	</div>

    <div class="form">

    	<div class="mytab<?php if(!(isset($_GET['tab'])) || (isset($_GET['tab']) && $_GET['tab']=="all")){ echo("-active"); } ?>" onclick="window.location='?tab=all';">Total</div>
      <div class="mytab<?php if(isset($_GET['tab']) && $_GET['tab']=="bought"){ echo("-active"); } ?>" onclick="window.location='?tab=bought';">Bought</div>
      <div class="mytab<?php if(isset($_GET['tab']) && $_GET['tab']=="sold"){ echo("-active"); } ?>" onclick="window.location='?tab=sold';">Sold</div>


      <div class="tab-contents">
        
      <?php if(!(isset($_GET['tab'])) || $_GET['tab']=="all"){ ?>
        <div id="balall">   
          <h1>All stocks you bought or sold:</h1>
          <center>
					 <table class="balsheet">
            <tr id="tableheader">
              <td>Type</td>
              <td>Qty</td>
              <td>Price</td>
              <td>Total</td>
            </tr>
            <?php while($row = mysqli_fetch_array($stockall)){ ?>
            <tr>
              <td>
                <?php if($row['type']){ ?>Sold<?php }else{ ?>Bought<?php } ?>
              </td>
              <td>
                <?php echo $row['quantity'] ?>
              </td>
              <td>
                <?php echo $row['price'] ?>
              </td>
              <td>
                <?php echo $row['quantity']*$row['price']; ?>
              </td>
            </tr>
            <?php } ?>
            </table>
          </center>
        </div>
      <?php } ?>


	    <?php if(isset($_GET['tab']) && $_GET['tab']=="bought"){ ?>
      <div id="buysell">   
        <h1>Stocks you bought:</h1>
        <center>
        
          <table class="balsheet">
            <tr id="tableheader">
              <td>Type</td>
              <td>Qty</td>
              <td>Price</td>
              <td>Total</td>
            </tr>
            <?php while($row = mysqli_fetch_assoc($stockb)){ ?>
            <tr>
              <td>
                <?php if($row['type']){ ?>Sold<?php }else{ ?>Bought<?php } ?>
              </td>
              <td>
                <?php echo $row['quantity'] ?>
              </td>
              <td>
                <?php echo $row['price'] ?>
              </td>
              <td>
                <?php echo $row['quantity']*$row['price'] ?>
              </td>
            </tr>
            <?php } ?>
          </table>
          
        </center> 
      </div>
      <?php } ?>

      <?php if(isset($_GET['tab']) && $_GET['tab']=="sold"){ ?>
      <div id="buysell">   
        <h1>Stocks you sold:</h1>
        <center>
          
          <table class="balsheet">
            <tr id="tableheader">
              <td>Type</td>
              <td>Qty</td>
              <td>Price</td>
              <td>Total</td>
            </tr>
            <?php while($row = mysqli_fetch_assoc($stocks)){ ?>
            <tr>
              <td>
                <?php if($row['type']){ ?>Sold<?php }else{ ?>Bought<?php } ?>
              </td>
              <td>
                <?php echo $row['quantity'] ?>
              </td>
              <td>
                <?php echo $row['price'] ?>
              </td>
              <td>
                <?php echo $row['quantity']*$row['price'] ?>
              </td>
            </tr>
            <?php } ?>
          </table>
          
        </center> 
      </div>
      <?php } ?>

        
      </div><!-- tab-content -->
      
</div> <!-- /form -->
  <script src="js/first.js"></script>   

  <script src="js/index.js"></script>

    
    
    
  </body>
</html>


<?php }else{ 
//---------------------USER NOT LOGGED IN!!
header("Location:/index.php");
};
?>