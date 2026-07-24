<?php
/**
 * Active Record for table alunoescola
 * @author  Claudio Azevedo Passos
 */
class AlunoEscola extends TRecord
{
    const TABLENAME = 'aluno_escola';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('id_aluno');
        parent::addAttribute('id_escola');
        //parent::addAttribute('ano');
    }

    static public function getAluno($id)
    {
        return parent::where('id_aluno', '=', $id)->first(); 
    }

    static public function RemoveEscola($idAluno)
    {
        return parent::where('id_aluno', '=', $idAluno)->delete(); 
    }

    static public function getEscola($idAluno)
    {
        return parent::where('id_aluno', '=', $idAluno)->first(); 
    }
}
?>
