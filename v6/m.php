<?php
require_once('./basic.php');
require_once('./database.php');
if(!isset($_SESSION['id']))
	header("Location:index.php");
if(isset($_SESSION['id']) && isset($_POST['buysell'])){
$success = 1;
$bidfound = 0;
	try{
		if(!isset($_POST['buysell']))
			throw new Exception("Access Denied!");
		$postuserid = $_SESSION['id'];
		$postcompid = validate($_POST['compid']);
		$postquantity = validate($_POST['quantity']);
		$postprice = validate($_POST['price']);
		$posttype = validate($_POST['type']);
		if($postquantity == null || $postprice == null || $postcompid == null || ($posttype != 1 && $posttype != 0) ){
			throw new Exception("All parameters not filled in");
		}
		$sqlresult = mysqli_query($conn, "SELECT * FROM comp WHERE id=".$postcompid);
		while($row = mysqli_fetch_assoc($sqlresult)){
			$compprice = $row['price'];
		}
		if(!mysqli_num_rows($sqlresult)){
			throw new Exception("The company does not exist");
		}
		if(!$posttype && $_SESSION['amount'] < ($postprice * $postquantity) ){
			throw new Exception("You don't have enough money in your account");
		}
		if($compprice == 0){
			throw new Exception("Company price has been set to zero!");
		}
		if(isset($compprice) && $postquantity < (2000/$compprice) ){
			throw new Exception("Quantity not in limits");
		}
		if(isset($compprice) && ($postprice > (1.015 * $compprice) || $postprice < (0.985 * $compprice))){
			throw new Exception("Price should be within 1.5% change only");
		}
		$sqlresult = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid);
		$total = 0;
		while($row = mysqli_fetch_assoc($sqlresult)){
			$total += $row['quantity'];
		}
		if($posttype && $postquantity > $total){
			throw new Exception("You don't own the stocks");
		}
         
        if($posttype != 1){
            $sql = ("SELECT FROM buysell WHERE compid = ".$postcompid. "AND price >= ".$postprice);
            if($row = mysqli_fetch_assoc(runMyQuery($sql))){
                if($row['quantity'] !=0 and $postquantity <= $row['quantity'] and postprice >= $row['price'])
                {
                    runMyQuery("INSERT INTO current (userid, compid, quantity, price) VALUES (
							".$_SESSION['id'].",
							".$postcompid.",
							".$tempquantity.",
							".$postprice."
						)");
                    runMyQuery("UPDATE buysell SET quantity = ".$row['quantity']-$postquantity."WHERE compid = ".$postcompid."AND price >= ".$postprice.);
                }
                
            }
            
            
        }

?>


<?php

require_once('./basic.php');
require_once('./database.php');
/*
if(!isset($_SESSION['id']))
	header("Location:index.php");
if(isset($_SESSION['id']) && isset($_POST['buysell'])){
$success = 1;
$bidfound = 0;
	try{
		if(!isset($_POST['buysell']))
			throw new Exception("Access Denied!");
		$postuserid = $_SESSION['id'];
		$postcompid = validate($_POST['compid']);
		$postquantity = validate($_POST['quantity']);
		$postprice = validate($_POST['price']);
		$posttype = validate($_POST['type']);
		if($postquantity == null || $postprice == null || $postcompid == null || ($posttype != 1 && $posttype != 0) ){
			throw new Exception("All parameters not filled in");
		}
		$sqlresult = mysqli_query($conn, "SELECT * FROM comp WHERE id=".$postcompid);
		while($row = mysqli_fetch_assoc($sqlresult)){
			$compprice = $row['price'];
		}
		if(!mysqli_num_rows($sqlresult)){
			throw new Exception("The company does not exist");
		}
		if(!$posttype && $_SESSION['amount'] < ($postprice * $postquantity) ){
			throw new Exception("You don't have enough money in your account");
		}
		if($compprice == 0){
			throw new Exception("Company price has been set to zero!");
		}
		if(isset($compprice) && $postquantity < (2000/$compprice) ){
			throw new Exception("Quantity not in limits");
		}
		if(isset($compprice) && ($postprice > (1.015 * $compprice) || $postprice < (0.985 * $compprice))){
			throw new Exception("Price should be within 1.5% change only");
		}
		$sqlresult = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid);
		$total = 0;
		while($row = mysqli_fetch_assoc($sqlresult)){
			$total += $row['quantity'];
		}
		if($posttype && $postquantity > $total){
			throw new Exception("You don't own the stocks");
		}
        
		if(!$posttype){
			
            $tempquantity=$postquantity;
			//$sqlresult = mysqli_query($conn, "SELECT * FROM buysell WHERE userid <> ".$postuserid." AND compid = ".$postcompid." AND type <> ".$posttype." AND  price = ".$postprice);
			while($row = mysqli_fetch_assoc($sqlresult)){
				if( $_SESSION['amount'] < ($postprice * $tempquantity) ){
					break;
				}
				$bidfound = 1;
				$sb = 0;

				if($tempquantity >= $row['quantity']){

					$cres = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$row['compid']);
					$sellert = 0;
					while($seller = mysqli_fetch_assoc($cres)){
						$sellert += $seller['quantity'];
					}
					if($sellert < $row['quantity'])
						continue;

					runMyQuery("DELETE FROM buysell WHERE id=".$row['id']);
					runMyQuery("INSERT INTO logs (buyer, seller, compid, quantity, price) VALUES(
						". $postuserid .",
						". $row['userid'] .",
						". $postcompid .",
						". $row['quantity'] .",
						". $postprice ."
					) ");

					$noss = $row['quantity'];
					$sb += $row['quantity'];
					$curr=mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$postcompid);
					while($cs = mysqli_fetch_assoc($curr)){
						if($noss >= $cs['quantity']){
							runMyQuery("DELETE FROM current WHERE id=".$cs['id']);
							$noss -= $cs['quantity'];
						}
						else{
							runMyQuery("UPDATE current SET quantity = ". ( $cs['quantity'] - $noss ) ." WHERE id=".$cs['id']);
							$noss = 0;
						}
						if($noss == 0)
							break;
					}
					updateAmount($row['userid'], ($postprice * $row['quantity']) );
					updateAmount($postuserid, -($postprice * $row['quantity']) );
					$tempquantity -= $row['quantity'];
				}else{
					$noss = $tempquantity;
					$sb += $tempquantity;
					$cres = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$row['compid']);
					$sellert = 0;
					while($seller = mysqli_fetch_assoc($cres)){
						$sellert += $seller['quantity'];
					}
					if($sellert < $tempquantity){
						continue;
					}
					//CHANGING THIS WILL KILL YOU!
					runMyQuery("UPDATE buysell SET quantity=".( $row['quantity'] - $noss )." WHERE id=".$row['id']);
					runMyQuery("INSERT INTO logs (buyer, seller, compid, quantity, price) VALUES(
						". $postuserid .",
						". $row['userid'] .",
						". $postcompid .",
						". $tempquantity .",
						". $postprice ."
					) ");
;

					$curr=mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$postcompid);
					while($cs = mysqli_fetch_assoc($curr)){

						if($noss >= $cs['quantity']){

							runMyQuery("DELETE FROM current WHERE id=".$cs['id']);
							$noss -= $cs['quantity'];
						}
						else{

							runMyQuery("UPDATE current SET quantity = ". ( $cs['quantity'] - $noss ) ." WHERE id=".$cs['id']);
							$noss = 0;
						}

						if($noss == 0)
							break;

					}
					updateAmount($row['userid'], ($postprice * $tempquantity) );
					updateAmount($postuserid, - ($postprice * $tempquantity) );
					$tempquantity = 0;
					break;
				}

				if($tempquantity == 0){

					break;
				}




				//give stocks to the current user (buyer)
				$found = 0;
				$curr = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid." AND compid=".$postcompid." AND price=".$postprice);
				while($cs = mysqli_fetch_assoc($curr)){

					runMyQuery("UPDATE current SET quantity=".( $cs['quantity'] + $sb)." WHERE id=".$cs['id'] );
					$found=1;
				}
				if(!$found){

					runMyQuery("INSERT INTO current (userid, compid, price, quantity) VALUES (
						".$postuserid.",
						".$postcompid.",
						".$postprice.",
						".$sb."
					)");
				}
			}


}else{

            
			$tempquantity = $postquantity;
			$sqlresult = mysqli_query($conn, "SELECT * FROM buysell WHERE userid = ".$postuserid." AND compid = ".$postcompid." AND type = ".$posttype." AND  price = ".$postprice);
			print_r($sqlresult);
            while($row = mysqli_fetch_assoc($sqlresult)){


				if( getAmount($row['userid']) < ($postprice * $postquantity) ){
					continue;
				}

				$bidfound = 1;
				$sb = 0;

				if($tempquantity >= $row['quantity']){



					$cres = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid." AND compid=".$row['compid']);
					$sellert = 0;
					while($seller = mysqli_fetch_assoc($cres)){
						$sellert += $seller['quantity'];
					}
					if($sellert < $row['quantity'])
						continue;


					runMyQuery("DELETE FROM buysell WHERE id=".$row['id']);

					runMyQuery("INSERT INTO logs (buyer, seller, compid, quantity, price) VALUES(
						". $row['userid'] .",
						". $postuserid .",
						". $postcompid .",
						". $row['quantity'] .",
						". $postprice ."
					) ");


					$nosb = $row['quantity'];
					$sb += $row['quantity'];

					$curr = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid." AND compid=".$postcompid);
					while($cs = mysqli_fetch_assoc($curr)){

						if($cs['quantity'] > $nosb){

							runMyQuery("UPDATE current SET quantity=".( $cs['quantity'] - $nosb )." WHERE id=".$cs['id'] );
							$nosb=0;
						}
						else{

							runMyQuery("UPDATE current SET quantity=".( $nosb - $cs['quantity'] )." WHERE id=".$cs['id'] );
							$nosb -= $cs['quantity'];
						}
						if(!$nosb){

							break;
						}

					}
					if($nosb){
						throw new Exception("Code bug at place 2");
					}

					$found=0;
					$curr = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$postcompid." AND price=".$postprice);
					while($cs = mysqli_fetch_assoc($curr)){

						runMyQuery("UPDATE current SET quantity=".( $cs['quantity'] + $row['quantity'])." WHERE id=".$cs['id'] );
						$found=1;
					}
					if(!($found)){

						runMyQuery("INSERT INTO current (userid, compid, quantity, price) VALUES (
							".$row['userid'].",
							".$postcompid.",
							".$row['quantity'].",
							".$postprice."
						)");
					}



					updateAmount($row['userid'], - ($postprice * $row['quantity']) );


					updateAmount($postuserid, + ($postprice * $row['quantity']) );
					$tempquantity -= $row['quantity'];



				}else{


					$cres = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid." AND compid=".$row['compid']);
					$sellert = 0;
					while($seller = mysqli_fetch_assoc($cres)){
						$sellert += $seller['quantity'];
					}
					if($sellert < $tempquantity)
						continue;


					runMyQuery("UPDATE buysell SET quantity=".( $row['quantity'] - $tempquantity )." WHERE id=".$row['id']);

					runMyQuery("INSERT INTO logs (buyer, seller, compid, quantity, price) VALUES(
						". $row['userid'] .",
						". $postuserid .",
						". $postcompid .",
						". $tempquantity .",
						". $postprice ."
					) ");



					$nosb = $tempquantity;
					$sb += $tempquantity;
					$curr = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid." AND compid=".$postcompid);
					while($cs = mysqli_fetch_assoc($curr)){

						if($cs['quantity'] > $nosb){

							runMyQuery("UPDATE current SET quantity=".( $cs['quantity'] - $nosb ));
							$nosb = 0;
						}
						else{

							runMyQuery("DELETE FROM current WHERE id=".$cs['id'] );
							$nosb -= $cs['quantity'];
						}
					}
					if($nosb){
						throw new Exception("Code bug at place 4");
					}

					$found=0;

					$curr = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$row['userid']." AND compid=".$postcompid." AND price=".$postprice);
					while($cs = mysqli_fetch_assoc($curr)){

						runMyQuery("UPDATE current SET quantity=".( $cs['quantity'] + $tempquantity));
						$found=1;
					}
					if(!$found){

						runMyQuery("INSERT INTO current (userid, compid, quantity, price) VALUES (
							".$row['userid'].",
							".$postcompid.",
							".$tempquantity.",
							".$postprice."
						)");
					}

					updateAmount($row['userid'], - ($postprice * $tempquantity) );


					updateAmount($postuserid, + ($postprice * $tempquantity) );
					$tempquantity = 0;





					if($tempquantity == 0)
						break;
				}
			}


		}
        $compres = mysqli_query($conn, "SELECT * FROM comp WHERE id=".$postcompid);
		$pprice = 0;  
            
        while($price = mysqli_fetch_assoc($compres)){
				$pprice = $price['price'];
			

        if($bidfound){
			

			

			if(!$pprice){
				throw new Exception("Code bug at place 3");
			}else{
				runMyQuery("UPDATE comp SET price=".$postprice." , pprice=".$pprice);
			}
		
        }
        if($tempquantity and $postprice >= $price['price']){

			runMyQuery("INSERT INTO buysell (userid, compid, price, quantity, type)
				VALUES (
					".$postuserid.",
					".$postcompid.",
					".$postprice.",
					".$tempquantity.",
					".$posttype."
				)");
		      runMyQuery("INSERT INTO current (userid, compid, quantity, price) VALUES (
							".$_SESSION['id'].",
							".$postcompid.",
							".$tempquantity.",
							".$postprice."
						)");    
        }else{
            $success = 0;
        }

        

            }

































	}catch(Exception $e){
		echo $e->getMessage();
		$success = 0;
	}

	if($success){
		echo "Your order was successful!";
	   
    }else{
        echo "Price cannot be lower";
    }



}



*/




//------------------------------------------------------------ Admin panel registeration code here ---------------------------

if(isset($_SESSION['id'])){

	try{


//---------------------------------------------------	REGISTERING NEW USER ------------------------------
		if(isset($_POST['reguser'])){

			if(!(isset($_SESSION['id']) || $_SESSION['id']==1)){
				throw new Exception("You don't have the access");
			}

			//VALIDATING DATA----------------------------
			$postname = validate($_POST['name']);
			$postpass = validate($_POST['password']);
			$postrepass = validate($_POST['re-password']);
			$postamount = 2000000;		//setting 20lac as the dafault amount

			if($postname == NULL || $postpass == NULL || $postrepass == NULL){
				throw new Exception("All Parameters must be filled");
			}

			$regS = 1;
			try{

				if($postpass != $postrepass){
					throw new Exception("Retyped password doesn't match");
				}

			}catch(Exception $error){
				echo $error -> getMessage();
				$regS = 0;
			}

			if($regS){

				runMyQuery("INSERT INTO users (name, password, amount) VALUES(
					'".$postname."',
					'".$postpass."',
					".$postamount."
				)");

				$result = mysqli_query($conn, "SELECT id FROM users WHERE name='".$postname."' AND password='".$postpass."'");
        while($row = mysqli_fetch_assoc($result)){
        	throw new Exception("Registeration successful! ID of new user: <b>".$row['id']."</b>");
        }
			}
		}



//--------------------------------------------------- REGISTERING NEW COMPANY-----------------------------------
		if(isset($_POST['regcomp'])){

			if(!isset($_SESSION['id']) || $_SESSION['id']!=1){
				throw new Exception("You don't have the access");
			}

			//VALIDATING DATA----------------------------
			$postname = validate($_POST['name']);
			$postprice = validate($_POST['price']);
			$postquantity = validate($_POST['quantity']);

			if($postname == NULL || $postprice == NULL || $postquantity == NULL){
				throw new Exception("All Parameters must be filled");
			}


			runMyQuery("INSERT INTO comp ( name, price, pprice ) VALUES(
				'".$postname."',
				".$postprice.",
				".$postprice."
			);");

			$id = 0;
			$result = mysqli_query($conn, "SELECT * FROM comp WHERE name='".$postname."' AND price=".$postprice);
      while($row = mysqli_fetch_assoc($result)){
      	$id = $row['id'];
      }

      runMyQuery("INSERT INTO buysell (userid, compid, quantity, price, type) VALUES(
				1,
				".$id.",
				".$postquantity.",
				".$postprice.",
				1
			);");

      echo("Registeration successful! ID of new company: <b>".$id."</b>");
        }
    }catch(Exception $e){
		echo $e->getMessage();																										//DISPLAY THE ERROR MESSAGE
	}
}


if(isset($_SESSION['id'])){
	if(isset($_POST['delete'])){
		$id = validate($_POST['delete']);
		$result = mysqli_query($conn, "SELECT * FROM buysell WHERE id=".$id." AND userid=".$_SESSION['id']);
    while($row = mysqli_fetch_assoc($result)){
        set_min($row['compid']);
        runMyQuery("INSERT INTO current (userid, compid, quantity, price) VALUES (
							".$_SESSION['id'].",
							".$row['compid'].",
							".$row['quantity'].",
							".$row['price']."
						);");
        runMyQuery("DELETE FROM buysell WHERE id=".$id);
        
    }
	}
}



















//------------------------------------------- GET NEWS -----------------------------------------------------
/*if(isset($_GET['getnews'])){
	$result = mysqli_query($conn, "SELECT * FROM news ORDER BY id DESC LIMIT 5");
  while($row = mysqli_fetch_assoc($result)){

	  echo "<a href=\"news.php?id=".$row['id']."\"><div class=\"news-item\">";
	  echo $row['content']."<br/>";
	  echo "<div style=\"float:right;font-size:12px;\">".$row['time']."</div><br/>";
	  echo "</div></a>";

  }

  $result = mysqli_query($conn, "SELECT * FROM news");
  if (mysqli_num_rows($result) > 5){
  	echo "<a href=\"news.php\"><div class=\"news-item\"><center>Show More</center></div></a>";
  }
}



//------------------------------------- RUN QUERY -------------------------------------------------------------
if(isset($_POST['query']) && isset($_SESSION['id']) && $_SESSION['id'] == 1){
	try{
		$query = validate($_POST['query']);
		runMyQuery($query);
	}catch(Exception $e){
		echo $e->getMessage();
	}
}

*/?>
<?php
require_once('./basic.php');
require_once('./database.php');
if(!isset($_SESSION['id']))
	header("Location:index.php");
if(isset($_SESSION['id']) && isset($_POST['buysell'])){
$success = 1;
$bidfound = 0;
	try{
		if(!isset($_POST['buysell']))
			throw new Exception("Access Denied!");
		$postuserid = $_SESSION['id'];
		$postcompid = validate($_POST['compid']);
		$postquantity = validate($_POST['quantity']);
		$postprice = validate($_POST['price']);
		$posttype = validate($_POST['type']);
		if($postquantity == null || $postprice == null || $postcompid == null || ($posttype != 1 && $posttype != 0) ){
			throw new Exception("All parameters not filled in");
		}
		$sqlresult = mysqli_query($conn, "SELECT * FROM comp WHERE id=".$postcompid);
		while($row = mysqli_fetch_assoc($sqlresult)){
			$compprice = $row['price'];
		}
		if(!mysqli_num_rows($sqlresult)){
			throw new Exception("The company does not exist");
		}
		if(!$posttype && $_SESSION['amount'] < ($postprice * $postquantity) ){
			throw new Exception("You don't have enough money in your account");
		}
		if($compprice == 0){
			throw new Exception("Company price has been set to zero!");
		}
		if(isset($compprice) && $postquantity < (2000/$compprice) ){
			throw new Exception("Quantity not in limits");
		}
		if(isset($compprice) && ($postprice > (1.015 * $compprice) || $postprice < (0.985 * $compprice))){
			throw new Exception("Price should be within 1.5% change only");
		}
		$sqlresult = mysqli_query($conn, "SELECT * FROM current WHERE userid=".$postuserid);
		$total = 0;
		while($row = mysqli_fetch_assoc($sqlresult)){
			$total += $row['quantity'];
		}
		if($posttype && $postquantity > $total){
			throw new Exception("You don't own the stocks");
		}
         
        if($posttype == 0){
            $buy = 1;
            $sql = ("SELECT * FROM buysell WHERE compid = ".$postcompid. " AND price = ".$postprice." AND type = ".$buy);
            $result = mysqli_query($conn,$sql);
            if($row = mysqli_fetch_assoc($result)){
                if($row['quantity'] !=0 and $postquantity <= $row['quantity'] and $postprice = $row['price'])
                {
                    runMyQuery("INSERT INTO current (userid, compid, quantity, price) VALUES (
							".$_SESSION['id'].",
							".$postcompid.",
							".$postquantity.",
							".$postprice."
						);");
                    runMyQuery("INSERT INTO balsheet (userid, compid, quantity, price) VALUES (
							".$_SESSION['id'].",
							".$postcompid.",
							".$postquantity.",
							".$postprice."
						);");
                    if($row['quantity']-$postquantity !=0){
                    runMyQuery("UPDATE buysell SET quantity = ".($row['quantity']-$postquantity)." WHERE compid = ".$postcompid." AND price = ".$row['price']);
                    }else{
                        runMyQuery("DELETE FROM buysell WHERE compid =".$postcompid." AND price = ".$row['price']);
                        set_min($postcompid);
                    }
                    runMyQuery("UPDATE users SET amount = ".($_SESSION['amount'] - ($postprice*$postquantity))." WHERE id = ".$_SESSION['id']);
                    runMyQuery("UPDATE users SET amount = amount+".($postprice*$postquantity)." WHERE id = ".$row['userid']);
                    set_min($postcompid);
                }else{
                    $success = 0;
                    throw new Exception("Data input's invalid");
                }
                
            }
            
        
        }else if($posttype){
             $sql = ("SELECT * FROM current WHERE userid = ".$_SESSION['id']." AND compid = ".$postcompid);
            $result = mysqli_query($conn,$sql);
            if($row = mysqli_fetch_assoc($result)){
                if($row['quantity'] >= $postquantity){
                    runMyQuery("INSERT INTO buysell (userid, compid, price, quantity, type)
				VALUES (
					".$postuserid.",
					".$postcompid.",
					".$postprice.",
					".$postquantity.",
					".$posttype."
				)");
                    
                    runMyQuery("INSERT INTO balsheet (userid, compid, price, quantity, type)
				VALUES (
					".$postuserid.",
					".$postcompid.",
					".$postprice.",
					".$postquantity.",
					".$posttype."
				)");
                    if($row['quantity']-$postquantity !=0){
                        runMyQuery("UPDATE current SET quantity = ".($row['quantity']-$postquantity)." WHERE userid = ".$_SESSION['id']." AND compid = ".$postcompid);
                        set_min($postcompid);
                    }else{
                         runMyQuery("DELETE FROM current WHERE compid =".$postcompid." AND price = ".$row['price']);
                         set_min($postcompid);
                    }
                }
            }
            
        }
    }catch(Exception $e){
		echo $e->getMessage();
		$success = 0;
	}

	if($success){
		echo "Your order was successful!";
    }
}
?>


