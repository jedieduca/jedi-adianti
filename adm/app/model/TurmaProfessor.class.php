<?php
/**
 * Active Record for table turmaprofessor
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class TurmaProfessor extends TRecord
{
    const TABLENAME = 'turma_professor';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_turma');
        parent::addAttribute('id_professor');
    }

    static public function getProfessores($id)
    {
        //return FrequenciaAulaMonitoria::where('idAula', '=', $idAula)->orderBy('nome', 'asc')->load(); 
        return TurmaProfessor::where('id_turma', '=', $id)->load(); 
    }

    static public function getProfessor($id)
    {
        return parent::where('id_professor', '=', $id)->first(); 
    }

    static public function RemoveProfessor($id)
    {
        $conn = TTransaction::get();
        // run query
        $sql='delete FROM turma_professor ';
        $sql.='WHERE id_professor='.$id;
        $conn->query($sql);
        //echo '<pre>'; print_r($sql); echo '</pre>';
    }
}
?>
