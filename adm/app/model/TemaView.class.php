<?php
/**
 * Active Record for table Category
 * @author  Claudio A Passos - Isabel Fernandes - Ronaldo Goldschmidt
 */
class TemaView extends TRecord
{
    const TABLENAME = 'tema2view';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
        parent::addAttribute('descricao');
        parent::addAttribute('idarea');
        parent::addAttribute('visibilidade');
        parent::addAttribute('idautor');
    }
}
?>
