<?php
     header("Access-Control-Allow-Origin: *");

     $userid = $_GET['userid'];
     $class = $_GET['class'];
	 
     require_once('dbConn.php');

     if ($class==0)
     {
	 
        if(!empty($userid)) 
        {      
            $query = "Select t.id, t.nome, a.descricao area, t.descricao, t.visibilidade from tema2 t, area2 a, usuariotema2 u ";
            $query.= "where t.id in (select idtema from pergunta2) and t.idarea=a.id and t.id=u.idtema and u.userid='" . $userid . "'";
        }
        else
        {
            $query = "Select t.id, t.nome, a.descricao area, t.descricao, t.visibilidade from tema2 t, area2 a, usuariotema2 u ";
            $query.= "where t.id in (select idtema from pergunta2) and t.idarea=a.id and t.id=u.idtema";
        }
    }
    else
    {
        $query = "select t.id, t.nome, a.descricao area, t.descricao, t.visibilidade from tema2 t, turmatema2 tt, usuario2 u, area2 a ";
        $query.= "where t.id=tt.idtema and t.idarea=a.id and tt.idturma=u.idturma and u.id='".$userid."'";
    }
    //echo $query;
	$stmt = $pdo->prepare($query);
	$stmt->execute();

    foreach($stmt as $row) 
    {
       echo $row['id'] . "\t" . utf8_encode($row['nome']) . "\t" . utf8_encode($row['area']). "\t" . utf8_encode($row['descricao']). "\t" . $row['visibilidade'] . "\n"; // And output them 
    }

    /*  
      //getting result 
      $stmt = $pdo->prepare($query);
      $stmt->execute();

      //creating a blank array 
      $result = array();
         
      //looping through all the records fetched
      foreach($stmt as $row) {
                  //echo $row['codigo']; echo $row['nome'];echo $row['area'];echo $row['descricao'];echo $row['visibilidade'];
                  array_push($result,array("codigo"=>$row['codigo'],
                                     "nome"=>utf8_encode($row['nome']),
                                     "area"=>utf8_encode($row['area']), 
                                     "descricao"=>utf8_encode($row['descricao']), 
                                     "visib"=>utf8_encode($row['visibilidade']) ));
        }
        echo json_encode(array('result'=>$result),JSON_UNESCAPED_UNICODE);      
      */
?>
