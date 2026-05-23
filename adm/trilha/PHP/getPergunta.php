<?php 
        header("Access-Control-Allow-Origin: *");
        header('Content-Type: application/json; charset=utf-8');
	//Importing Database Script 
	//require_once('dbConnect.php');
        require_once('dbConn.php');
	
        $user = $_GET['username'];
	
	//Creating sql query
        $sql = "SELECT p.* FROM pergunta p, usuarioTema ut where p.tema=ut.codTema and login='".$user."'";
	
	//getting result 
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	
        $output.="";
        foreach($stmt as $row) 
                $output.=$row['tema']."#".$row['codPerg']."#".$row['pergunta']."#".$row['respCerta']."#".$row['resp2']."#".$row['resp3']."#".$row['resp4'].";";
        
        echo $output;
        
	//creating a blank array 
	/*$result = array();

	//looping through all the records fetched
	foreach($stmt as $row) {
			array_push($result,array(
                            "tema"=>$row['tema'],
                            "codPerg"=>$row['codPerg'],
                            "pergunta"=>$row['pergunta'],
                            "respCerta"=>$row['respCerta'],
                            "resp2"=>$row['resp2'],
                            "resp3"=>$row['resp3'],
                            "resp4"=>$row['resp4'] 
                        ));
	}
        
	//Displaying the array in json format 

	echo json_encode(array('result'=>$result),JSON_UNESCAPED_UNICODE);*/

	
?>