<?php
/**
 * Active Record for table nivelensino
 * @author  Claudio A Passos
 */
class NivelEnsino extends TRecord
{
    const TABLENAME = 'nivelensino';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('descricao');
    }
}
?>
