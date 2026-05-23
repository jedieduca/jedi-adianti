
<?php 
    //getTema.php

        header('Content-Type: application/json; charset=utf-8');
	//Importing Database Script 
	//require_once('dbConnect.php');
        require_once('dbConn.php');
	
        $user = $_GET['username'];
	
	//Creating sql query
	$sql = "SELECT ut.login, t.* FROM tema t, usuarioTema ut where t.codigo=ut.codTema and login='".$user."'";
	
	//getting result 
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	
        $output.="";
        foreach($stmt as $row) 
                $output.=$row['login']."#".$row['codigo']."#".$row['nome']."#".$row['descricao']."#".$row['codArea']."#".$row['visibilidade'].";";
        
        echo $output;
        
	//creating a blank array 
	/*$result = array();

         // retorna uma tabela      
        
	//looping through all the records fetched
        

        
        	foreach($stmt as $row) {
			array_push($result,array(
                            "login"=>$row['login'],
                            "codigo"=>$row['codigo'],
                            "nome"=>$row['nome'],
                            "descricao"=>$row['descricao'],
                            "codArea"=>$row['codArea'],
                            "visibilidade"=>$row['visibilidade']    
                        ));
                }
	
	//Displaying the array in json format 

	echo json_encode(array('result'=>$result),JSON_UNESCAPED_UNICODE);*/
        
	
?>