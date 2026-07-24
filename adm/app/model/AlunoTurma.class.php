<?php
/**
 * Active Record for table alunoturma
 * @author  Claudio Azevedo Passos
 */
class AlunoTurma extends TRecord
{
    const TABLENAME = 'alunoturma';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('cpf');
        parent::addAttribute('idTurma');
        //parent::addAttribute('ano');
    }

    static public function RemoveTurma($cpf)
    {
        return parent::where('cpf', '=', $cpf)->delete(); 
    }

    static public function getTurma($cpf)
    {
        return parent::where('cpf', '=', $cpf)->first(); 
    }
}
?>
