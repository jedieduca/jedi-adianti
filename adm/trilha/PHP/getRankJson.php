<?php

     header("Access-Control-Allow-Origin: *");

     $tema = $_GET['tema'];
	 
     require_once 'dbConn.php';
	 
     if(!empty($tema)) 
     {
        $query = "SELECT tema.nome tema, login, jogador, pontuacao, (qtdAcertos/(qtdAcertos+qtdErros))*100 aproveitamento, tempoGasto FROM partidasPerguntas, tema";
        $query .=" WHERE tema.codigo=partidasPerguntas.tema";
        $query .=" and tema=".$tema." and avaliacaoJogo!='Em processo' order by pontuacao desc, (qtdAcertos/(qtdAcertos+qtdErros))*100 desc, tempoGasto asc LIMIT 5";

        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $json = '{ "items":['; 
         foreach($stmt as $row) 
         {
           $json .= '{"tema":"'.$row['tema']; 
           $json .= '","login":"'.$row['login'];
           $json .= '","jogador":"'.$row['jogador'];
           $json .= '","pontuacao":"'.$row['pontuacao'];
           $json .= '","aproveitamento":"'.$row['aproveitamento'];
           $json .= '","tempoGasto":"'.$row['tempoGasto'].'"},';
         }
         $json = substr($json, 0, -1);
         $json .=']}';
         echo $json; 
     } 
	      
?>
