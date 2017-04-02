<?php

session_start();

require_once("./database.php");

if(isset($_SESSION['id'])){
	$sqlresult = mysqli_query($conn, "SELECT name,amount FROM users WHERE id=".$_SESSION['id']);
	while($row = mysqli_fetch_assoc($sqlresult)){
		$_SESSION['name'] = $row['name'];
		$_SESSION['amount'] = $row['amount'];
	}
}


//----------------------------------------------------- THE FUNCTIONS ARE HERE ----------------------------------------------

function validate($data) {																										//VALIDATES INPUT DATA
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function runMyQuery($sql){																										//RUNS THE MYSQL QUERY
	if(!mysqli_query($GLOBALS['conn'], $sql)){
		throw new Exception("The following query did not succeed: ".$sql."\n Because:".$GLOBALS['conn']->error."\n");
	}
}

function updateAmount($id,$amount){
	$sqlresult = mysqli_query($GLOBALS['conn'], "SELECT * FROM users WHERE id=".$id);
	while($row = mysqli_fetch_assoc($sqlresult)){
		$amount += $row['amount'];
	}
    runMyQuery("UPDATE users set amount=".$amount." WHERE id=".$id);
}

function getAmount($id){
	$amount = 0;
	$sqlresult = mysqli_query($GLOBALS['conn'], "SELECT amount FROM users WHERE id=".$id);
	while($row = mysqli_fetch_assoc($sqlresult)){
		return $row['amount'];
	}
	throw new Exception("Bug in code at place 1");
}

function set_min($compid){
    $sql = ("SELECT * FROM buysell WHERE compid = ".$compid);
    $sql2 = ("SELECT * FROM comp WHERE id = ".$compid);
    $results = mysqli_query($GLOBALS['conn'],$sql);
    $rows = array();
    while($row = $results->fetch_row()){
        $rows[] = $row;   
    }
    print_r($rows);
    if($row2 = mysqli_fetch_assoc(mysqli_query($GLOBALS['conn'],$sql2))){
            foreach($rows as $row1){
                if($row1[5] > 0 and $row1[4] == 1){
                    if($row1[3] < $row2['price']){
                        runMyQuery("UPDATE comp SET price = ".$row1[3]." WHERE id = ".$compid);
                        }
            
                }
            }
        }
    }


?>