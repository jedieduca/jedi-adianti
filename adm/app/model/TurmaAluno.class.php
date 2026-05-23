<?php
/**
 * Active Record for table turmaaluno
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class TurmaAluno extends TRecord
{
    const TABLENAME = 'turmaaluno';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('idTurma');
        parent::addAttribute('idAluno');
    }

    static public function getAlunos($id)
    {
        //return FrequenciaAulaMonitoria::where('idAula', '=', $idAula)->orderBy('nome', 'asc')->load(); 
        return TurmaAluno::where('idTurma', '=', $id)->load(); 
    }

    static public function getAluno($id)
    {
        return parent::where('idAluno', '=', $id)->first(); 
    }

    static public function RemoveAlunos($id)
    {
        $conn = TTransaction::get();
        // run query
        $sql='delete FROM turmaaluno ';
        $sql.='WHERE idTurma='.$id;
        $conn->query($sql);
        //echo '<pre>'; print_r($sql); echo '</pre>';
    }
}
?>
