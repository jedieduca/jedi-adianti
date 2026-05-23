<?php 
   
   $tema = $_GET['tema'];

   require_once 'conectaBD.php'; 

   $sql = "select count(*) from Rank where Tema=".$tema;
   $result = $conn->query($sql);
   $row = $result->fetch_array();
   
   if (intval($row[0])>5)
   {
       //$sql = "delete from Rank where Tema=".$tema." and pontuacao in (select pontuacao from Rank order by pontuacao asc limit 1)";  não aceita na versão do mysql
	      $sql = "select pontuacao from Rank where Tema=".$tema." order by pontuacao asc limit 1";
        $result = $conn->query($sql);
        $row = $result->fetch_array();
        $sql = "delete from Rank where Tema=".$tema." and pontuacao=".$row[0];
		    $result = $conn->query($sql);
   }

   $conn->close();
?>

