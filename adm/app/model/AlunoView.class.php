<?php
/**
 * Active Record for table alunoview
 * @author  Claudio Passos, Isabel Fernandes e Ronaldo Goldshmidt
 */
class AlunoView extends TRecord
{
    const TABLENAME = 'alunoview';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        //parent::addAttribute('cpf');
        parent::addAttribute('name');
        parent::addAttribute('login');
        parent::addAttribute('email');
        parent::addAttribute('id_turma');
        parent::addAttribute('turma');
        parent::addAttribute('active');
    }
}
?>
