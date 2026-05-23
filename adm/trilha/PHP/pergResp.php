<?php

     $tema = $_GET['tema'];

     require_once 'dbConn.php';
     
     $sql = "Select Pergunta, RespCerta, Resp2, Resp3, Resp4 from pergunta where Tema=".$tema." order by RAND()";
     //$sql = "Select Pergunta, RespCerta, Resp2, Resp3, Resp4 from Pergunta where Tema=".$tema;

     $stmt = $pdo->prepare($sql);
     $stmt->execute();
     
     //$result = $conn->query($sql);

    //And now iterate through our results
    foreach($stmt as $row) 
     //while($row = $result->fetch_array())
     {
          //echo utf8_decode($row['Pergunta']) . "\t" . utf8_encode($row['RespCerta']) . "\t" . utf8_encode($row['Resp2']). "\t" . utf8_encode($row['Resp3']). "\t" . utf8_encode($row['Resp4']) . "\n"; 
          echo $row['Pergunta'] . "\t" . $row['RespCerta'] . "\t" . $row['Resp2']. "\t" . $row['Resp3']. "\t" . $row['Resp4'] . "\n"; 
     }
	 //$conn->close();    
?>
