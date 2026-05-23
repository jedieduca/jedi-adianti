<?php
     header("Access-Control-Allow-Origin: *");

     $user = $_GET['username'];
     $pass = $_GET['password'];
	 
     require_once('dbConn.php');
	 
     if(isset($user) && isset($pass)) {
         $query = "SELECT id, login, administrador, idturma FROM usuario2 WHERE login = '".$user."' and senha = '".$pass."'";
         //$result = mysqli_query($sqlconnection, $query);
         //$result = $conn->query($query);
         
         //getting result 
        $stmt = $pdo->prepare($query);
        $stmt->execute();
      
        //creating a blank array 
        $result = array();
        
        if ($stmt->rowCount()==0)
        {
             echo utf8_encode("~");
             return;
        }
        else
        {
            //looping through all the records fetched
            foreach($stmt as $row) {
                  /*array_push($result,array("login"=>$row['login'],
                                     "administrador"=>$row['administrador'] ));*/
                  //echo $row['login'].'&'.$row['administrador'];
                  echo $row['login'].'&'.$row['administrador'].'&'.$row['idturma'].'&'.$row['id'];
            }
            
        }
        
     }
?>
