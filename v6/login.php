    <?php
require_once("database.php");

$loginerr="<br/>";

if(isset($_POST['user']) && isset($_POST['password']) && isset($_POST['login'])){
    $id = $_POST['user'];
    $password = $_POST['password'];
	$sql = "SELECT * FROM users WHERE id=".$id. " AND password = "."'".$password."'";
	$result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    

	if($row){
        if($_POST['user']==$row['id'] && $_POST['password']==$row['password']){
			session_start();
            $_SESSION['id']=$row['id'];
			$_SESSION['name']=$row['name'];
			$_SESSION['amount']=$row['amount'];
            header("Location: mypage.php");    
        }
    }else{
			$loginerr="Invalid UserID/Password combination";
		    header("Location: index.php?incorrect={$loginerr}");
        }
}

?>