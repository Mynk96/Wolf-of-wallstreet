<?php

require_once("./basic.php");
require_once("./database.php");
$company = "SELECT * FROM comp ORDER BY name";
$compresult = mysqli_query($conn, $company);

?>
<table class="prices-table">
          <tr class="bidres">
            <td></td><td></td></td><td><td><b>Wants To</b></td><td><b>Price Offd.</b></td><td><b>No.</b></td></td><td>
          </tr>
<?php $companies = array();
    while($comp = $compresult->fetch_row()){ 
	   $companies[] = $comp;
    }
?>
<?php foreach($companies as $comp):?>
        <tr class="compres">
            <td colspan="1" style="text-align:left;max-width:175px;cursor:pointer;" onClick="window.location='company.php?id=<?php echo $comp[0] ?>';"><?php echo $comp[2]; ?></a></td>
            <td colspan="1" class="price<?php if($comp[4]<$comp[3]){ 
		echo "inc";
	}else if($comp[4]>$comp[3]){
		echo "dec";
	}else{ 
		echo "sta";
	} ?>" ><?php echo $comp[3]; ?></td><td></td><td></td><td></td><td></td><td><button class="bid-button" onclick="showBid(<?php echo $comp[0] ?>)">Bid</button></td>
          </tr>
          <?php $bidres = mysqli_query($conn, "SELECT * FROM buysell WHERE compid = ".$comp[0]);
          while($bid = mysqli_fetch_assoc($bidres)){  ?>
          <tr class="bidres">
            <td></td></td><td><td></td>
            <td><?php if(!$bid['type']) echo "Buy"; else echo "Sell"; ?></td>
            <td><?php echo $bid['price']; ?></td>
            <td><?php echo $bid['quantity']; ?></td>
            </td><td><?php if($bid['userid'] == $_SESSION['id']){ ?><div class="del-button" onclick="del(<?php echo $bid['id']; ?>)">Delete</div><?php } ?></td>
          </tr>
        <?php } ?>      
    <?php endforeach;?>


    
        </table>
