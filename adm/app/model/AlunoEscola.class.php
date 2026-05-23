<?php
/**
 * Active Record for table alunoescola
 * @author  Claudio Azevedo Passos
 */
class AlunoEscola extends TRecord
{
    const TABLENAME = 'alunoescola';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('idAluno');
        parent::addAttribute('idEscola');
        //parent::addAttribute('ano');
    }

    static public function getAluno($id)
    {
        return parent::where('idAluno', '=', $id)->first(); 
    }

    static public function RemoveEscola($idAluno)
    {
        return parent::where('idAluno', '=', $idAluno)->delete(); 
    }

    static public function getEscola($idAluno)
    {
        return parent::where('idAluno', '=', $idAluno)->first(); 
    }
}
?>
