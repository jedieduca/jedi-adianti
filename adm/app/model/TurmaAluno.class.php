<?php
/**
 * Active Record for table turmaaluno
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class TurmaAluno extends TRecord
{
    const TABLENAME = 'turma_aluno';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_turma');
        parent::addAttribute('id_aluno');
    }

    static public function getAlunos($id)
    {
        //return FrequenciaAulaMonitoria::where('idAula', '=', $idAula)->orderBy('nome', 'asc')->load(); 
        return TurmaAluno::where('id_turma', '=', $id)->load(); 
    }

    static public function getAluno($id)
    {
        return parent::where('id_aluno', '=', $id)->first(); 
    }

    static public function RemoveAlunos($id)
    {
        $conn = TTransaction::get();
        // run query
        $sql='delete FROM turma_aluno ';
        $sql.='WHERE id_turma='.$id;
        $conn->query($sql);
        //echo '<pre>'; print_r($sql); echo '</pre>';
    }
}
?>
