<?php
/**
 * Active Record for table perguntacategoria
 * @author  Claudio Passos, Isabel Fernandes e Ronaldo Goldshmidt
 */
class PerguntaCategoria extends TRecord
{
    const TABLENAME = 'perguntacategoria2';
    const PRIMARYKEY= 'id';
    //const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
        parent::addAttribute('tema');
        parent::addAttribute('codPerg');
        parent::addAttribute('categoria');
    }

    static public function getCategoria($idTema, $idPergunta)
    {
        return parent::where('tema', '=', $idTema)->where('codPerg', '=', $idPergunta)->load(); 
    }
    
    public function removePerguntaCategoria($idTema, $idPergunta)
    {
        $conn = TTransaction::get();
        // run query
        $sql="delete FROM perguntacategoria2 ";
        $sql.="WHERE tema={$idTema} AND codPerg={$idPergunta} ";
        $conn->query($sql);
    }
}
?>
