<?php
/**
 * Active Record for table pergunta_categoria
 */
class PerguntaCategoria extends TRecord
{
    const TABLENAME = 'pergunta_categoria';
    const PRIMARYKEY= 'id';
    //const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
        parent::addAttribute('id_tema');
        parent::addAttribute('id_pergunta');
        parent::addAttribute('id_categoria');
    }

    static public function getCategoria($idTema, $idPergunta)
    {
        return parent::where('id_tema', '=', $idTema)->where('id_pergunta', '=', $idPergunta)->load(); 
    }
    
    public function removePerguntaCategoria($idTema, $idPergunta)
    {
        $conn = TTransaction::get();
        // run query
        $sql="delete FROM pergunta_categoria ";
        $sql.="WHERE id_tema={$idTema} AND id_pergunta={$idPergunta} ";
        $conn->query($sql);
    }
}
?>
