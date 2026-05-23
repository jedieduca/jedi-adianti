<?php

     $tema = $_GET['tema'];

     require_once 'conectaBD.php';
     
     $sql = "Select Pergunta, RespCerta, Resp2, Resp3, Resp4 from Pergunta where Tema=".$tema." order by RAND()";
     //$sql = "Select Pergunta, RespCerta, Resp2, Resp3, Resp4 from Pergunta where Tema=".$tema;
     $result = $conn->query($sql);

    //And now iterate through our results
     while($row = $result->fetch_array())
     {
		 echo utf8_encode($row['Pergunta']) . "\t" . utf8_encode($row['RespCerta']) . "\t" . utf8_encode($row['Resp2']). "\t" . utf8_encode($row['Resp3']). "\t" . utf8_encode($row['Resp4']) . "\n"; 
     }
	 $conn->close();    
?>
