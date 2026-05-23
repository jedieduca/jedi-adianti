<?php

     header("Access-Control-Allow-Origin: *");
     
     $tema = $_GET['tema'];

     require_once 'dbConn.php';
     
     //$sql = "Select pergunta, id, respcerta, resp2, resp3, resp4, caminhoimagem from pergunta2 where idtema=".$tema." limit 1337";
     $sql = "Select pergunta, id, respcerta, resp2, resp3, resp4, caminhoimagem from pergunta2 where idtema=".$tema." order by RAND() ";
     //$sql = "Select Pergunta, RespCerta, Resp2, Resp3, Resp4 from Pergunta where Tema=".$tema;

     $stmt = $pdo->prepare($sql);
     $stmt->execute();
     
     $resultado = array();
          
          $json = '{ "items":['; 
               foreach($stmt as $row) 
               {
                 $json .= '{"pergunta":"'.$row['pergunta']; 
                 $json .= '","codPerg":"'.$row['id'];
                 $json .= '","respCerta":"'.$row['respcerta'];
                 $json .= '","resp2":"'.$row['resp2'];
                 $json .= '","resp3":"'.$row['resp3'];
                 $json .= '","resp4":"'.$row['resp4'];
                 $json .= '","caminhoImagem":"'.$row['caminhoimagem'].'"},';
               }
               $json = substr($json, 0, -1);
               $json .=']}';
               echo $json;   
?>
