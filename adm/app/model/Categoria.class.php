<?php
/**
 * Active Record for table categoria
 */
class Categoria extends TRecord
{
    const TABLENAME = 'categoria';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL)
    {
        parent::__construct($id);
        parent::addAttribute('nome');
    }
}
?>
